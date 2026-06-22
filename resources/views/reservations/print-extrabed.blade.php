<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Extra Bed Requisition - {{ $reservation->reservation_number }}</title>
  <style>
    body {
      font-family: 'Courier New', Courier, monospace;
      font-size: 10pt;
      color: #000;
      margin: 20px;
    }
    .voucher-box {
      border: 2px dashed #000;
      padding: 15px;
      max-width: 650px;
      margin: 0 auto;
    }
    .header {
      text-align: center;
      border-bottom: 1px solid #000;
      padding-bottom: 10px;
      margin-bottom: 15px;
    }
    .header h2 {
      margin: 0;
      font-size: 14pt;
      text-transform: uppercase;
      font-weight: bold;
    }
    .header p {
      margin: 2px 0;
    }
    .details-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 8px;
    }
    .details-col {
      width: 48%;
    }
    .table-ledger {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
      margin-bottom: 15px;
    }
    .table-ledger th, .table-ledger td {
      border: 1px solid #000;
      padding: 6px;
      text-align: left;
    }
    .table-ledger th {
      background-color: #f2f2f2;
    }
    .signatures {
      display: flex;
      justify-content: space-between;
      margin-top: 30px;
      text-align: center;
    }
    .sig-line {
      border-bottom: 1px dashed #000;
      width: 150px;
      margin: 40px auto 5px auto;
    }
    .copies {
      margin-top: 25px;
      border-top: 1px solid #000;
      padding-top: 8px;
      font-size: 8pt;
      text-align: center;
      color: #444;
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
    }
    @media print {
      .no-print-btn {
        display: none !important;
      }
      body {
        margin: 0;
      }
      .voucher-box {
        border: 2px solid #000;
      }
    }
  </style>
</head>
<body>

  <button class="no-print-btn" onclick="window.print()">Print Voucher</button>

  <div class="voucher-box">
    <div class="header">
      <h2>PPKD HOTEL - EXTRA BED REQUISITION</h2>
      <p>Date Generated: {{ now()->format('d M Y H:i') }}</p>
    </div>

    <div class="details-row">
      <div class="details-col">
        <p><strong>Reservation No:</strong> {{ $reservation->reservation_number }}</p>
        <p><strong>Guest Name:</strong> {{ $reservation->guest->name }}</p>
      </div>
      <div class="details-col" style="text-align: right;">
        <p><strong>Room Number:</strong> Room {{ $reservation->room->room_number }}</p>
        <p><strong>Period of Stay:</strong> {{ $reservation->check_in_date }} - {{ $reservation->check_out_date }}</p>
      </div>
    </div>

    <table class="table-ledger">
      <thead>
        <tr>
          <th>Description</th>
          <th style="text-align: center;">Qty</th>
          <th style="text-align: center;">Nights</th>
          <th style="text-align: right;">Unit Price</th>
          <th style="text-align: right;">Total Price</th>
        </tr>
      </thead>
      <tbody>
        @if($extraBed)
          <tr>
            <td>Extra Bed Requisition (Folding Bed Set)</td>
            <td style="text-align: center;">{{ $extraBed->qty }}</td>
            <td style="text-align: center;">{{ $extraBed->num_nights }}</td>
            <td style="text-align: right;">Rp{{ number_format($extraBed->price_per_night, 0, ',', '.') }}</td>
            <td style="text-align: right; font-weight: bold;">Rp{{ number_format($extraBed->total_price, 0, ',', '.') }}</td>
          </tr>
        @else
          <tr>
            <td colspan="5" style="text-align: center; color: #555;">No active extra bed request found for this booking.</td>
          </tr>
        @endif
      </tbody>
    </table>

    <p style="font-size: 8.5pt;">*Note: This charge has been automatically posted to guest folio statement.*</p>

    <div class="signatures">
      <div>
        <p>Guest Signature</p>
        <div class="sig-line"></div>
        <p>{{ $reservation->guest->name }}</p>
      </div>
      <div>
        <p>Housekeeping Officer</p>
        <div class="sig-line"></div>
        <p>( Installation Team )</p>
      </div>
      <div>
        <p>Front Office Cashier</p>
        <div class="sig-line"></div>
        <p>{{ auth()->user()->name }}</p>
      </div>
    </div>

    <div class="copies">
      Copy Designation: WHITE - Front Office Folio | PINK - Housekeeping | YELLOW - Guest Copy
    </div>
  </div>

</body>
</html>
