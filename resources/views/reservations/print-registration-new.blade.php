<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Registration Card - {{ $reservation->reservation_number }}</title>
    <style>
        :root {
            --primary-color: #0f172a;
            --accent-color: #0ea5e9;
            --border-color: #cbd5e1;
            --bg-light: #f8fafc;
            --text-dark: #1e293b;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-size: 10pt;
            color: var(--text-dark);
            margin: 0;
            padding: 20px;
            background-color: #fff;
            line-height: 1.5;
        }

        .container {
            max-width: 850px;
            margin: 0 auto;
            padding: 30px;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            background-color: #fff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .hotel-brand {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .hotel-brand img {
            height: 60px;
            width: auto;
        }

        .hotel-details h2 {
            margin: 0;
            font-size: 18pt;
            color: var(--primary-color);
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .hotel-details p {
            margin: 2px 0 0 0;
            font-size: 8pt;
            color: var(--text-muted);
        }

        .doc-title {
            text-align: right;
        }

        .doc-title h1 {
            margin: 0;
            font-size: 15pt;
            color: var(--accent-color);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .doc-title p {
            margin: 3px 0 0 0;
            font-size: 9pt;
            font-style: italic;
            color: var(--text-muted);
        }

        .section-title {
            font-size: 9.5pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--primary-color);
            background-color: var(--bg-light);
            padding: 6px 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            border-left: 3px solid var(--accent-color);
        }

        .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .info-card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 14px;
            background-color: #fff;
        }

        .info-label {
            font-size: 7.5pt;
            text-transform: uppercase;
            color: var(--text-muted);
            font-weight: 600;
            margin-bottom: 3px;
        }

        .info-label-id {
            font-size: 6.5pt;
            color: var(--text-muted);
            font-style: italic;
        }

        .info-value {
            font-size: 10pt;
            font-weight: 700;
            color: var(--text-dark);
        }

        .terms-box {
            font-size: 8pt;
            color: var(--text-dark);
            border: 1px dashed var(--border-color);
            border-radius: 8px;
            padding: 15px;
            background-color: var(--bg-light);
            margin-bottom: 25px;
            text-align: justify;
        }

        .terms-box ul {
            margin: 8px 0 0 0;
            padding-left: 20px;
        }

        .terms-box li {
            margin-bottom: 4px;
        }

        .signatures-area {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding: 0 20px;
        }

        .sig-block {
            text-align: center;
            width: 200px;
        }

        .sig-label {
            font-size: 8.5pt;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 55px;
        }

        .sig-line {
            border-top: 1.5px solid var(--primary-color);
            padding-top: 5px;
            font-weight: 700;
            font-size: 9.5pt;
            color: var(--text-dark);
        }

        .no-print-bar {
            background-color: var(--bg-light);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 850px;
            margin: 0 auto 20px auto;
        }

        .no-print-title {
            font-weight: 700;
            font-size: 11pt;
            color: var(--primary-color);
        }

        .no-print-btn {
            background-color: var(--accent-color);
            color: #fff;
            border: none;
            padding: 8px 20px;
            font-weight: 700;
            border-radius: 8px;
            cursor: pointer;
            transition: opacity 0.2s;
            font-size: 9.5pt;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .no-print-btn:hover {
            opacity: 0.9;
        }

        @media print {
            .no-print-bar {
                display: none !important;
            }

            body {
                padding: 0;
                color: #000;
            }

            .container {
                border: none;
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }

            .info-card {
                border: 1px solid #94a3b8;
            }

            .terms-box {
                border: 1px dashed #94a3b8;
                background-color: #fff;
            }
        }
    </style>
</head>

<body>

    <div class="no-print-bar">
        <span class="no-print-title">Registration Form View</span>
        <button class="no-print-btn" onclick="window.print()">Print Registration Card</button>
    </div>

    <div class="container">
        <div class="header">
            <div class="hotel-brand">
                <img src="{{ asset('logo_PPKDJP.png') }}" alt="Logo">
                <div class="hotel-details">
                    <h2>PPKD HOTEL</h2>
                    <p>Jl. Raya Kemayoran No. 1, Jakarta Pusat | Telp: (021) 123456</p>
                </div>
            </div>
            <div class="doc-title">
                <h1>Registration Card</h1>
                <p>Guest Registration Form</p>
            </div>
        </div>

        <!-- Section: Room & Stay Info -->
        <div class="section-title">Stay & Room Details / Rincian Kamar & Kunjungan</div>
        <div class="grid-3">
            <div class="info-card">
                <div class="info-label">Room Number / <span class="info-label-id">No. Kamar</span></div>
                <div class="info-value">{{ $reservation->room->room_number }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Room Type / <span class="info-label-id">Jenis Kamar</span></div>
                <div class="info-value">{{ $reservation->room->roomType->name }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">No. of Person / <span class="info-label-id">Jumlah Tamu</span></div>
                <div class="info-value">{{ $reservation->number_of_guests }} Guest(s)</div>
            </div>
        </div>
        <div class="grid-3">
            <div class="info-card">
                <div class="info-label">Arrival Date / <span class="info-label-id">Tgl Kedatangan</span></div>
                <div class="info-value">{{ \Carbon\Carbon::parse($reservation->check_in_date)->format('d M Y') }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Arrival Time / <span class="info-label-id">Waktu Masuk</span></div>
                <div class="info-value">
                    {{ \Carbon\Carbon::parse($reservation->check_in_time ?? '14:00')->format('H:i') }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Departure Date / <span class="info-label-id">Tgl Keberangkatan</span></div>
                <div class="info-value">{{ \Carbon\Carbon::parse($reservation->check_out_date)->format('d M Y') }}</div>
            </div>
        </div>

        <!-- Section: Guest Profile -->
        <div class="section-title">Guest Profile / Profil Tamu</div>
        <div class="grid-2">
            <div class="info-card">
                <div class="info-label">Full Name / <span class="info-label-id">Nama Lengkap</span></div>
                <div class="info-value">{{ $reservation->guest->name }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">ID or Passport No. / <span class="info-label-id">No. Identitas</span></div>
                <div class="info-value">{{ $reservation->guest->id_number }}</div>
            </div>
        </div>
        <div class="grid-3">
            <div class="info-card">
                <div class="info-label">Nationality / <span class="info-label-id">Kewarganegaraan</span></div>
                <div class="info-value">{{ $reservation->guest->country }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Birth Date / <span class="info-label-id">Tanggal Lahir</span></div>
                <div class="info-value">
                    {{ $reservation->guest->birth_date ? \Carbon\Carbon::parse($reservation->guest->birth_date)->format('d M Y') : '-' }}
                </div>
            </div>
            <div class="info-card">
                <div class="info-label">Gender / <span class="info-label-id">Jenis Kelamin</span></div>
                <div class="info-value">{{ $reservation->guest->gender }}</div>
            </div>
        </div>
        <div class="grid-3">
            <div class="info-card">
                <div class="info-label">Profession / <span class="info-label-id">Pekerjaan</span></div>
                <div class="info-value">{{ $reservation->guest->profession ?? '-' }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Company / <span class="info-label-id">Perusahaan</span></div>
                <div class="info-value">{{ $reservation->guest->company ?? '-' }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Member Card No. / <span class="info-label-id">No. Member</span></div>
                <div class="info-value">{{ $reservation->guest->member_card_no ?? '-' }}</div>
            </div>
        </div>
        <div class="grid-2">
            <div class="info-card">
                <div class="info-label">Phone Number / <span class="info-label-id">No. Telepon</span></div>
                <div class="info-value">{{ $reservation->guest->phone }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Email Address / <span class="info-label-id">Alamat Email</span></div>
                <div class="info-value">{{ $reservation->guest->email ?? '-' }}</div>
            </div>
        </div>
        <div class="grid-2">
            <div class="info-card">
                <div class="info-label">Home Address / <span class="info-label-id">Alamat Rumah</span></div>
                <div class="info-value" style="font-weight: normal; font-size: 9.5pt;">
                    {{ $reservation->guest->address ?? '-' }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Vehicle License Plate / <span class="info-label-id">No. Polisi Kendaraan</span>
                </div>
                <div class="info-value">{{ $reservation->guest->vehicle_no ?? '-' }}</div>
            </div>
        </div>

        <!-- Section: Payment & Misc -->
        <div class="section-title">Payment & Guarantee / Pembayaran & Jaminan</div>
        <div class="grid-2">
            <div class="info-card">
                <div class="info-label">Method of Payment / <span class="info-label-id">Metode Pembayaran</span></div>
                @php
                    $payMethod = $reservation->deposits->where('type', 'payment')->first()?->payment_method ?? 'Cash';
                @endphp
                <div class="info-value">{{ $payMethod }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Safety Deposit Box No. / <span class="info-label-id">Nomor Kotak
                        Simpanan</span></div>
                <div class="info-value">{{ $reservation->safety_deposit_box ?? '-' }}</div>
            </div>
        </div>

        <!-- Section: Terms -->
        <div class="terms-box">
            <strong>Declaration & Terms / Pernyataan & Ketentuan:</strong><br>
            Saya menyatakan bertanggung jawab baik secara pribadi maupun bersama-sama dengan perusahaan, asosiasi, atau
            perorangan atas seluruh tagihan pelayanan yang timbul selama kunjungan saya. / <em>I acknowledge that I am
                jointly and severally liable with the company, association, or individual for all charges incurred
                during my stay.</em>
            <ul>
                <li>Dilarang keras membawa buah durian atau hewan peliharaan ke dalam area hotel. / <em>Bringing durians
                        or pets into the hotel premises is strictly prohibited.</em></li>
                <li>Gunakan brankas (safety deposit box) untuk menyimpan barang berharga Anda. Hotel tidak bertanggung
                    jawab atas hilangnya barang berharga di luar brankas. / <em>Please store your valuables in the safe
                        deposit box. The hotel is not liable for any lost property outside the safe.</em></li>
                <li>Dilarang merokok di dalam kamar (Non-Smoking Room). Pelanggaran akan dikenakan denda Rp 1.000.000,-.
                    / <em>This is a non-smoking room. Violators will be fined Rp 1.000.000,-.</em></li>
            </ul>
        </div>

        <!-- Section: Signatures -->
        <div class="signatures-area">
            <div class="sig-block">
                <div class="sig-label">Guest Signature / <span class="info-label-id">Tangan Tamu</span></div>
                <div class="sig-line">{{ $reservation->guest->name }}</div>
            </div>
            <div class="sig-block">
                <div class="sig-label">Front Office Agent / <span class="info-label-id">Petugas</span></div>
                <div class="sig-line">{{ auth()->user()->name }}</div>
            </div>
        </div>
    </div>

</body>

</html>
