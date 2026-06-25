<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Card - {{ $reservation->reservation_number }}</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 9.5pt;
      color: #000;
      margin: 15px;
      background-color: #fff;
      line-height: 1.3;
    }
    .container {
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
      border: 1px solid #000;
      background-color: #fff;
    }
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 2px double #000;
      padding-bottom: 10px;
      margin-bottom: 15px;
    }
    .logo-area {
      width: 25%;
    }
    .logo-area img {
      height: 55px;
      width: auto;
    }
    .title-area {
      width: 75%;
      text-align: right;
    }
    .title-area h1 {
      margin: 0;
      font-size: 16pt;
      font-weight: bold;
    }
    .title-area .subtitle {
      font-size: 10pt;
      font-weight: bold;
      margin-top: 3px;
    }
    table.reg-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    table.reg-table td {
      border: 1px solid #000;
      padding: 6px 8px;
      vertical-align: top;
      font-size: 8.5pt;
    }
    .label-ind {
      font-size: 8pt;
      font-weight: bold;
    }
    .label-eng {
      font-size: 7.5pt;
      font-style: italic;
      color: #555;
    }
    .cell-value {
      font-size: 9.5pt;
      font-weight: bold;
      margin-top: 4px;
    }
    .terms-text {
      font-size: 7.5pt;
      line-height: 1.3;
      text-align: justify;
    }
    .signatures-bottom {
      display: flex;
      justify-content: space-between;
      margin-top: 40px;
      padding: 0 40px;
      font-size: 9.5pt;
    }
    .sig-block {
      width: 220px;
      text-align: center;
    }
    .sig-line-bottom {
      margin-top: 60px;
      border-bottom: 1px solid #000;
      margin-bottom: 5px;
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
      .container {
        border: none;
        padding: 0;
      }
    }
  </style>
