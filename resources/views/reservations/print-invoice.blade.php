<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Guest Invoice - {{ $reservation->reservation_number }}</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 10pt;
      color: #000;
      margin: 20px;
      background-color: #fff;
      line-height: 1.4;
    }
    .invoice-container {
      max-width: 800px;
      margin: 0 auto;
      border: 1px solid #000;
      padding: 30px;
      background-color: #fff;
    }
    .header {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 25px;
      gap: 15px;
      border-bottom: 2px solid #000;
      padding-bottom: 15px;
    }
    .header .logo {
      height: 55px;
      width: auto;
    }
    .header .hotel-title {
      font-size: 18pt;
      font-weight: bold;
      letter-spacing: 2px;
    }
    .invoice-title {
      text-align: center;
      font-size: 14pt;
      font-weight: bold;
      text-transform: uppercase;
      margin-bottom: 25px;
      letter-spacing: 1px;
    }
    .details-grid {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
      border: 1px solid #000;
      padding: 15px;
      background-color: #fafafa;
    }
    .details-col {
      width: 48%;
    }
    .details-row {
      display: flex;
      margin-bottom: 6px;
      font-size: 9pt;
    }
    .details-label {
      width: 120px;
      font-weight: bold;
    }
    .details-val {
      flex-grow: 1;
    }
    .section-title {
      font-size: 10pt;
      font-weight: bold;
      text-transform: uppercase;
      margin-top: 25px;
      margin-bottom: 10px;
      border-bottom: 1px solid #000;
      padding-bottom: 4px;
      letter-spacing: 0.5px;
    }
    table.data-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    table.data-table th, table.data-table td {
      border: 1px solid #000;
      padding: 6px 10px;
      font-size: 8.5pt;
    }
    table.data-table th {
      background-color: #f2f2f2;
      font-weight: bold;
      text-align: left;
    }
    .summary-box {
      width: 320px;
      margin-left: auto;
      margin-top: 15px;
      border: 1px solid #000;
      padding: 10px;
      background-color: #fafafa;
    }
    .summary-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 5px;
      font-size: 9pt;
    }
    .summary-row.total {
      font-weight: bold;
      border-top: 1px solid #000;
      padding-top: 5px;
      margin-top: 5px;
    }
    .signatures {
      display: flex;
      justify-content: space-between;
      margin-top: 40px;
      margin-bottom: 20px;
    }
    .sig-col {
      width: 45%;
      text-align: center;
    }
    .sig-title {
      font-weight: bold;
      margin-bottom: 0;
    }
    .sig-line {
      margin: 60px auto 5px auto;
      width: 80%;
      border-bottom: 1px solid #000;
    }
    .sig-name {
      font-size: 9.5pt;
    }
    .footer-note {
      text-align: center;
      margin-top: 30px;
      font-size: 8pt;
      font-style: italic;
      color: #555;
      border-top: 1px solid #000;
      padding-top: 10px;
    }
    .no-print-btn {
      display: block;
      margin: 20px auto;
      padding: 8px 16px;
      background-color: #000;
      color: #fff;
      border: none;
      cursor: pointer;
      font-weight: bold;
      font-size: 10pt;
      border-radius: 4px;
    }
    @media print {
      .no-print-btn {
        display: none !important;
      }
      body {
        margin: 0;
      }
      .invoice-container {
        border: none;
        padding: 0;
      }
    }
  </style>
