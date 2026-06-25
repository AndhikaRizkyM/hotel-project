<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Miscellaneous Folio Voucher - {{ $reservation->reservation_number }}</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 11pt;
      color: #000;
      margin: 20px;
      background-color: #fff;
    }
    .voucher-box {
      border: 1px solid #000;
      padding: 30px;
      max-width: 650px;
      margin: 0 auto;
      background: #fff;
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
      height: 50px;
      width: auto;
    }
    .header .hotel-title {
      font-size: 18pt;
      font-weight: bold;
      letter-spacing: 2px;
    }
    .form-title {
      text-align: center;
      font-size: 14pt;
      font-weight: bold;
      text-transform: uppercase;
      margin-bottom: 25px;
      letter-spacing: 1px;
    }
    .info-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
      font-size: 10.5pt;
    }
    .info-group {
      display: flex;
      align-items: flex-end;
    }
    .info-group strong {
      margin-right: 8px;
    }
    .info-group .value {
      border-bottom: 1px solid #000;
      min-width: 150px;
      padding-bottom: 1px;
    }
    table.misc-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 30px;
    }
    table.misc-table th, table.misc-table td {
      border: 1px solid #000;
      padding: 8px 12px;
    }
    table.misc-table th {
      background-color: #f2f2f2;
      font-weight: bold;
      text-transform: uppercase;
      font-size: 9.5pt;
    }
    table.misc-table td.cell-desc {
      text-align: left;
      height: 24px; /* fixed height to ensure blank rows look clean */
    }
    table.misc-table td.cell-amount {
      text-align: right;
      width: 180px;
    }
    table.misc-table tr.total-row td {
      font-weight: bold;
      background-color: #f9f9f9;
    }
    table.misc-table tr.total-row td.cell-total-label {
      text-align: right;
      text-transform: uppercase;
      font-size: 10pt;
    }
    .signatures {
      display: flex;
      justify-content: space-between;
      margin-top: 50px;
      margin-bottom: 20px;
      font-size: 10.5pt;
    }
    .sig-col {
      width: 45%;
      text-align: center;
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
      .voucher-box {
        border: 1px solid #000;
        padding: 20px;
      }
    }
  </style>
</head>
<body>

  <button class="no-print-btn" onclick="window.print()">Print Receipt</button>

  <div class="voucher-box">
    <div class="header">
      <img src="{{ asset('logo_PPKDJP.png') }}" alt="Logo" class="logo">
      <div class="hotel-title">PPKD HOTEL</div>
    </div>

    <div class="form-title">MISCELLANEOUS FORM</div>

    <div class="info-row">
      <div class="info-group">
        <strong>Guest Name:</strong>
        <span class="value">{{ $reservation->guest->name }}</span>
      </div>
      <div class="info-group">
        <strong>Room No.</strong>
        <span class="value">{{ $reservation->room->room_number }}</span>
      </div>
    </div>

    @php
      $miscItems = [];
      $totalAmount = 0;
      if ($reservation->folio && $reservation->folio->items) {
          foreach ($reservation->folio->items as $item) {
              if (in_array($item->item_type, ['Miscellaneous Charge', 'Breakfast', 'Damage Charge'])) {
                  $miscItems[] = $item;
                  $totalAmount += $item->amount;
              }
          }
      }
      $totalRowsNeeded = 7;
      $itemsCount = count($miscItems);
    @endphp

    <table class="misc-table">
      <thead>
        <tr>
          <th>Description</th>
          <th style="text-align: right;">Amount</th>
        </tr>
      </thead>
      <tbody>
        @for ($i = 0; $i < max($totalRowsNeeded, $itemsCount); $i++)
          @if (isset($miscItems[$i]))
            <tr>
              <td class="cell-desc">{{ $miscItems[$i]->description }} ({{ $miscItems[$i]->item_type }})</td>
              <td class="cell-amount">Rp{{ number_format($miscItems[$i]->amount, 0, ',', '.') }}</td>
            </tr>
          @else
            <tr>
              <td class="cell-desc">&nbsp;</td>
              <td class="cell-amount">&nbsp;</td>
            </tr>
          @endif
        @endfor
        <tr class="total-row">
          <td class="cell-total-label">Total</td>
          <td class="cell-amount">Rp{{ number_format($totalAmount, 0, ',', '.') }}</td>
        </tr>
      </tbody>
    </table>

    <div class="signatures">
      <div class="sig-col">
        <p>Cashier: ___________________</p>
        <p style="margin-top: 5px;">( {{ auth()->user()->name }} )</p>
      </div>
      <div class="sig-col">
        <p>Guest: ___________________</p>
        <p style="margin-top: 5px;">( {{ $reservation->guest->name }} )</p>
      </div>
    </div>
  </div>

</body>
</html>
