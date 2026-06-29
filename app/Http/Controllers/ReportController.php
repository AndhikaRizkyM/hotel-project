<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Reservation;
use App\Models\Deposit;
use App\Models\GuestFolioItem;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    // 1. Analytics Reports Index
    public function index(Request $request)
    {
        $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date)->startOfDay() : now()->subDays(30)->startOfDay();
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date)->endOfDay() : now()->endOfDay();

        // General Metrics
        $totalRooms = Room::where('is_active', true)->count();
        $occupiedRooms = Room::where('status', 'O')->count();
        $occupancyRate = $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0;

        $newBookingsCount = Reservation::whereBetween('created_at', [$startDate, $endDate])->count();
        $checkinsCount = Reservation::whereBetween('check_in_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->whereIn('status', ['CI', 'CO'])
            ->count();

        // Revenue Breakdown
        $revenueDetails = [
            'Room Charge' => (float) GuestFolioItem::whereBetween('created_at', [$startDate, $endDate])->where('item_type', 'Room Charge')->sum('amount'),
            'Breakfast' => (float) GuestFolioItem::whereBetween('created_at', [$startDate, $endDate])->where('item_type', 'Breakfast')->sum('amount'),
            'Food & Beverage' => (float) GuestFolioItem::whereBetween('created_at', [$startDate, $endDate])->where('item_type', 'Food & Beverage')->sum('amount'),
            'Extra Bed' => (float) GuestFolioItem::whereBetween('created_at', [$startDate, $endDate])->where('item_type', 'Extra Bed')->sum('amount'),
            'Laundry' => (float) GuestFolioItem::whereBetween('created_at', [$startDate, $endDate])->where('item_type', 'Laundry')->sum('amount'),
            'Damage Charge' => (float) GuestFolioItem::whereBetween('created_at', [$startDate, $endDate])->where('item_type', 'Damage Charge')->sum('amount'),
            'Lost Item Charge' => (float) GuestFolioItem::whereBetween('created_at', [$startDate, $endDate])->where('item_type', 'Lost Item Charge')->sum('amount'),
            'Miscellaneous' => (float) GuestFolioItem::whereBetween('created_at', [$startDate, $endDate])->where('item_type', 'Miscellaneous Charge')->sum('amount'),
        ];

        $totalRevenue = array_sum($revenueDetails);

        // Deposit Settlements (Actual cash flow)
        $cashInflow = (float) Deposit::whereBetween('transaction_date', [$startDate, $endDate])
            ->where('type', 'payment')
            ->sum('amount');
        $cashOutflow = (float) Deposit::whereBetween('transaction_date', [$startDate, $endDate])
            ->where('type', 'refund')
            ->sum('amount');
        $netCashFlow = $cashInflow - $cashOutflow;

        // Recent transactions list
        $recentTransactions = Deposit::with(['reservation.guest', 'reservation.room'])
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->orderBy('transaction_date', 'desc')
            ->get();

        // Active guest list
        $activeReservations = Reservation::with(['guest', 'room.roomType'])
            ->where('status', 'CI')
            ->get();

        return view('reports.index', compact(
            'startDate', 'endDate', 
            'totalRooms', 'occupiedRooms', 'occupancyRate',
            'newBookingsCount', 'checkinsCount',
            'revenueDetails', 'totalRevenue',
            'cashInflow', 'cashOutflow', 'netCashFlow',
            'recentTransactions', 'activeReservations'
        ));
    }

    // 2. Export reports to simulated Excel (HTML/TSV format)
    public function exportExcel(Request $request)
    {
        $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date)->startOfDay() : now()->subDays(30)->startOfDay();
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date)->endOfDay() : now()->endOfDay();

        $fileName = 'HMS-Report-' . $startDate->format('Ymd') . '-to-' . $endDate->format('Ymd') . '.xls';

        // Fetch Data
        $revenueDetails = [
            'Room Charge' => (float) GuestFolioItem::whereBetween('created_at', [$startDate, $endDate])->where('item_type', 'Room Charge')->sum('amount'),
            'Breakfast' => (float) GuestFolioItem::whereBetween('created_at', [$startDate, $endDate])->where('item_type', 'Breakfast')->sum('amount'),
            'Food & Beverage' => (float) GuestFolioItem::whereBetween('created_at', [$startDate, $endDate])->where('item_type', 'Food & Beverage')->sum('amount'),
            'Extra Bed' => (float) GuestFolioItem::whereBetween('created_at', [$startDate, $endDate])->where('item_type', 'Extra Bed')->sum('amount'),
            'Laundry' => (float) GuestFolioItem::whereBetween('created_at', [$startDate, $endDate])->where('item_type', 'Laundry')->sum('amount'),
            'Damage Charge' => (float) GuestFolioItem::whereBetween('created_at', [$startDate, $endDate])->where('item_type', 'Damage Charge')->sum('amount'),
            'Lost Item Charge' => (float) GuestFolioItem::whereBetween('created_at', [$startDate, $endDate])->where('item_type', 'Lost Item Charge')->sum('amount'),
            'Miscellaneous' => (float) GuestFolioItem::whereBetween('created_at', [$startDate, $endDate])->where('item_type', 'Miscellaneous Charge')->sum('amount'),
        ];
        $totalRevenue = array_sum($revenueDetails);

        $transactions = Deposit::with(['reservation.guest', 'reservation.room'])
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->orderBy('transaction_date', 'asc')
            ->get();

        // Output TSV headers
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo "PPKD HOTEL MANAGEMENT SYSTEM ANALYTICS REPORT\n";
        echo "Period:\t" . $startDate->format('Y-m-d') . " to " . $endDate->format('Y-m-d') . "\n\n";

        // 1. Revenue Breakdown Table
        echo "REVENUE BREAKDOWN BY SERVICE CATEGORY\n";
        echo "Category\tRevenue Amount (IDR)\n";
        foreach ($revenueDetails as $category => $amount) {
            echo "{$category}\t" . number_format($amount, 2, '.', '') . "\n";
        }
        echo "TOTAL REVENUE\t" . number_format($totalRevenue, 2, '.', '') . "\n\n";

        // 2. Transaction List Table
        echo "TRANSACTION LEDGER (DEPOSITS & CHECKOUT SETTLEMENTS)\n";
        echo "Date & Time\tReservation No\tGuest Name\tRoom No\tPayment Method\tType\tAmount (IDR)\tNotes\n";
        foreach ($transactions as $tx) {
            $date = Carbon::parse($tx->transaction_date)->format('Y-m-d H:i');
            $resNo = $tx->reservation->reservation_number ?? 'N/A';
            $guestName = $tx->reservation->guest->name ?? 'N/A';
            $roomNo = $tx->reservation->room->room_number ?? 'N/A';
            $method = $tx->payment_method;
            $type = ucfirst($tx->type);
            $amount = number_format($tx->amount, 2, '.', '');
            $notes = str_replace(["\r", "\n", "\t"], ' ', $tx->notes ?? '');
            
            echo "{$date}\t{$resNo}\t{$guestName}\t{$roomNo}\t{$method}\t{$type}\t{$amount}\t{$notes}\n";
        }
        exit;
    }
}
