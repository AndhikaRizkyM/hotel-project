<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Miscellaneous Folio Voucher - {{ $reservation->reservation_number }}</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 10pt;
      color: #333;
      margin: 30px;
    }
    .voucher-container {
      border: 1px solid #ccc;
      padding: 20px;
      max-width: 600px;
      margin: 0 auto;
      border-radius: 6px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    .header-table {
      width: 100%;
      border-bottom: 2px solid #1e3a8a;
      padding-bottom: 10px;
      margin-bottom: 15px;
    }
    .header-table h3 {
      margin: 0;
      color: #1e3a8a;
      font-size: 15pt;
    }
    .voucher-title {
      font-size: 12pt;
      font-weight: bold;
      text-transform: uppercase;
      text-align: center;
      margin-bottom: 20px;
      letter-spacing: 0.5px;
      color: #333;
    }
    table.data-table {
      width: 100%;
      margin-bottom: 20px;
    }
    table.data-table td {
      padding: 6px 0;
    }
    table.data-table td.label {
      font-weight: bold;
      width: 30%;
    }
    .items-box {
      border-top: 1px solid #eee;
      border-bottom: 1px solid #eee;
      padding: 10px 0;
      margin-bottom: 20px;
    }
    .total-amount {
      font-size: 12pt;
      font-weight: bold;
      text-align: right;
      color: #1e3a8a;
    }
    .signatures {
      display: flex;
      justify-content: space-between;
      margin-top: 40px;
    }
    .sig-box {
      width: 45%;
      text-align: center;
    }
    .sig-line {
      border-bottom: 1px solid #666;
      margin-top: 50px;
      margin-bottom: 5px;
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
      .voucher-container {
        border: none;
        box-shadow: none;
        padding: 0;
      }
    }
  </style>
</head>
<body>

  <button class="no-print-btn" onclick="window.print()">Print Receipt</button>

  <div class="voucher-container">
    <table class="header-table">
      <tr>
        <td>
          <h3>PPKD HOTEL MANAGEMENT</h3>
          <p style="margin: 2px 0; font-size: 8.5pt; color: #666;">Guest Folio Miscellaneous Adjustment Receipt</p>
        </td>
        <td style="text-align: right; font-size: 9pt;">
          <strong>Date:</strong> {{ now()->format('d M Y H:i') }}
        </td>
      </tr>
    </table>

    <div class="voucher-title">Miscellaneous Folio Voucher</div>

    <table class="data-table">
      <tr>
        <td class="label">Reservation No.</td>
        <td>: {{ $reservation->reservation_number }}</td>
      </tr>
      <tr>
        <td class="label">Guest Name</td>
        <td>: {{ $reservation->guest->name }}</td>
      </tr>
      <tr>
        <td class="label">Room Number</td>
        <td>: Room {{ $reservation->room->room_number }}</td>
      </tr>
    </table>

    <div class="items-box">
      <h6 style="margin: 0 0 8px 0; font-size: 9.5pt; font-weight: bold; color: #666;">Ledger Transaction Record:</h6>
      <table style="width: 100%; font-size: 9.5pt;">
        <thead>
          <tr style="border-bottom: 1px solid #eee; font-weight: bold;">
            <td style="padding: 4px 0;">Item Description</td>
            <td style="text-align: right; padding: 4px 0;">Amount (IDR)</td>
          </tr>
        </thead>
        <tbody>
          @php $tot = 0; @endphp
          @if($reservation->folio)
            @forelse($reservation->folio->items as $item)
              @if($item->item_type === 'Miscellaneous Charge' || $item->item_type === 'Breakfast' || $item->item_type === 'Damage Charge')
                @php $tot += $item->amount; @endphp
                <tr>
                  <td style="padding: 5px 0;">{{ $item->description }} ({{ $item->item_type }})</td>
                  <td style="text-align: right; padding: 5px 0;">Rp{{ number_format($item->amount, 0, ',', '.') }}</td>
                </tr>
              @endif
            @empty
              <tr><td colspan="2" style="text-align: center; color: #888;">No miscellaneous items listed.</td></tr>
            @endforelse
          @endif
        </tbody>
      </table>
    </div>

    @if($tot > 0)
      <div class="total-amount">
        Total Adjustments: Rp{{ number_format($tot, 0, ',', '.') }}
      </div>
    @endif

    <div class="signatures">
      <div class="sig-box">
        <p>Guest Signature</p>
        <div class="sig-line"></div>
        <p>{{ $reservation->guest->name }}</p>
      </div>
      <div class="sig-box">
        <p>Folio Clerk Cashier</p>
        <div class="sig-line"></div>
        <p>{{ auth()->user()->name }}</p>
      </div>
    </div>
  </div>

</body>
</html>
