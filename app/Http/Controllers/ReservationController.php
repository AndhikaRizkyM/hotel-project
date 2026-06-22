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
        return view('reservations.create', compact('rooms', 'preselectedRoom'));
    }

    // 4. Store Reservation
    public function store(Request $request)
    {
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
            // Booking details
            'room_id' => ['required', 'exists:rooms,id'],
            'check_in_date' => ['required', 'date', 'after_or_equal:today'],
            'check_out_date' => ['required', 'date', 'after:check_in_date'],
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
            ]
        );

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
        
        return view('reservations.show', compact('reservation', 'fbMenus', 'laundryServices'));
    }

    // 6. Check In Process
    public function checkIn($id)
    {
        $reservation = Reservation::with('room')->findOrFail($id);
        
        if ($reservation->status !== 'RSV') {
            return back()->with('error', 'Only reserved bookings can be checked in.');
        }

        // Change statuses
        $reservation->update(['status' => 'CI']);
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

    // 7. Check Out Process
    public function checkOut(Request $request, $id)
    {
        $reservation = Reservation::with(['room', 'folio'])->findOrFail($id);
        
        if ($reservation->status !== 'CI') {
            return back()->with('error', 'Only checked in guests can be checked out.');
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

    // ==========================================
    // 13. Printable Vouchers views
    // ==========================================
    public function printRegistration($id)
    {
        $reservation = Reservation::with(['guest', 'room.roomType'])->findOrFail($id);
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

    public function printInvoice($id)
    {
        $reservation = Reservation::with(['guest', 'room.roomType', 'deposits', 'folio.items'])->findOrFail($id);
        return view('reservations.print-invoice', compact('reservation'));
    }
}
