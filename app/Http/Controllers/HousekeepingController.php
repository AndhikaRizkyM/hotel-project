<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use App\Models\Reservation;
use App\Models\HousekeepingTask;
use App\Models\RoomInspection;
use App\Models\DamageReport;
use App\Models\LostFoundReport;
use App\Models\MaintenanceRequest;
use App\Models\ExtrabedRequest;
use App\Models\GuestFolioItem;
use Illuminate\Http\Request;

class HousekeepingController extends Controller
{
    // 1. Cleaning Tasks Overview
    public function tasksIndex(Request $request)
    {
        $query = HousekeepingTask::with(['room.roomType', 'assignedTo']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('floor')) {
            $query->whereHas('room', function($q) use ($request) {
                $q->where('floor', $request->floor);
            });
        }

        $tasks = $query->orderBy('created_at', 'desc')->get();
        $hkStaff = User::where('role', 'HK')->where('status', 'active')->get();
        $rooms = Room::where('is_active', true)->orderBy('room_number')->get();

        // Load other modules for the unified hub
        $pendingTasks = HousekeepingTask::with('room.roomType')
            ->where('status', 'ready_for_inspection')
            ->get();
        $inspections = RoomInspection::with(['room.roomType', 'housekeepingTask.assignedTo'])
            ->orderBy('created_at', 'desc')
            ->get();
        $damages = DamageReport::with(['room', 'reportedBy', 'guest'])
            ->orderBy('created_at', 'desc')
            ->get();
        $activeReservations = Reservation::with('guest')
            ->where('status', 'CI')
            ->get();
        $lostFoundReports = LostFoundReport::with(['room', 'reportedBy'])
            ->orderBy('created_at', 'desc')
            ->get();
        $maintenanceRequests = MaintenanceRequest::with(['room', 'reportedBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('housekeeping.hub', compact(
            'tasks', 'hkStaff', 'rooms', 
            'pendingTasks', 'inspections', 
            'damages', 'activeReservations', 
            'lostFoundReports', 
            'maintenanceRequests'
        ));
    }

    public function startTask(Request $request, $id)
    {
        $task = HousekeepingTask::findOrFail($id);
        
        $request->validate([
            'assigned_to_user_id' => ['nullable', 'exists:users,id'],
        ]);

        $task->update([
            'status' => 'cleaning',
            'assigned_to_user_id' => $request->assigned_to_user_id ?? auth()->id(),
            'start_time' => now(),
        ]);

        // Set room status to 'C' (Cleaning)
        $task->room->update(['status' => 'C']);

        return back()->with('success', 'Cleaning task started.');
    }

    public function completeTask($id)
    {
        $task = HousekeepingTask::findOrFail($id);
        
        $task->update([
            'status' => 'ready_for_inspection',
            'end_time' => now(),
        ]);

        // Room is ready for inspection
        return back()->with('success', 'Cleaning complete. Room is now pending inspection.');
    }

