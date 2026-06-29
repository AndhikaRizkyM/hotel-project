<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Deposit;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function store(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'payment_method' => ['required', 'string', 'in:Cash,Debit Card,Credit Card,Transfer,QRIS'],
            'notes' => ['nullable', 'string'],
        ]);

        Deposit::create([
            'reservation_id' => $reservation->id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'type' => 'payment',
            'transaction_date' => now(),
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Deposit payment recorded successfully.');
    }

    public function refund(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'notes' => ['nullable', 'string'],
        ]);

        $totalPaid = (float) $reservation->deposits()->where('type', 'payment')->sum('amount') 
                     - (float) $reservation->deposits()->where('type', 'refund')->sum('amount');

        if ($request->amount > $totalPaid) {
            return back()->with('error', 'Refund amount cannot exceed the total deposit paid (Max refund: Rp' . number_format($totalPaid, 0, ',', '.') . ').');
        }

        Deposit::create([
            'reservation_id' => $reservation->id,
            'amount' => $request->amount,
            'payment_method' => 'Cash', // Default cash refund
            'type' => 'refund',
            'transaction_date' => now(),
            'notes' => $request->notes ?? 'Deposit refund transaction',
        ]);

        return back()->with('success', 'Deposit refund recorded successfully.');
    }
}
