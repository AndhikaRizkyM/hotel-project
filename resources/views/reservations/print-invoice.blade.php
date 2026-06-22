<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Guest Invoice - {{ $reservation->reservation_number }}</title>
  <style>
    body {
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
      font-size: 10pt;
      line-height: 1.4;
      color: #333;
      margin: 30px;
    }
    .invoice-container {
      max-width: 800px;
      margin: 0 auto;
    }
    .header {
      display: flex;
      justify-content: space-between;
      border-bottom: 2px solid #1e3a8a;
      padding-bottom: 15px;
      margin-bottom: 25px;
    }
    .hotel-logo h1 {
      margin: 0;
      font-size: 20pt;
      color: #1e3a8a;
      font-weight: bold;
    }
    .hotel-logo p {
      margin: 2px 0;
      font-size: 8.5pt;
      color: #666;
    }
    .invoice-title {
      text-align: right;
    }
    .invoice-title h2 {
      margin: 0;
      font-size: 16pt;
      color: #333;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .invoice-title p {
      margin: 2px 0;
      font-weight: bold;
      font-size: 9.5pt;
    }
    .details-box {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
      background-color: #f8fafc;
      padding: 12px;
      border-radius: 4px;
      border: 1px solid #e2e8f0;
    }
    .details-col {
      width: 48%;
    }
    .details-col table {
      width: 100%;
    }
    .details-col td {
      padding: 3px 0;
      vertical-align: top;
    }
    .details-col td.label {
      font-weight: bold;
      color: #64748b;
      width: 40%;
    }
    .ledger-title {
      font-size: 11pt;
      font-weight: bold;
      margin-top: 25px;
      margin-bottom: 10px;
      color: #1e3a8a;
      border-bottom: 1px solid #e2e8f0;
      padding-bottom: 5px;
    }
    table.ledger-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    table.ledger-table th, table.ledger-table td {
      padding: 8px 6px;
      border-bottom: 1px solid #e2e8f0;
    }
    table.ledger-table th {
      background-color: #f1f5f9;
      font-weight: bold;
      color: #334155;
      text-align: left;
    }
    .summary-box {
      width: 45%;
      margin-left: auto;
      margin-top: 15px;
      border-top: 2px solid #1e3a8a;
      padding-top: 10px;
    }
    .summary-row {
      display: flex;
      justify-content: space-between;
      padding: 4px 0;
    }
    .summary-row.total {
      font-size: 11pt;
      font-weight: bold;
      border-top: 1px solid #e2e8f0;
      padding-top: 6px;
      margin-top: 6px;
      color: #1e3a8a;
    }
    .signatures {
      display: flex;
      justify-content: space-between;
      margin-top: 50px;
    }
    .sig-box {
      width: 40%;
      text-align: center;
    }
    .sig-line {
      border-bottom: 1px solid #333;
      margin-top: 55px;
      margin-bottom: 5px;
    }
    .footer-note {
      text-align: center;
      margin-top: 50px;
      font-size: 8pt;
      color: #94a3b8;
      border-top: 1px solid #e2e8f0;
      padding-top: 10px;
    }
    .no-print-btn {
      display: block;
      margin: 20px auto;
      padding: 8px 16px;
      background-color: #1e3a8a;
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-weight: bold;
    }
    @media print {
      .no-print-btn {
        display: none !important;
      }
      body {
        margin: 0;
      }
    }
  </style>