    // 2. Room Inspections
    public function inspectionsIndex()
    {
        // Tasks ready for inspection
        $pendingTasks = HousekeepingTask::with('room.roomType')
            ->where('status', 'ready_for_inspection')
            ->get();

        $inspections = RoomInspection::with(['room.roomType', 'housekeepingTask.assignedTo'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('housekeeping.inspections', compact('pendingTasks', 'inspections'));
    }

    public function storeInspection(Request $request)
    {
        $request->validate([
            'housekeeping_task_id' => ['required', 'exists:housekeeping_tasks,id'],
            'result' => ['required', 'string', 'in:passed,failed'],
            'notes' => ['nullable', 'string'],
            
            // Optional integrated issues fields
            'report_damage' => ['nullable', 'boolean'],
            'damage_item_name' => ['required_if:report_damage,1', 'nullable', 'string', 'max:255'],
            'damage_description' => ['required_if:report_damage,1', 'nullable', 'string'],
            'damage_estimated_cost' => ['required_if:report_damage,1', 'nullable', 'numeric', 'min:0'],
            'damage_is_charged_to_folio' => ['nullable', 'boolean'],

            'report_lost' => ['nullable', 'boolean'],
            'lost_item_description' => ['required_if:report_lost,1', 'nullable', 'string'],
            'lost_location_found' => ['required_if:report_lost,1', 'nullable', 'string', 'max:255'],
            'lost_guest_name' => ['nullable', 'string', 'max:255'],
            'lost_contact_number' => ['nullable', 'string', 'max:255'],

            'report_maintenance' => ['nullable', 'boolean'],
            'maintenance_description' => ['required_if:report_maintenance,1', 'nullable', 'string'],
            'maintenance_priority' => ['required_if:report_maintenance,1', 'nullable', 'string', 'in:low,medium,high'],
            'maintenance_estimated_cost' => ['required_if:report_maintenance,1', 'nullable', 'numeric', 'min:0'],
        ]);

        $task = HousekeepingTask::with(['room', 'reservation'])->findOrFail($request->housekeeping_task_id);

        if ($task->task_type === 'inspection') {
            // For pre-checkout room inspection, guest is still checked-in, so room remains Occupied (O)
            $statusAfter = 'Occupied';
            if ($request->result === 'passed') {
                $task->update(['status' => 'completed']);
            } else {
                $task->update(['status' => 'pending']); // retry inspection or trigger task completion later
            }
            $task->room->update(['status' => 'O']);
        } else {
            // Regular daily cleaning or checkout cleaning inspections
            if ($request->result === 'passed') {
                $statusAfter = 'Available';
                $task->update(['status' => 'completed']);
                $task->room->update(['status' => 'A']); // Available
            } else {
                $statusAfter = 'Dirty';
                $task->update(['status' => 'pending']); // return to pending for re-cleaning
                $task->room->update(['status' => 'D']); // Dirty
            }
        }

        RoomInspection::create([
            'housekeeping_task_id' => $task->id,
            'room_id' => $task->room_id,
            'result' => $request->result,
            'status_after_inspection' => $statusAfter,
            'notes' => $request->notes,
        ]);

        // Find active reservation for guest details & folio charging
        $reservation = $task->reservation;
        if (!$reservation) {
            $reservation = Reservation::where('room_id', $task->room_id)->where('status', 'CI')->first();
        }

        // 1. Process damage report if checked
        if ($request->report_damage) {
            $guestId = $reservation ? $reservation->guest_id : null;
            $resId = $reservation ? $reservation->id : null;
            $chargeFolio = $request->has('damage_is_charged_to_folio') && $request->damage_is_charged_to_folio;
            
            if ($chargeFolio && $reservation && $reservation->folio) {
                GuestFolioItem::create([
                    'guest_folio_id' => $reservation->folio->id,
                    'item_type' => 'Damage Charge',
                    'description' => "Room Damage Fee - Room {$task->room->room_number} ({$request->damage_item_name})",
                    'amount' => $request->damage_estimated_cost,
                ]);
            }
            
            DamageReport::create([
                'room_id' => $task->room_id,
                'reported_by_user_id' => auth()->id(),
                'guest_id' => $guestId,
                'reservation_id' => $resId,
                'item_name' => $request->damage_item_name,
                'description' => $request->damage_description,
                'estimated_cost' => $request->damage_estimated_cost,
                'is_charged_to_folio' => $chargeFolio,
                'status' => 'pending',
            ]);
        }

        // 2. Process lost & found if checked
        if ($request->report_lost) {
            $resId = $reservation ? $reservation->id : null;
            LostFoundReport::create([
                'room_id' => $task->room_id,
                'reported_by_user_id' => auth()->id(),
                'reservation_id' => $resId,
                'item_description' => $request->lost_item_description,
                'location_found' => $request->lost_location_found,
                'guest_name' => $request->lost_guest_name,
                'contact_number' => $request->lost_contact_number,
                'status' => 'lost',
            ]);
        }

        // 3. Process maintenance if checked
        if ($request->report_maintenance) {
            MaintenanceRequest::create([
                'room_id' => $task->room_id,
                'reported_by_user_id' => auth()->id(),
                'description' => $request->maintenance_description,
                'priority' => $request->maintenance_priority,
                'estimated_cost' => $request->maintenance_estimated_cost,
                'status' => 'pending',
            ]);
            // Update room status to 'M' (Maintenance)
            $task->room->update(['status' => 'M']);
        }

        return redirect()->route('hk.tasks', ['tab' => 'inspections'])->with('success', 'Inspection and reported issues logged successfully.');
    }

    // 3. Damage Reports
    public function damagesIndex()
    {
        $damages = DamageReport::with(['room', 'reportedBy', 'guest'])
            ->orderBy('created_at', 'desc')
            ->get();

        $rooms = Room::where('is_active', true)->get();
        
        // Active reservations to link guest profiles
        $activeReservations = Reservation::with('guest')
            ->where('status', 'CI')
            ->get();

        return view('housekeeping.damages', compact('damages', 'rooms', 'activeReservations'));
    }

    public function storeDamage(Request $request)
    {
        $request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
            'item_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'estimated_cost' => ['required', 'numeric', 'min:0'],
            'reservation_id' => ['nullable', 'exists:reservations,id'],
            'is_charged_to_folio' => ['nullable', 'boolean'],
        ]);

        $guestId = null;
        $chargeFolio = $request->has('is_charged_to_folio') && $request->is_charged_to_folio;

        if ($request->filled('reservation_id')) {
            $reservation = Reservation::with('folio')->findOrFail($request->reservation_id);
            $guestId = $reservation->guest_id;

            if ($chargeFolio && $reservation->folio) {
                GuestFolioItem::create([
                    'guest_folio_id' => $reservation->folio->id,
                    'item_type' => 'Damage Charge',
                    'description' => "Room Damage Fee - Room {$reservation->room->room_number} ({$request->item_name})",
                    'amount' => $request->estimated_cost,
                ]);
            }
        }

        DamageReport::create([
            'room_id' => $request->room_id,
            'reported_by_user_id' => auth()->id(),
            'guest_id' => $guestId,
            'reservation_id' => $request->reservation_id,
            'item_name' => $request->item_name,
            'description' => $request->description,
            'estimated_cost' => $request->estimated_cost,
            'is_charged_to_folio' => $chargeFolio,
            'status' => 'pending',
        ]);

        return redirect()->route('hk.tasks', ['tab' => 'report'])->with('success', 'Damage report submitted.');
    }