</head>
<body>

  <button class="no-print-btn" onclick="window.print()">Print Registration Card</button>

  <div class="container">
    <div class="header">
      <div class="logo-area">
        <img src="{{ asset('logo_PPKDJP.png') }}" alt="Logo" class="logo">
      </div>
      <div class="title-area">
        <h1>PPKD HOTEL</h1>
        <div class="subtitle">Formulir Pendaftaran / Registration Form</div>
      </div>
    </div>

    <table class="reg-table">
      <tbody>
        <!-- Row 1 -->
        <tr>
          <td rowspan="2" style="width: 20%;">
            <div class="label-ind">No. Kamar</div>
            <div class="label-eng">Room No.</div>
            <div class="cell-value">{{ $reservation->room->room_number }}</div>
          </td>
          <td style="width: 20%;">
            <div class="label-ind">Jumlah Tamu</div>
            <div class="label-eng">No. of Person</div>
            <div class="cell-value">1</div>
          </td>
          <td style="width: 20%;">&nbsp;</td>
          <td colspan="2" style="width: 40%;">&nbsp;</td>
        </tr>

        <!-- Row 2 -->
        <tr>
          <td>
            <div class="label-ind">Jumlah Kamar</div>
            <div class="label-eng">No. of Room</div>
            <div class="cell-value">1</div>
          </td>
          <td>
            <div class="label-ind">Jenis Kamar</div>
            <div class="label-eng">Room Type</div>
            <div class="cell-value">{{ $reservation->room->roomType->name }}</div>
          </td>
          <td colspan="2">&nbsp;</td>
        </tr>

        <!-- Row 3 -->
        <tr>
          <td colspan="5" style="text-align: center; font-weight: bold; background-color: #f5f5f5;">
            Waktu Lapor Keluar : Jam 12.00 Siang | Check Out Time : 12.00 Noon
          </td>
        </tr>

        <!-- Row 4 -->
        <tr>
          <td colspan="4" style="font-weight: bold; font-style: italic; background-color: #fafafa;">
            Harap tulis dengan huruf cetak — Please print in block letters
          </td>
          <td rowspan="3" style="width: 20%;">
            <div class="label-ind">Waktu Kedatangan</div>
            <div class="label-eng">Arrival Time</div>
            <div class="cell-value">{{ $reservation->created_at->format('H:i') }}</div>
          </td>
        </tr>

        <!-- Row 5 -->
        <tr>
          <td colspan="4">
            <div class="label-ind">Nama / <span class="label-eng">Name</span></div>
            <div class="cell-value">{{ $reservation->guest->name }}</div>
          </td>
        </tr>

        <!-- Row 6 -->
        <tr>
          <td colspan="4">
            <div class="label-ind">Pekerjaan / <span class="label-eng">Profession</span></div>
            <div class="cell-value">________________________________________________</div>
          </td>
        </tr>

        <!-- Row 7 -->
        <tr>
          <td colspan="4">
            <div class="label-ind">Perusahaan / <span class="label-eng">Company</span></div>
            <div class="cell-value">________________________________________________</div>
          </td>
          <td rowspan="2">
            <div class="label-ind">Tanggal Kedatangan</div>
            <div class="label-eng">Arrival Date</div>
            <div class="cell-value">{{ \Carbon\Carbon::parse($reservation->check_in_date)->format('d M Y') }}</div>
          </td>
        </tr>

        <!-- Row 8 -->
        <tr>
          <td colspan="4">
            <div style="display: flex; justify-content: space-between;">
              <div style="width: 33%;">
                <div class="label-ind">Kebangsaan / <span class="label-eng">Nationality</span></div>
                <div class="cell-value">{{ $reservation->guest->country }}</div>
              </div>
              <div style="width: 33%;">
                <div class="label-ind">No. KTP / <span class="label-eng">Passport No.</span></div>
                <div class="cell-value">{{ $reservation->guest->id_number }}</div>
              </div>
              <div style="width: 33%;">
                <div class="label-ind">Tanggal Lahir / <span class="label-eng">Birth Date</span></div>
                <div class="cell-value">{{ \Carbon\Carbon::parse($reservation->guest->birth_date)->format('d M Y') }}</div>
              </div>
            </div>
          </td>
        </tr>

        <!-- Row 9 & 10 -->
        <tr>
          <td colspan="4" rowspan="2">
            <div style="margin-bottom: 8px;">
              <div class="label-ind">Alamat / <span class="label-eng">Address</span></div>
              <div class="cell-value" style="font-weight: normal;">{{ $reservation->guest->address ?? '-' }}</div>
            </div>
            <div style="display: flex; justify-content: space-between;">
              <div style="width: 48%;">
                <div class="label-ind">Telephone / Phone / Mobile</div>
                <div class="cell-value">{{ $reservation->guest->phone }}</div>
              </div>
              <div style="width: 48%;">
                <div class="label-ind">Email</div>
                <div class="cell-value">{{ $reservation->guest->email ?? '-' }}</div>
              </div>
            </div>
          </td>
          <td rowspan="2">
            <div class="label-ind">Tgl Keberangkatan</div>
            <div class="label-eng">Departure Date</div>
            <div class="cell-value">{{ \Carbon\Carbon::parse($reservation->check_out_date)->format('d M Y') }}</div>
          </td>
        </tr>
        <tr>
          <!-- Skipped due to rowspan -->
        </tr>

        <!-- Row 11 -->
        <tr>
          <td colspan="4">
            <div style="display: flex; justify-content: space-between; align-items: center;">
              <div>
                <div class="label-ind">No. Member / <span class="label-eng">Member Card No.</span></div>
                <div class="cell-value">_________________________________</div>
              </div>
              <div>
                <div class="label-ind">Cara Pembayaran / <span class="label-eng">Method of Payment</span></div>
                <div style="margin-top: 5px; font-size: 8.5pt;">
                  <input type="checkbox" style="vertical-align: middle;"> VISA &nbsp;&nbsp;
                  <input type="checkbox" style="vertical-align: middle;"> Debit Card &nbsp;&nbsp;
                  <input type="checkbox" style="vertical-align: middle;"> Other: _________________
                </div>
              </div>
            </div>
          </td>
          <td>&nbsp;</td>
        </tr>

        <!-- Row 12 & 13 -->
        <tr>
          <td colspan="4" rowspan="2" style="padding: 10px;">
            <div class="terms-text">
              <strong>Kepada Park Hotel:</strong> Saya menyatakan bahwa saya baik sendiri ataupun bersama-sama dengan perusahaan, asosiasi, perorangan atau semuanya bertanggung jawab atas pembayaran semua tagihan yang terjadi sehubungan dengan seluruh pelayanan yang Anda berikan sesuai formulir pendaftaran ini. / <em>To Park Hotel: I acknowledge that I'm jointly and severally liable with the fore-going person, company or association (and if more than one all of them) for payment of the amount of any charges payable or incurred in connection with all services provided by you under registration.</em>
            </div>
            <hr style="border: 0; border-top: 1px dotted #ccc; margin: 8px 0;">
            <div class="terms-text">
              • Untuk diketahui bahwa anda tidak diperkenankan untuk membawa durian ke area hotel. / <em>Please be informed that you are not allowed to bring Durian into the hotel premises.</em><br>
              • Barang berharga (perhiasan, uang dsb) dapat anda simpan dalam brankas di kamar anda atau di kantor depan. / <em>For your valuable belongings (jewels, money, etc), you can use the safe deposit box in your room or front office.</em><br>
              • Kamar ini bebas rokok. Denda sebesar Rp. 1.000.000,- akan ditagihkan apabila Anda merokok di kamar ini. / <em>This room is designed as a non-smoking room. A fine of Rp. 1.000.000,- will be charged for smoking in this room.</em>
            </div>
          </td>
          <td rowspan="2" style="text-align: center; vertical-align: middle; height: 120px;">
            <div class="label-ind" style="margin-bottom: 50px;">Tanda Tangan Tamu<br><span class="label-eng">Guest Signature</span></div>
            <div class="cell-value" style="font-size: 8pt; font-weight: normal; border-top: 1px solid #000; padding-top: 3px;">
              {{ $reservation->guest->name }}
            </div>
          </td>
        </tr>
        <tr>
          <!-- Skipped due to rowspan -->
        </tr>

        <!-- Row 14 -->
        <tr>
          <td colspan="2">
            <div class="label-ind">Nomor Kotak Deposit / <span class="label-eng">Safety Deposit Box Number</span></div>
            <div class="cell-value">_________________________________</div>
          </td>
          <td colspan="2">
            <div class="label-ind">Dikeluarkan oleh / <span class="label-eng">Issued</span></div>
            <div class="cell-value">{{ auth()->user()->name }}</div>
          </td>
          <td>
            <div class="label-ind">Tanggal / <span class="label-eng">Date</span></div>
            <div class="cell-value">{{ now()->format('d M Y') }}</div>
          </td>
        </tr>
      </tbody>
    </table>

    <div class="signatures-bottom">
      <div class="sig-block">
        <div>Melapor masuk oleh</div>
        <div style="font-style: italic; font-size: 8pt; color: #555;">Check in by</div>
        <div class="sig-line-bottom"></div>
      </div>
      <div class="sig-block">
        <div>Melapor keluar oleh</div>
        <div style="font-style: italic; font-size: 8pt; color: #555;">Check out by</div>
        <div class="sig-line-bottom"></div>
      </div>
    </div>
  </div>

</body>
</html>
