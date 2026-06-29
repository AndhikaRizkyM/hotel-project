<?php

namespace App\Http\Controllers;

use App\Models\FbOrder;
use App\Models\Reservation;
use App\Models\GuestFolioItem;
use Illuminate\Http\Request;

class FbController extends Controller
{
    public function breakfastList(Request $request)
    {
        $date = $request->filled('date') ? $request->date : today()->toDateString();
        
        // Checked in reservations today
        $guests = Reservation::with(['guest', 'room.roomType'])
            ->where('status', 'CI')
            ->get();

        // Load or create breakfast records for today
        foreach ($guests as $guest) {
            $record = \App\Models\BreakfastRecord::firstOrCreate(
                [
                    'reservation_id' => $guest->id,
                    'date' => $date,
                ],
                [
                    'status' => 'Pending',
                    'pax' => $guest->room->roomType->capacity,
                    'timeline' => [
                        [
                            'status' => 'Pending',
                            'time' => now()->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
                            'user' => auth()->user()->name,
                        ]
                    ]
                ]
            );
            $guest->breakfast_record = $record;
        }

        return view('fb.breakfast', compact('guests', 'date'));
    }

    public function updateBreakfastStatus(Request $request, $id)
    {
        $record = \App\Models\BreakfastRecord::findOrFail($id);

        $request->validate([
            'status' => ['required', 'string', 'in:Pending,Preparing,Delivered,Skipped'],
            'notes' => ['nullable', 'string']
        ]);

        $newStatus = $request->status;

        $timeline = $record->timeline ?? [];
        $timeline[] = [
            'status' => $newStatus,
            'time' => now()->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'user' => auth()->user()->name,
        ];

        $record->update([
            'status' => $newStatus,
            'notes' => $request->notes,
            'timeline' => $timeline,
        ]);

        return back()->with('success', "Breakfast status updated to {$newStatus}.");
    }

    public function ordersIndex()
    {
        $activeOrders = FbOrder::with(['reservation.room', 'items.menu'])
            ->whereIn('status', ['Pending', 'Preparing', 'Ready'])
            ->orderBy('order_date', 'asc')
            ->get();

        $completedOrders = FbOrder::with(['reservation.room', 'items.menu'])
            ->whereIn('status', ['Delivered', 'Cancelled'])
            ->orderBy('updated_at', 'desc')
            ->take(20)
            ->get();

        return view('fb.orders', compact('activeOrders', 'completedOrders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = FbOrder::with('reservation.folio')->findOrFail($id);
        
        $request->validate([
            'status' => ['required', 'string', 'in:Pending,Preparing,Ready,Delivered,Cancelled']
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        $order->update(['status' => $newStatus]);

        // Business Rule: Once Delivered, charge is automatically posted to Guest Folio
        if ($newStatus === 'Delivered' && $oldStatus !== 'Delivered') {
            $folio = $order->reservation->folio;
            if ($folio) {
                GuestFolioItem::create([
                    'guest_folio_id' => $folio->id,
                    'item_type' => 'Food & Beverage',
                    'description' => "Food & Beverage Room Service (Order #FNB-{$order->id})",
                    'amount' => $order->total_amount,
                    'reference_id' => $order->id,
                ]);
            }
        }

        return back()->with('success', "Order #FNB-{$order->id} status updated to {$newStatus}.");
    }
}
