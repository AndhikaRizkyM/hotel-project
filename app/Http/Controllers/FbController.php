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

        return view('fb.breakfast', compact('guests', 'date'));
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
