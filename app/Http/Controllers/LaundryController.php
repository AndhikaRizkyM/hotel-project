<?php

namespace App\Http\Controllers;

use App\Models\LaundryOrder;
use App\Models\LaundryDamageReport;
use App\Models\GuestFolioItem;
use Illuminate\Http\Request;

class LaundryController extends Controller
{
    // 1. Laundry Orders Dashboard
    public function index()
    {
        $activeOrders = LaundryOrder::with(['reservation.room', 'reservation.guest', 'service', 'items'])
            ->whereIn('status', ['Pending', 'Collected', 'Washing', 'Ready'])
            ->orderBy('order_date', 'asc')
            ->get();

        $completedOrders = LaundryOrder::with(['reservation.room', 'reservation.guest', 'service', 'items'])
            ->whereIn('status', ['Delivered', 'Cancelled'])
            ->orderBy('updated_at', 'desc')
            ->take(20)
            ->get();

        return view('laundry.index', compact('activeOrders', 'completedOrders'));
    }

    // 2. Update Laundry Status
    public function updateStatus(Request $request, $id)
    {
        $order = LaundryOrder::with('reservation.folio')->findOrFail($id);

        $request->validate([
            'status' => ['required', 'string', 'in:Pending,Collected,Washing,Ready,Delivered,Cancelled']
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
                    'item_type' => 'Laundry',
                    'description' => "Laundry Service (Order #LDR-{$order->id} - {$order->service->name})",
                    'amount' => $order->total_amount,
                    'reference_id' => $order->id,
                ]);
            }
        }

        return back()->with('success', "Laundry Order #LDR-{$order->id} status updated to {$newStatus}.");
    }

    // 3. Report Damage / Lost Item in Laundry
    public function reportDamage(Request $request, $id)
    {
        $order = LaundryOrder::with('reservation.folio')->findOrFail($id);

        $request->validate([
            'item_name' => ['required', 'string', 'max:255'],
            'issue_type' => ['required', 'string', 'in:damage,lost'],
            'description' => ['nullable', 'string'],
            'compensation_amount' => ['required', 'numeric', 'min:0'],
            'resolve_immediately' => ['nullable', 'boolean'],
        ]);

        $status = $request->has('resolve_immediately') && $request->resolve_immediately ? 'resolved' : 'pending';

        $report = LaundryDamageReport::create([
            'laundry_order_id' => $order->id,
            'item_name' => $request->item_name,
            'issue_type' => $request->issue_type,
            'description' => $request->description,
            'compensation_amount' => $request->compensation_amount,
            'status' => $status,
        ]);

        // If resolved and compensation is greater than zero, apply credit/refund to guest folio as a negative adjustment
        if ($status === 'resolved' && $request->compensation_amount > 0) {
            $folio = $order->reservation->folio;
            if ($folio) {
                GuestFolioItem::create([
                    'guest_folio_id' => $folio->id,
                    'item_type' => 'Miscellaneous Charge',
                    'description' => "Laundry Compensation (Credit) - " . ucfirst($request->issue_type) . " item: {$request->item_name}",
                    'amount' => -$request->compensation_amount, // Negative amount acts as a credit
                    'reference_id' => $report->id,
                ]);
            }
        }

        return back()->with('success', "Laundry damage/loss logged for Order #LDR-{$order->id}. Status: " . ucfirst($status));
    }
}