</head>
<body>

  <button class="no-print-btn" onclick="window.print()">Print Invoice</button>

  <div class="invoice-container">
    <div class="header">
      <div class="hotel-logo">
        <h1>PPKD HOTEL & RESORT</h1>
        <p>Jl. Raya Jakarta No. 12, DKI Jakarta</p>
        <p>Telp: (021) 123456 | Email: billing@ppkdhotel.com</p>
      </div>
      <div class="invoice-title">
        <h2>Guest Folio Invoice</h2>
        <p>Invoice No: INV-{{ $reservation->reservation_number }}</p>
        <p style="font-weight: normal; font-size: 8.5pt; color: #666;">Date: {{ now()->format('d M Y H:i') }}</p>
      </div>
    </div>

    <div class="details-box">
      <div class="details-col">
        <table>
          <tr><td class="label">Guest Name</td><td>: {{ $reservation->guest->name }}</td></tr>
          <tr><td class="label">NIK/Passport</td><td>: {{ $reservation->guest->id_number }}</td></tr>
          <tr><td class="label">Address</td><td>: {{ $reservation->guest->address ?? '-' }}</td></tr>
          <tr><td class="label">Country</td><td>: {{ $reservation->guest->country }}</td></tr>
        </table>
      </div>
      <div class="details-col">
        <table>
          <tr><td class="label">Room No.</td><td>: <strong>Room {{ $reservation->room->room_number }}</strong> ({{ $reservation->room->roomType->name }})</td></tr>
          <tr><td class="label">Check-in</td><td>: {{ \Carbon\Carbon::parse($reservation->check_in_date)->format('d M Y') }}</td></tr>
          <tr><td class="label">Check-out</td><td>: {{ \Carbon\Carbon::parse($reservation->check_out_date)->format('d M Y') }}</td></tr>
          <tr><td class="label">Billing Status</td><td>: <strong>{{ $reservation->status === 'CO' ? 'Checked Out / Paid' : 'Checked In / Active' }}</strong></td></tr>
        </table>
      </div>
    </div>

    <div class="ledger-title">Transaction Ledger Statement</div>
    <table class="ledger-table">
      <thead>
        <tr>
          <th style="width: 15%;">Date</th>
          <th style="width: 20%;">Category</th>
          <th>Description</th>
          <th style="text-align: right; width: 20%;">Amount (IDR)</th>
        </tr>
      </thead>
      <tbody>
        @php $totalCharges = 0; @endphp
        @if($reservation->folio && $reservation->folio->items)
          @foreach($reservation->folio->items as $item)
            @php $totalCharges += $item->amount; @endphp
            <tr>
              <td>{{ $item->created_at->format('d/m/Y') }}</td>
              <td>{{ $item->item_type }}</td>
              <td>{{ $item->description }}</td>
              <td style="text-align: right;">Rp{{ number_format($item->amount, 0, ',', '.') }}</td>
            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="4" style="text-align: center; color: #666;">No ledger items loaded.</td>
          </tr>
        @endif
      </tbody>
    </table>

    <div class="ledger-title">Payment & Deposit Records</div>
    <table class="ledger-table">
      <thead>
        <tr>
          <th style="width: 15%;">Date</th>
          <th style="width: 25%;">Transaction Type</th>
          <th>Payment Method / Details</th>
          <th style="text-align: right; width: 20%;">Amount Paid (IDR)</th>
        </tr>
      </thead>
      <tbody>
        @php
          $totPay = 0;
          $totRef = 0;
        @endphp
        @forelse($reservation->deposits as $tx)
          @php
            if ($tx->type === 'payment') $totPay += $tx->amount;
            else $totRef += $tx->amount;
          @endphp
          <tr>
            <td>{{ \Carbon\Carbon::parse($tx->transaction_date)->format('d/m/Y') }}</td>
            <td>{{ ucfirst($tx->type) }}</td>
            <td>{{ $tx->payment_method }} - {{ $tx->notes ?? 'Checkout payment' }}</td>
            <td style="text-align: right; font-weight: bold; color: {{ $tx->type === 'payment' ? 'green' : 'red' }}">
              {{ $tx->type === 'payment' ? '' : '-' }}Rp{{ number_format($tx->amount, 0, ',', '.') }}
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" style="text-align: center; color: #666;">No payments recorded.</td>
          </tr>
        @endforelse
      </tbody>
    </table>

    @php
      $netPayments = $totPay - $totRef;
      $outstanding = $totalCharges - $netPayments;
    @endphp

    <div class="summary-box">
      <div class="summary-row">
        <span>Total Charges:</span>
        <span>Rp{{ number_format($totalCharges, 0, ',', '.') }}</span>
      </div>
      <div class="summary-row">
        <span>Total Net Paid:</span>
        <span style="color: green;">Rp{{ number_format($netPayments, 0, ',', '.') }}</span>
      </div>
      <div class="summary-row total">
        <span>Outstanding Balance:</span>
        <span style="color: {{ $outstanding > 0.01 ? 'red' : 'green' }};">
          @if($outstanding > 0.01)
            Rp{{ number_format($outstanding, 0, ',', '.') }}
          @else
            Rp0 (Fully Settled)
          @endif
        </span>
      </div>
    </div>

    <div class="signatures">
      <div class="sig-box">
        <p>Guest Signature</p>
        <div class="sig-line"></div>
        <p>{{ $reservation->guest->name }}</p>
      </div>
      <div class="sig-box">
        <p>Authorized Cashier</p>
        <div class="sig-line"></div>
        <p>{{ auth()->user()->name }}</p>
      </div>
    </div>

    <div class="footer-note">
      Thank you for staying at PPKD Hotel & Resort. Have a safe journey home!
    </div>
  </div>

</body>
</html>
