<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Extra Bed Requisition - {{ $reservation->reservation_number }}</title>
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
      margin-bottom: 30px;
      letter-spacing: 1px;
    }
    .form-group {
      display: flex;
      margin-bottom: 18px;
      align-items: flex-end;
      line-height: 1.2;
    }
    .form-group .label {
      width: 180px;
      font-weight: bold;
      font-size: 10pt;
      text-transform: uppercase;
    }
    .form-group .value {
      flex-grow: 1;
      border-bottom: 1px dotted #000;
      padding-bottom: 2px;
      font-size: 11pt;
      padding-left: 10px;
    }
    .signatures {
      display: flex;
      justify-content: space-between;
      margin-top: 50px;
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
      font-size: 10pt;
      text-transform: uppercase;
    }
    .copies {
      margin-top: 30px;
      border-top: 1px solid #000;
      padding-top: 10px;
      font-size: 8pt;
      text-align: center;
      font-style: italic;
      color: #555;
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

  <button class="no-print-btn" onclick="window.print()">Print Voucher</button>

  <div class="voucher-box">
    <div class="header">
      <img src="{{ asset('logo_PPKDJP.png') }}" alt="Logo" class="logo">
      <div class="hotel-title">PPKD HOTEL</div>
    </div>

    <div class="form-title">EXTRABED REQUISITION FORM</div>

    <div class="form-group">
      <span class="label">Date:</span>
      <span class="value">{{ $extraBed ? \Carbon\Carbon::parse($extraBed->request_date)->format('d M Y') : now()->format('d M Y') }}</span>
    </div>

    <div class="form-group">
      <span class="label">Time:</span>
      <span class="value">{{ $extraBed ? \Carbon\Carbon::parse($extraBed->request_date)->format('H:i') : now()->format('H:i') }}</span>
    </div>

    <div class="form-group">
      <span class="label">Guest Name:</span>
      <span class="value">{{ $reservation->guest->name }}</span>
    </div>

    <div class="form-group">
      <span class="label">Room Number:</span>
      <span class="value">{{ $reservation->room->room_number }}</span>
    </div>

    <div class="form-group">
      <span class="label">Check In Date:</span>
      <span class="value">{{ \Carbon\Carbon::parse($reservation->check_in_date)->format('d M Y') }}</span>
    </div>

    <div class="form-group">
      <span class="label">Check Out Date:</span>
      <span class="value">{{ \Carbon\Carbon::parse($reservation->check_out_date)->format('d M Y') }}</span>
    </div>

    <div class="form-group">
      <span class="label">Length to set up:</span>
      <span class="value">{{ $extraBed->num_nights ?? \Carbon\Carbon::parse($reservation->check_in_date)->diffInDays(\Carbon\Carbon::parse($reservation->check_out_date)) }} Night(s)</span>
    </div>

    <div class="form-group">
      <span class="label">Number of Items:</span>
      <span class="value">{{ $extraBed->qty ?? 0 }}</span>
    </div>

    <div class="form-group">
      <span class="label">Price:</span>
      <span class="value">Rp{{ number_format($extraBed->total_price ?? 0, 0, ',', '.') }}</span>
    </div>

    <div class="signatures">
      <div class="sig-col">
        <p class="sig-title">Front Office</p>
        <div class="sig-line"></div>
        <p class="sig-name">NAME: {{ auth()->user()->name }}</p>
      </div>
      <div class="sig-col">
        <p class="sig-title">Guest</p>
        <div class="sig-line"></div>
        <p class="sig-name">NAME: {{ $reservation->guest->name }}</p>
      </div>
    </div>

    <div class="copies">
      White for Guest | Red For FO | Yellow for Housekeeping
    </div>
  </div>

</body>
</html>
