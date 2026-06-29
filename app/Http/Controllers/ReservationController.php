<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use App\Models\Guest;
use App\Models\Reservation;
use App\Models\Deposit;
use App\Models\GuestFolio;
use App\Models\GuestFolioItem;
use App\Models\ExtrabedRequest;
use App\Models\FbOrder;
use App\Models\FbOrderItem;
use App\Models\FbMenu;
use App\Models\LaundryOrder;
use App\Models\LaundryOrderItem;
use App\Models\LaundryService;
use App\Models\HousekeepingTask;
use App\Models\DamageReport;
use App\Models\LostFoundReport;
use App\Models\MaintenanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReservationController extends Controller
{
    // 1. Room Availability Grid
    public function availability(Request $request)
    {
        $query = Room::with('roomType')->where('is_active', true);

        if ($request->filled('floor')) {
            $query->where('floor', $request->floor);
        }
        if ($request->filled('room_type_id')) {
            $query->where('room_type_id', $request->room_type_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Ordered by Floor (highest to lowest), Room number (lowest to highest)
        $rooms = $query->orderBy('floor', 'desc')->orderBy('room_number', 'asc')->get();
        $roomTypes = RoomType::all();

        return view('reservations.availability', compact('rooms', 'roomTypes'));
    }

    // 2. Reservation History
    public function index(Request $request)
    {
        $query = Reservation::with(['guest', 'room.roomType']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reservation_number', 'like', "%{$search}%")
                  ->orWhereHas('guest', function($g) use ($search) {
                      $g->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reservations = $query->orderBy('created_at', 'desc')->get();
        return view('reservations.index', compact('reservations'));
    }

    // 3. Create Reservation Form
    public function create(Request $request)
    {
        $rooms = Room::with('roomType')->where('status', 'A')->where('is_active', true)->get();
        $preselectedRoom = null;
        if ($request->filled('room_id')) {
            $preselectedRoom = Room::with('roomType')->find($request->room_id);
        }
        $guests = Guest::orderBy('name')->get();
        return view('reservations.create', compact('rooms', 'preselectedRoom', 'guests'));
    }

    // 4. Store Reservation
    public function store(Request $request)
    {
        $guestMode = $request->input('guest_mode', 'new');

        if ($guestMode === 'existing') {
            $request->validate([
                'guest_id' => ['required', 'exists:guests,id'],
                'room_id' => ['required', 'exists:rooms,id'],
                'check_in_date' => ['required', 'date', 'after_or_equal:today'],
                'check_out_date' => ['required', 'date', 'after:check_in_date'],
                'check_in_time' => ['required', 'date_format:H:i'],
                'check_out_time' => ['nullable', 'date_format:H:i'],
                'number_of_guests' => ['required', 'integer', 'min:1'],
                'safety_deposit_box' => ['nullable', 'string', 'max:255'],
                'profession' => ['nullable', 'string', 'max:255'],
                'company' => ['nullable', 'string', 'max:255'],
                'member_card_no' => ['nullable', 'string', 'max:255'],
            ]);
            $guest = Guest::findOrFail($request->guest_id);
            $guest->update([
                'profession' => $request->profession,
                'company' => $request->company,
                'member_card_no' => $request->member_card_no,
            ]);
        } else {
            $request->validate([
                // Guest Details
                'name' => ['required', 'string', 'max:255'],
                'id_number' => ['required', 'string'],
                'birth_date' => ['required', 'date'],
                'gender' => ['required', 'string'],
                'address' => ['nullable', 'string'],
                'country' => ['required', 'string'],
                'phone' => ['required', 'string'],
                'email' => ['nullable', 'email'],
                'vehicle_no' => ['nullable', 'string'],
                'profession' => ['nullable', 'string', 'max:255'],
                'company' => ['nullable', 'string', 'max:255'],
                'member_card_no' => ['nullable', 'string', 'max:255'],
                // Booking details
                'room_id' => ['required', 'exists:rooms,id'],
                'check_in_date' => ['required', 'date', 'after_or_equal:today'],
                'check_out_date' => ['required', 'date', 'after:check_in_date'],
                'check_in_time' => ['required', 'date_format:H:i'],
                'check_out_time' => ['nullable', 'date_format:H:i'],
                'number_of_guests' => ['required', 'integer', 'min:1'],
                'safety_deposit_box' => ['nullable', 'string', 'max:255'],
            ]);

            // Find or create Guest profile
            $guest = Guest::updateOrCreate(
                ['id_number' => $request->id_number],
                [
                    'name' => $request->name,
                    'birth_date' => $request->birth_date,
                    'gender' => $request->gender,
                    'address' => $request->address,
                    'country' => $request->country,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'vehicle_no' => $request->vehicle_no,
                    'profession' => $request->profession,
                    'company' => $request->company,
                    'member_card_no' => $request->member_card_no,
                ]
            );
        }

        $room = Room::findOrFail($request->room_id);
        if ($room->status !== 'A') {
            return back()->with('error', 'Room is not available for booking.');
        }

        $inDate = Carbon::parse($request->check_in_date);
        $outDate = Carbon::parse($request->check_out_date);
        $nights = $inDate->diffInDays($outDate);
        if ($nights <= 0) $nights = 1;

        $roomPrice = $room->roomType->price_per_night;
        $totalRoomCharge = $roomPrice * $nights;
        
        // Charges: 10% tax, 5% service charge
        $tax = $totalRoomCharge * 0.10;
        $service = $totalRoomCharge * 0.05;
        $totalCharge = $totalRoomCharge + $tax + $service;

        // Generate Reservation number: RSV-YYYYMMDD-XXXX
        $reservationNo = 'RSV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));

        $reservation = Reservation::create([
            'reservation_number' => $reservationNo,
            'guest_id' => $guest->id,
            'room_id' => $room->id,
            'check_in_date' => $inDate->toDateString(),
            'check_out_date' => $outDate->toDateString(),
            'check_in_time' => $request->check_in_time,
            'check_out_time' => $request->input('check_out_time', '12:00'),
            'number_of_guests' => $request->input('number_of_guests', 1),
            'safety_deposit_box' => $request->safety_deposit_box,
            'room_charge_per_night' => $roomPrice,
            'total_room_charge' => $totalRoomCharge,
            'tax' => $tax,
            'service_charge' => $service,
            'total_charge' => $totalCharge,
            'status' => 'RSV',
        ]);

        // Update room status to Reserved
        $room->update(['status' => 'R']);

        return redirect()->route('fo.reservations.show', $reservation->id)
            ->with('success', 'Reservation created successfully: ' . $reservationNo);
    }

    // 5. Reservation details & Guest Folio overview
    public function show($id)
    {
        $reservation = Reservation::with(['guest', 'room.roomType', 'deposits', 'folio.items', 'extrabedRequests', 'fbOrders.items', 'laundryOrders.items'])->findOrFail($id);
        
        // Menus for room service order panel
        $fbMenus = FbMenu::where('is_active', true)->get();
        // Laundry options
        $laundryServices = LaundryService::where('is_active', true)->get();

        // Calculate initial reservation totals for check-in settlement
        $totalPaid = $reservation->deposits->where('type', 'payment')->sum('amount') 
                     - $reservation->deposits->where('type', 'refund')->sum('amount');
        $outstanding = $reservation->total_charge - $totalPaid;
        
        return view('reservations.show', compact('reservation', 'fbMenus', 'laundryServices', 'totalPaid', 'outstanding'));
    }

    // 6. Check In Process
    public function checkIn(Request $request, $id)
    {
        $reservation = Reservation::with('room')->findOrFail($id);
        
        if ($reservation->status !== 'RSV') {
            return back()->with('error', 'Only reserved bookings can be checked in.');
        }

        // Validate guarantee options and settlement amount
        $request->validate([
            'guarantee_type' => ['required', 'string', 'in:Cash,Identity Card'],
            'guarantee_card_number' => ['required_if:guarantee_type,Identity Card', 'nullable', 'string', 'max:255'],
            'guarantee_cash_amount' => ['required_if:guarantee_type,Cash', 'nullable', 'numeric', 'min:0'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'payment_method' => ['required_if:amount,>0', 'nullable', 'string'],
        ]);

        $totalPaid = $reservation->deposits()->where('type', 'payment')->sum('amount') 
                     - $reservation->deposits()->where('type', 'refund')->sum('amount');
        $outstanding = $reservation->total_charge - $totalPaid;

        // Process outstanding payment settlement if amount is provided
        if ($request->filled('amount') && $request->amount > 0) {
            if ($request->amount < $outstanding) {
                return back()->with('error', 'Check-in failed: Full settlement of outstanding reservation charges (Rp' . number_format($outstanding, 0, ',', '.') . ') is required to check in.');
            }

            Deposit::create([
                'reservation_id' => $reservation->id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'type' => 'payment',
                'transaction_date' => now(),
                'notes' => 'Reservation check-in settlement payment',
            ]);

            $totalPaid += $request->amount;
            $outstanding -= $request->amount;
        }

        // Enforce that reservation charge must be paid in full to check in
        if ($totalPaid < $reservation->total_charge) {
            return back()->with('error', 'Check-in failed: Outstanding balance is not fully settled. Required: Rp' . number_format($reservation->total_charge, 0, ',', '.') . ', Currently paid: Rp' . number_format($totalPaid, 0, ',', '.'));
        }

        // Determine guarantee detail and process cash deposit if applicable
        $guaranteeDetail = '';
        if ($request->guarantee_type === 'Identity Card') {
            $guaranteeDetail = 'ID Card: ' . $request->guarantee_card_number;
        } elseif ($request->guarantee_type === 'Cash') {
            $cashAmount = $request->guarantee_cash_amount;
            $guaranteeDetail = 'Cash Deposit: Rp' . number_format($cashAmount, 0, ',', '.');

            // Log security deposit to payments ledger
            if ($cashAmount > 0) {
                Deposit::create([
                    'reservation_id' => $reservation->id,
                    'amount' => $cashAmount,
                    'payment_method' => 'Cash',
                    'type' => 'payment',
                    'transaction_date' => now(),
                    'notes' => 'Check-in security deposit (Cash Guarantee)',
                ]);
            }
        }

        // Change statuses and save guarantee
        $reservation->update([
            'status' => 'CI',
            'guarantee_type' => $request->guarantee_type,
            'guarantee_detail' => $guaranteeDetail,
        ]);
        $reservation->room->update(['status' => 'O']);

        // Create Guest Folio
        $folio = GuestFolio::create([
            'reservation_id' => $reservation->id
        ]);

        // Post Room Charge automatically
        $nights = Carbon::parse($reservation->check_in_date)->diffInDays(Carbon::parse($reservation->check_out_date));
        if ($nights <= 0) $nights = 1;

        GuestFolioItem::create([
            'guest_folio_id' => $folio->id,
            'item_type' => 'Room Charge',
            'description' => "Room Charge (Room {$reservation->room->room_number} x {$nights} Nights)",
            'amount' => $reservation->total_room_charge,
        ]);

        // Post Early Check-in Charge if applicable (Rp100.000 / hour before standard check-in time of 14:00 based on reservation check-in time)
        $timezone = 'Asia/Jakarta';
        $standardCheckInTime = Carbon::createFromTime(14, 0, 0, $timezone);
        $timeParts = explode(':', $reservation->check_in_time ?? '14:00:00');
        $reservationCheckInTime = Carbon::createFromTime((int)$timeParts[0], (int)($timeParts[1] ?? 0), (int)($timeParts[2] ?? 0), $timezone);

        if ($reservationCheckInTime->isBefore($standardCheckInTime)) {
            $diffInMinutes = $reservationCheckInTime->diffInMinutes($standardCheckInTime);
            $hoursEarly = (int) ceil($diffInMinutes / 60);
            if ($hoursEarly > 0) {
                $earlyCharge = $hoursEarly * 100000;
                GuestFolioItem::create([
                    'guest_folio_id' => $folio->id,
                    'item_type' => 'Miscellaneous Charge',
                    'description' => "Early Check-in Charge ({$hoursEarly} Hours @ Rp100.000)",
                    'amount' => $earlyCharge,
                ]);
            }
        }

        // Post Tax and Service
        GuestFolioItem::create([
            'guest_folio_id' => $folio->id,
            'item_type' => 'Miscellaneous Charge',
            'description' => 'Hotel Room Tax (10%)',
            'amount' => $reservation->tax,
        ]);

        GuestFolioItem::create([
            'guest_folio_id' => $folio->id,
            'item_type' => 'Miscellaneous Charge',
            'description' => 'Hotel Room Service Charge (5%)',
            'amount' => $reservation->service_charge,
        ]);

        return redirect()->route('fo.reservations.show', $reservation->id)
            ->with('success', 'Guest checked in successfully! Guest Folio created.');
    }

    // 6b. Request Room Inspection
    public function requestInspection($id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->status !== 'CI') {
            return back()->with('error', 'Inspection can only be requested for checked-in reservations.');
        }

        // Check if there is already an active inspection task
        $existingTask = HousekeepingTask::where('reservation_id', $reservation->id)
            ->where('task_type', 'inspection')
            ->where('status', '!=', 'completed')
            ->first();

        if ($existingTask) {
            return back()->with('error', 'An active room inspection is already in progress.');
        }

        // Create the task
        HousekeepingTask::create([
            'room_id' => $reservation->room_id,
            'reservation_id' => $reservation->id,
            'task_type' => 'inspection',
            'status' => 'pending',
        ]);

        return back()->with('success', 'Room inspection requested successfully. Housekeeping has been notified.');
    }

    // 6c. Process Damage Issue from Inspection (Front Office Review)
    public function processDamageIssue(Request $request, $id, $damageId, $action)
    {
        $reservation = Reservation::with('folio')->findOrFail($id);
        $damageReport = DamageReport::where('reservation_id', $reservation->id)->findOrFail($damageId);

        if ($damageReport->status !== 'pending') {
            return back()->with('error', 'This damage issue has already been processed.');
        }

        if ($action === 'charge') {
            $damageReport->update([
                'status' => 'repaired', // Set as processed
                'is_charged_to_folio' => true,
            ]);

            // Add charge to guest folio
            GuestFolioItem::create([
                'guest_folio_id' => $reservation->folio->id,
                'item_type' => 'Damage Charge',
                'description' => "Room Damage Fee - Room {$reservation->room->room_number} ({$damageReport->item_name})",
                'amount' => $damageReport->estimated_cost,
            ]);

            return back()->with('success', 'Damage charge of Rp' . number_format($damageReport->estimated_cost, 0, ',', '.') . ' has been added to folio.');
        } elseif ($action === 'waive') {
            $damageReport->update([
                'status' => 'repaired', // Set as processed
                'is_charged_to_folio' => false,
            ]);

            return back()->with('success', 'Damage charge has been waived.');
        }

        return back()->with('error', 'Invalid action.');
    }

    // 6d. Process Lost Found Issue from Inspection (Front Office Review)
    public function processLostIssue(Request $request, $id, $lostId, $action)
    {
        $reservation = Reservation::with('folio')->findOrFail($id);
        $lostReport = LostFoundReport::where('reservation_id', $reservation->id)->findOrFail($lostId);

        if ($lostReport->status !== 'lost') {
            return back()->with('error', 'This lost item issue has already been processed.');
        }

        if ($action === 'charge') {
            $request->validate([
                'charge_amount' => ['required', 'numeric', 'min:0']
            ]);

            $lostReport->update([
                'status' => 'claimed', // Set as processed
                'claim_date' => now(),
            ]);

            // Add charge to guest folio
            GuestFolioItem::create([
                'guest_folio_id' => $reservation->folio->id,
                'item_type' => 'Lost Item Charge',
                'description' => "Lost Item Penalty - Room {$reservation->room->room_number} ({$lostReport->item_description})",
                'amount' => $request->charge_amount,
            ]);

            return back()->with('success', 'Lost item charge of Rp' . number_format($request->charge_amount, 0, ',', '.') . ' has been added to folio.');
        } elseif ($action === 'waive') {
            $lostReport->update([
                'status' => 'claimed', // Set as processed
                'claim_date' => now(),
            ]);

            return back()->with('success', 'Lost item charge has been waived.');
        }

        return back()->with('error', 'Invalid action.');
    }

    // 7. Check Out Process
    public function checkOut(Request $request, $id)
    {
        $reservation = Reservation::with(['room', 'folio'])->findOrFail($id);
        
        if ($reservation->status !== 'CI') {
            return back()->with('error', 'Only checked in guests can be checked out.');
        }

        // Check if there is an inspection task and if it is completed
        $inspectionTask = HousekeepingTask::where('reservation_id', $reservation->id)
            ->where('task_type', 'inspection')
            ->latest()
            ->first();
        if ($inspectionTask && $inspectionTask->status !== 'completed') {
            return back()->with('error', 'Check-out failed: Room inspection has not been completed.');
        }

        // Check for pending damages or lost items
        $pendingDamages = DamageReport::where('reservation_id', $reservation->id)->where('status', 'pending')->count();
        $pendingLost = LostFoundReport::where('reservation_id', $reservation->id)->where('status', 'lost')->count();
        if ($pendingDamages > 0 || $pendingLost > 0) {
            return back()->with('error', 'Check-out failed: There are still damage or lost item reports that have not been processed by FO.');
        }

        // Post Late Check-out Charge if applicable (Rp100.000 / hour after scheduled check-out time)
        $timezone = 'Asia/Jakarta';
        $timePartsOut = explode(':', $reservation->check_out_time ?? '12:00:00');
        $standardCheckOutTime = Carbon::createFromTime((int)$timePartsOut[0], (int)($timePartsOut[1] ?? 0), (int)($timePartsOut[2] ?? 0), $timezone);
        
        $actualTimeStr = $request->input('actual_check_out_time');
        if ($actualTimeStr) {
            $actualTimeParts = explode(':', $actualTimeStr);
            $actualCheckOutTime = Carbon::createFromTime((int)$actualTimeParts[0], (int)($actualTimeParts[1] ?? 0), 0, $timezone);
            
            if ($actualCheckOutTime->isAfter($standardCheckOutTime)) {
                $diffInMinutes = $standardCheckOutTime->diffInMinutes($actualCheckOutTime);
                $hoursLate = (int) ceil($diffInMinutes / 60);
                if ($hoursLate > 0) {
                    $lateCharge = $hoursLate * 100000;
                    
                    // Avoid duplicate late charge posting on page reload / repeat submit
                    $hasLateCharge = GuestFolioItem::where('guest_folio_id', $reservation->folio->id)
                        ->where('description', 'like', 'Late Check-out Charge%')
                        ->exists();

                    if (!$hasLateCharge) {
                        GuestFolioItem::create([
                            'guest_folio_id' => $reservation->folio->id,
                            'item_type' => 'Miscellaneous Charge',
                            'description' => "Late Check-out Charge ({$hoursLate} Hours @ Rp100.000)",
                            'amount' => $lateCharge,
                        ]);
                    }
                }
            }
        }

        // Sum up folio charges
        $totalCharges = (float) $reservation->folio->items()->sum('amount');
        $totalPaid = (float) $reservation->deposits()->where('type', 'payment')->sum('amount') 
                     - (float) $reservation->deposits()->where('type', 'refund')->sum('amount');
        
        $balance = $totalCharges - $totalPaid;

        if (abs($balance) > 0.01) {
            // Process payment in checkout
            $request->validate([
                'payment_method' => ['required', 'string']
            ]);

            // Add final payment log
            Deposit::create([
                'reservation_id' => $reservation->id,
                'amount' => $balance,
                'payment_method' => $request->payment_method,
                'type' => 'payment',
                'transaction_date' => now(),
                'notes' => 'Checkout balance settlement payment',
            ]);
        }

        // Change statuses
        $reservation->update(['status' => 'CO']);
        $reservation->room->update(['status' => 'D']); // Dirty

        // Create cleaning task for Housekeeping
        HousekeepingTask::create([
            'room_id' => $reservation->room_id,
            'task_type' => 'cleaning_checkout',
            'status' => 'pending',
        ]);

        return redirect()->route('fo.reservations.show', $reservation->id)
            ->with('success', 'Guest checked out successfully! Room status changed to Dirty.');
    }

    // 8. Cancel booking
    public function cancel($id)
    {
        $reservation = Reservation::with('room')->findOrFail($id);
        
        if ($reservation->status !== 'RSV') {
            return back()->with('error', 'Only reserved bookings can be cancelled.');
        }

        $reservation->update(['status' => 'CAN']);
        $reservation->room->update(['status' => 'A']); // back to Available

        return back()->with('success', 'Reservation cancelled successfully.');
    }

    // 8b. No Show booking
    public function noShow($id)
    {
        $reservation = Reservation::with('room')->findOrFail($id);
        
        if ($reservation->status !== 'RSV') {
            return back()->with('error', 'Only reserved bookings can be set as No Show.');
        }

        $reservation->update(['status' => 'NS']);
        $reservation->room->update(['status' => 'A']); // back to Available

        return back()->with('success', 'Reservation marked as No Show.');
    }

    // 9. Extra Bed Request Action from FO
    public function storeExtrabed(Request $request, $id)
    {
        $reservation = Reservation::with('room.roomType', 'folio')->findOrFail($id);
        
        $request->validate([
            'qty' => ['required', 'integer', 'min:1', 'max:2'],
        ]);

        if (!$reservation->room->roomType->extra_bed_available) {
            return back()->with('error', 'Extra Bed is not supported for this room type.');
        }

        $nights = Carbon::parse($reservation->check_in_date)->diffInDays(Carbon::parse($reservation->check_out_date));
        if ($nights <= 0) $nights = 1;
        
        $unitPrice = 150000.00;
        $totalPrice = $unitPrice * $request->qty * $nights;

        $extraRequest = ExtrabedRequest::create([
            'reservation_id' => $reservation->id,
            'qty' => $request->qty,
            'price_per_night' => $unitPrice,
            'num_nights' => $nights,
            'total_price' => $totalPrice,
            'status' => 'requested',
            'request_date' => now(),
        ]);

        // Post to Guest Folio if Checked In
        if ($reservation->status === 'CI' && $reservation->folio) {
            GuestFolioItem::create([
                'guest_folio_id' => $reservation->folio->id,
                'item_type' => 'Extra Bed',
                'description' => "Extra Bed Charge ({$request->qty} Bed x {$nights} Nights)",
                'amount' => $totalPrice,
                'reference_id' => $extraRequest->id,
            ]);
        }

        return back()->with('success', 'Extra Bed request logged successfully.');
    }

    // 10. Room Service Order Action from FO
    public function storeFbOrder(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $request->validate([
            'items' => ['required', 'array'],
            'items.*.menu_id' => ['required', 'exists:fb_menus,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
        ]);

        $totalAmount = 0.00;
        $orderItems = [];

        foreach ($request->items as $itemData) {
            $menu = FbMenu::findOrFail($itemData['menu_id']);
            $subtotal = $menu->price * $itemData['qty'];
            $totalAmount += $subtotal;
            
            $orderItems[] = [
                'fb_menu_id' => $menu->id,
                'qty' => $itemData['qty'],
                'price' => $menu->price,
                'subtotal' => $subtotal,
            ];
        }

        $order = FbOrder::create([
            'reservation_id' => $reservation->id,
            'status' => 'Pending',
            'order_date' => now(),
            'total_amount' => $totalAmount,
        ]);

        foreach ($orderItems as $item) {
            $order->items()->create($item);
        }

        return back()->with('success', 'Room service food order placed successfully.');
    }

    // 11. Laundry Order Action from FO
    public function storeLaundryOrder(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $request->validate([
            'laundry_service_id' => ['required', 'exists:laundry_services,id'],
            'items' => ['required', 'array'],
            'items.*.name' => ['required', 'string'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
        ]);

        $service = LaundryService::findOrFail($request->laundry_service_id);
        $totalAmount = 0.00;
        $orderItems = [];

        foreach ($request->items as $itemData) {
            $subtotal = $service->price * $itemData['qty'];
            $totalAmount += $subtotal;
            
            $orderItems[] = [
                'item_name' => $itemData['name'],
                'qty' => $itemData['qty'],
                'price' => $service->price,
                'subtotal' => $subtotal,
            ];
        }

        $order = LaundryOrder::create([
            'reservation_id' => $reservation->id,
            'laundry_service_id' => $service->id,
            'status' => 'Pending',
            'order_date' => now(),
            'total_amount' => $totalAmount,
        ]);

        foreach ($orderItems as $item) {
            $order->items()->create($item);
        }

        return back()->with('success', 'Laundry order logged successfully.');
    }

    // 12. Guest profiles management
    public function guestsIndex()
    {
        $guests = Guest::orderBy('name')->get();
        return view('reservations.guests.index', compact('guests'));
    }

    public function guestsShow($id)
    {
        $guest = Guest::with('reservations.room')->findOrFail($id);
        return view('reservations.guests.show', compact('guest'));
    }

    public function guestsUpdate(Request $request, $id)
    {
        $guest = Guest::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'gender' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'email' => ['nullable', 'email'],
            'address' => ['nullable', 'string'],
            'country' => ['required', 'string'],
            'vehicle_no' => ['nullable', 'string'],
        ]);

        $guest->update($request->all());

        return redirect()->route('fo.guests.show', $guest->id)->with('success', 'Guest profile updated successfully.');
    }

    public function guestsStore(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'id_number' => ['required', 'string', 'max:255', 'unique:guests,id_number'],
            'birth_date' => ['required', 'date'],
            'gender' => ['required', 'string', 'in:Male,Female'],
            'phone' => ['required', 'string'],
            'email' => ['nullable', 'email'],
            'address' => ['nullable', 'string'],
            'country' => ['required', 'string'],
            'vehicle_no' => ['nullable', 'string'],
        ]);

        Guest::create($request->all());

        return redirect()->route('fo.guests.index')->with('success', 'New guest profile has been successfully added.');
    }

    public function guestsDestroy($id)
    {
        $guest = Guest::findOrFail($id);
        $guest->delete();

        return redirect()->route('fo.guests.index')->with('success', 'Guest profile has been successfully deleted.');
    }

    // ==========================================
    // 13. Printable Vouchers views
    // ==========================================
    public function printRegistration(Request $request, $id)
    {
        $reservation = Reservation::with(['guest', 'room.roomType'])->findOrFail($id);
        $template = $request->query('template', 'classic');
        if ($template === 'modern') {
            return view('reservations.print-registration-new', compact('reservation'));
        }
        return view('reservations.print-registration', compact('reservation'));
    }

    public function printExtrabed($id)
    {
        $reservation = Reservation::with(['guest', 'room'])->findOrFail($id);
        $extraBed = $reservation->extrabedRequests()->latest()->first();
        return view('reservations.print-extrabed', compact('reservation', 'extraBed'));
    }

    public function printMisc($id)
    {
        $reservation = Reservation::with(['guest', 'room', 'folio.items'])->findOrFail($id);
        return view('reservations.print-misc', compact('reservation'));
    }

    public function printInvoice(Request $request, $id)
    {
        $reservation = Reservation::with(['guest', 'room.roomType', 'deposits', 'folio.items'])->findOrFail($id);
        $template = $request->query('template', 'classic');
        if ($template === 'modern') {
            return view('reservations.print-invoice-new', compact('reservation'));
        }
        return view('reservations.print-invoice', compact('reservation'));
    }
}