    // 4. Lost & Found
    public function lostFoundIndex()
    {
        $reports = LostFoundReport::with(['room', 'reportedBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        $rooms = Room::where('is_active', true)->get();

        return view('housekeeping.lost-found', compact('reports', 'rooms'));
    }

    public function storeLostFound(Request $request)
    {
        $request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
            'item_description' => ['required', 'string'],
            'location_found' => ['nullable', 'string', 'max:255'],
            'guest_name' => ['nullable', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:255'],
            'reservation_id' => ['nullable', 'exists:reservations,id'],
        ]);

        LostFoundReport::create([
            'room_id' => $request->room_id,
            'reported_by_user_id' => auth()->id(),
            'reservation_id' => $request->reservation_id,
            'item_description' => $request->item_description,
            'location_found' => $request->location_found,
            'guest_name' => $request->guest_name,
            'contact_number' => $request->contact_number,
            'status' => 'lost',
        ]);

        return redirect()->route('hk.tasks', ['tab' => 'report'])->with('success', 'Lost & Found item recorded.');
    }

    public function claimLostFound(Request $request, $id)
    {
        $report = LostFoundReport::findOrFail($id);
        
        $report->update([
            'status' => 'claimed',
            'claim_date' => now(),
        ]);

        return redirect()->route('hk.tasks', ['tab' => 'logs'])->with('success', 'Item marked as claimed.');
    }

    // 5. Maintenance Requests
    public function maintenanceIndex()
    {
        $requests = MaintenanceRequest::with(['room', 'reportedBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        $rooms = Room::where('is_active', true)->get();

        return view('housekeeping.maintenance', compact('requests', 'rooms'));
    }

    public function storeMaintenance(Request $request)
    {
        $request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
            'description' => ['required', 'string'],
            'priority' => ['required', 'string', 'in:low,medium,high'],
            'estimated_cost' => ['required', 'numeric', 'min:0'],
        ]);

        MaintenanceRequest::create([
            'room_id' => $request->room_id,
            'reported_by_user_id' => auth()->id(),
            'description' => $request->description,
            'priority' => $request->priority,
            'estimated_cost' => $request->estimated_cost,
            'status' => 'pending',
        ]);

        // Update room status to 'M' (Maintenance)
        Room::findOrFail($request->room_id)->update(['status' => 'M']);

        return redirect()->route('hk.tasks', ['tab' => 'report'])->with('success', 'Maintenance request logged. Room status set to Maintenance.');
    }

    public function completeMaintenance(Request $request, $id)
    {
        $req = MaintenanceRequest::findOrFail($id);

        $req->update([
            'status' => 'completed',
            'completion_date' => now(),
        ]);

        // After maintenance, set room status back to 'D' (Dirty) so housekeeping cleans it
        $req->room->update(['status' => 'D']);

        // Create housekeeping task to clean the room
        HousekeepingTask::create([
            'room_id' => $req->room_id,
            'task_type' => 'cleaning_checkout', // standard cleaning
            'status' => 'pending',
        ]);

        return redirect()->route('hk.tasks', ['tab' => 'logs'])->with('success', 'Maintenance completed. Room status set to Dirty (Pending Cleaning).');
    }

    // 6. Extra Bed Task Complete
    public function completeExtrabedTask($id)
    {
        $extraBed = ExtrabedRequest::findOrFail($id);
        
        // Transition status: requested -> installed
        $newStatus = $extraBed->status === 'requested' ? 'installed' : 'removed';
        $extraBed->update(['status' => $newStatus]);

        return back()->with('success', "Extra bed status updated to: {$newStatus}");
    }
}
