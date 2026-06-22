<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Card - {{ $reservation->reservation_number }}</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 11pt;
      line-height: 1.4;
      color: #333;
      margin: 20px;
    }
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 2px solid #000;
      padding-bottom: 10px;
      margin-bottom: 20px;
    }
    .hotel-info h1 {
      margin: 0;
      font-size: 18pt;
      font-weight: bold;
      color: #1e3a8a;
    }
    .hotel-info p {
      margin: 2px 0;
      font-size: 9pt;
      color: #666;
    }
    .doc-title {
      text-align: right;
    }
    .doc-title h2 {
      margin: 0;
      font-size: 14pt;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    .doc-title p {
      margin: 2px 0;
      font-weight: bold;
      color: #d97706;
    }
    .section-title {
      font-size: 10pt;
      font-weight: bold;
      text-transform: uppercase;
      background-color: #f3f4f6;
      padding: 4px 8px;
      margin-top: 15px;
      margin-bottom: 8px;
      border-left: 3px solid #1e3a8a;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 15px;
    }
    table.details-table td {
      padding: 6px 4px;
      vertical-align: top;
    }
    table.details-table td.label {
      font-weight: bold;
      width: 25%;
    }
    .rules {
      font-size: 8pt;
      color: #555;
      margin-top: 20px;
      border: 1px solid #ccc;
      padding: 10px;
      border-radius: 4px;
      background-color: #fafafa;
    }
    .rules h4 {
      margin-top: 0;
      margin-bottom: 5px;
      color: #000;
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
      border-bottom: 1px solid #000;
      margin-top: 60px;
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
    }
  </style>
</head>
<body>

  <button class="no-print-btn" onclick="window.print()">Print Document</button>

  <div class="header">
    <div class="hotel-info">
      <h1>PPKD HOTEL & RESORT</h1>
      <p>Jl. Raya Jakarta No. 12, DKI Jakarta</p>
      <p>Telp: (021) 123456 | Email: info@ppkdhotel.com</p>
    </div>
    <div class="doc-title">
      <h2>Registration Card</h2>
      <p>No: {{ $reservation->reservation_number }}</p>
    </div>
  </div>

  <div class="section-title">Guest Profile</div>
  <table class="details-table">
    <tr>
      <td class="label">Guest Name</td>
      <td>: {{ $reservation->guest->name }}</td>
      <td class="label">NIK/Passport No</td>
      <td>: {{ $reservation->guest->id_number }}</td>
    </tr>
    <tr>
      <td class="label">Nationality</td>
      <td>: {{ $reservation->guest->country }}</td>
      <td class="label">Date of Birth</td>
      <td>: {{ \Carbon\Carbon::parse($reservation->guest->birth_date)->format('d M Y') }}</td>
    </tr>
    <tr>
      <td class="label">Phone Number</td>
      <td>: {{ $reservation->guest->phone }}</td>
      <td class="label">Email Address</td>
      <td>: {{ $reservation->guest->email ?? '-' }}</td>
    </tr>
    <tr>
      <td class="label">Address</td>
      <td colspan="3">: {{ $reservation->guest->address ?? '-' }}</td>
    </tr>
  </table>

  <div class="section-title">Room & Stay Details</div>
  <table class="details-table">
    <tr>
      <td class="label">Room Number</td>
      <td>: <strong>Room {{ $reservation->room->room_number }}</strong> ({{ $reservation->room->roomType->name }})</td>
      <td class="label">Room Rate</td>
      <td>: Rp{{ number_format($reservation->room_charge_per_night, 0, ',', '.') }} / Night</td>
    </tr>
    <tr>
      <td class="label">Check-in Date</td>
      <td>: {{ \Carbon\Carbon::parse($reservation->check_in_date)->format('d M Y') }} (14:00)</td>
      <td class="label">Check-out Date</td>
      <td>: {{ \Carbon\Carbon::parse($reservation->check_out_date)->format('d M Y') }} (12:00)</td>
    </tr>
    <tr>
      <td class="label">Vehicle No</td>
      <td>: {{ $reservation->guest->vehicle_no ?? '-' }}</td>
      <td class="label">Total Est. Stay</td>
      <td>: {{ \Carbon\Carbon::parse($reservation->check_in_date)->diffInDays(\Carbon\Carbon::parse($reservation->check_out_date)) }} Night(s)</td>
    </tr>
  </table>

  <div class="rules">
    <h4>Hotel Regulations & Policies:</h4>
    <ol style="margin: 0; padding-left: 15px;">
      <li>Guests are required to present valid identification upon check-in.</li>
      <li>Check-out time is strictly 12:00 PM. Late check-out may be subject to additional fees.</li>
      <li>Strictly <strong>NO SMOKING</strong> inside rooms. Violation will result in a cleaning fee of Rp1.500.000.</li>
      <li>Strictly <strong>NO DURIANS</strong> or other strong-smelling foods allowed on premises.</li>
      <li>The hotel is not responsible for any loss of valuable goods left in the room. Please utilize the Safe Deposit Box.</li>
    </ol>
  </div>

  <div class="signatures">
    <div class="sig-box">
      <p>Guest Signature</p>
      <div class="sig-line"></div>
      <p>{{ $reservation->guest->name }}</p>
    </div>
    <div class="sig-box">
      <p>Receiving Agent</p>
      <div class="sig-line"></div>
      <p>{{ auth()->user()->name }}</p>
    </div>
  </div>

</body>
</html>