</head>
<body>

  <button class="no-print-btn" onclick="window.print()">Print Invoice</button>

  <div class="invoice-container">
    <div class="header">
      <img src="{{ asset('logo_PPKDJP.png') }}" alt="Logo" class="logo">
      <div class="hotel-title">PPKD HOTEL</div>
    </div>

    <div class="invoice-title">Guest Folio Invoice</div>

    <div class="details-grid">
      <div class="details-col">
        <div class="details-row">
          <span class="details-label">Guest Name</span>
          <span class="details-val">: {{ $reservation->guest->name }}</span>
        </div>
        <div class="details-row">
          <span class="details-label">NIK/Passport</span>
          <span class="details-val">: {{ $reservation->guest->id_number }}</span>
        </div>
        <div class="details-row">
          <span class="details-label">Address</span>
          <span class="details-val">: {{ $reservation->guest->address ?? '-' }}</span>
        </div>
        <div class="details-row">
          <span class="details-label">Country</span>
          <span class="details-val">: {{ $reservation->guest->country }}</span>
        </div>
      </div>
      <div class="details-col">
        <div class="details-row">
          <span class="details-label">Invoice No.</span>
          <span class="details-val">: INV-{{ $reservation->reservation_number }}</span>
        </div>
        <div class="details-row">
          <span class="details-label">Room No.</span>
          <span class="details-val">: <strong>Room {{ $reservation->room->room_number }}</strong> ({{ $reservation->room->roomType->name }})</span>
        </div>
        <div class="details-row">
          <span class="details-label">Check-in</span>
          <span class="details-val">: {{ \Carbon\Carbon::parse($reservation->check_in_date)->format('d M Y') }}</span>
        </div>
        <div class="details-row">
          <span class="details-label">Check-out</span>
          <span class="details-val">: {{ \Carbon\Carbon::parse($reservation->check_out_date)->format('d M Y') }}</span>
        </div>
        <div class="details-row">
          <span class="details-label">Billing Status</span>
          <span class="details-val">: <strong>{{ $reservation->status === 'CO' ? 'Checked Out / Paid' : 'Checked In / Active' }}</strong></span>
        </div>
      </div>
    </div>

    <div class="section-title">Transaction Ledger Statement</div>
    <table class="data-table">
      <thead>
        <tr>
          <th style="width: 15%;">Date</th>
          <th style="width: 25%;">Category</th>
          <th>Description</th>
          <th style="text-align: right; width: 20%;">Amount</th>
        </tr>
      </thead>
      <tbody>
        @php $totalCharges = 0; @endphp
        @if($reservation->folio && $reservation->folio->items)
          @foreach($reservation->folio->items as $item)
            @php $totalCharges += $item->amount; @endphp
            <tr>
              <td>{{ $item->created_at->format('d M Y') }}</td>
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

    <div class="section-title">Payment & Deposit Records</div>
    <table class="data-table">
      <thead>
        <tr>
          <th style="width: 15%;">Date</th>
          <th style="width: 25%;">Transaction Type</th>
          <th>Payment Method / Notes</th>
          <th style="text-align: right; width: 20%;">Amount Paid</th>
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
            <td>{{ \Carbon\Carbon::parse($tx->transaction_date)->format('d M Y') }}</td>
            <td>{{ ucfirst($tx->type) }}</td>
            <td>{{ $tx->payment_method }} - {{ $tx->notes ?? 'Checkout settlement' }}</td>
            <td style="text-align: right; font-weight: bold; color: {{ $tx->type === 'payment' ? '#1b5e20' : '#b71c1c' }}">
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
        <span style="color: #1b5e20; font-weight: bold;">Rp{{ number_format($netPayments, 0, ',', '.') }}</span>
      </div>
      <div class="summary-row total">
        <span>Outstanding Balance:</span>
        <span style="color: {{ $outstanding > 0.01 ? '#b71c1c' : '#1b5e20' }}; font-weight: bold;">
          @if($outstanding > 0.01)
            Rp{{ number_format($outstanding, 0, ',', '.') }}
          @else
            Rp0 (Fully Settled)
          @endif
        </span>
      </div>
    </div>

    <div class="signatures">
      <div class="sig-col">
        <p class="sig-title">Guest Signature</p>
        <div class="sig-line"></div>
        <p class="sig-name">{{ $reservation->guest->name }}</p>
      </div>
      <div class="sig-col">
        <p class="sig-title">Authorized Cashier</p>
        <div class="sig-line"></div>
        <p class="sig-name">{{ auth()->user()->name }}</p>
      </div>
    </div>

    <div class="footer-note">
      Thank you for staying at PPKD Hotel. Have a safe journey home!
    </div>
  </div>

</body>
</html>
