<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Reservation;
use App\Models\Deposit;
use App\Models\HousekeepingTask;
use App\Models\FbOrder;
use App\Models\LaundryOrder;
use App\Models\DamageReport;
use App\Models\LostFoundReport;
use App\Models\MaintenanceRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 1. Calculate General Room metrics
        $roomsCount = [
            'total' => Room::count(),
            'available' => Room::where('status', 'A')->count(),
            'occupied' => Room::where('status', 'O')->count(),
            'reserved' => Room::where('status', 'R')->count(),
            'dirty' => Room::where('status', 'D')->count(),
            'cleaning' => Room::where('status', 'C')->count(),
            'maintenance' => Room::where('status', 'M')->count(),
        ];

        // 2. Today's operations
        $todayOps = [
            'checkins' => Reservation::whereDate('check_in_date', today())->count(),
            'checkouts' => Reservation::whereDate('check_out_date', today())->count(),
            'revenue' => (float) Deposit::whereDate('created_at', today())->where('type', 'payment')->sum('amount') 
                         - (float) Deposit::whereDate('created_at', today())->where('type', 'refund')->sum('amount'),
        ];

        // 3. Pending operational lists
        $pending = [
            'hk_tasks' => HousekeepingTask::whereIn('status', ['pending', 'cleaning', 'ready_for_inspection'])->count(),
            'fb_orders' => FbOrder::whereIn('status', ['Pending', 'Preparing', 'Ready'])->count(),
            'maintenance' => MaintenanceRequest::where('status', 'pending')->count(),
        ];

        // 4. Role specific data
        switch ($user->role) {
            case 'Admin':
                $recentReservations = Reservation::with(['guest', 'room'])
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
                $recentDamages = DamageReport::with(['room'])->where('status', 'pending')->take(5)->get();
                return view('dashboards.admin', compact('roomsCount', 'todayOps', 'pending', 'recentReservations', 'recentDamages'));

            case 'FO':
                $arrivalsToday = Reservation::with(['guest', 'room'])
                    ->where('status', 'RSV')
                    ->whereDate('check_in_date', '<=', today())
                    ->get();
                $departuresToday = Reservation::with(['guest', 'room'])
                    ->where('status', 'CI')
                    ->whereDate('check_out_date', '<=', today())
                    ->get();
                $rooms = Room::with('roomType')->orderBy('floor', 'desc')->orderBy('room_number', 'asc')->get();
                return view('dashboards.fo', compact('roomsCount', 'todayOps', 'pending', 'arrivalsToday', 'departuresToday', 'rooms'));

            case 'HK':
                $hkTasks = HousekeepingTask::with(['room', 'assignedTo'])
                    ->whereIn('status', ['pending', 'cleaning', 'ready_for_inspection'])
                    ->get();
                $maintenanceTasks = MaintenanceRequest::with(['room'])
                    ->whereIn('status', ['pending', 'in_progress'])
                    ->get();
                return view('dashboards.hk', compact('roomsCount', 'hkTasks', 'maintenanceTasks'));

            case 'FB':
                $fbOrders = FbOrder::with(['reservation.room', 'items.menu'])
                    ->whereIn('status', ['Pending', 'Preparing', 'Ready'])
                    ->orderBy('order_date', 'asc')
                    ->get();
                // Breakfast list: Reservations currently checked in
                $breakfastGuests = Reservation::with(['guest', 'room.roomType'])
                    ->where('status', 'CI')
                    ->get();
                return view('dashboards.fb', compact('fbOrders', 'breakfastGuests'));
                
            default:
                abort(403, 'Unauthorized dashboard view.');
        }
    }
}
