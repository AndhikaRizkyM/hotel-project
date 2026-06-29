<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Guest Invoice - {{ $reservation->reservation_number }}</title>
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0f172a;
            --accent-color: #0ea5e9;
            --border-color: #cbd5e1;
            --bg-light: #f8fafc;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --success-color: #10b981;
            --danger-color: #ef4444;
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

        .info-value strong {
            color: var(--accent-color);
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.data-table th, table.data-table td {
            border: 1px solid #e2e8f0;
            padding: 10px 14px;
            font-size: 9pt;
        }

        table.data-table th {
            background-color: var(--bg-light);
            color: var(--primary-color);
            font-weight: 700;
            text-align: left;
            text-transform: uppercase;
            font-size: 8pt;
            letter-spacing: 0.5px;
        }

        table.data-table tr:nth-child(even) {
            background-color: #fafdff;
        }

        .summary-wrapper {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
        }

        .summary-box {
            width: 350px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 18px;
            background-color: var(--bg-light);
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 9pt;
            color: var(--text-dark);
        }

        .summary-row span:first-child {
            font-weight: 500;
            color: var(--text-muted);
        }

        .summary-row span:last-child {
            font-weight: 700;
        }

        .summary-row.total {
            font-size: 11pt;
            font-weight: bold;
            border-top: 1.5px dashed var(--border-color);
            padding-top: 12px;
            margin-top: 12px;
            margin-bottom: 0;
        }

        .summary-row.total span:first-child {
            color: var(--primary-color);
            font-weight: 800;
        }

        .summary-row.total span:last-child {
            color: var(--accent-color);
            font-weight: 800;
        }

        .signatures-area {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding: 0 20px;
        }

        .sig-block {
            text-align: center;
            width: 220px;
        }

        .sig-label {
            font-size: 8.5pt;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 60px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sig-line {
            border-top: 1.5px solid var(--primary-color);
            padding-top: 5px;
            font-weight: 700;
            font-size: 9.5pt;
            color: var(--text-dark);
        }

        .footer-note {
            text-align: center;
            margin-top: 40px;
            font-size: 8.5pt;
            color: var(--text-muted);
            border-top: 1px dashed var(--border-color);
            padding-top: 15px;
            font-style: italic;
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

            table.data-table th {
                background-color: #f1f5f9 !important;
                border: 1px solid #94a3b8 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            table.data-table td {
                border: 1px solid #94a3b8 !important;
            }

            .summary-box {
                border: 1px solid #94a3b8 !important;
                background-color: #f1f5f9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .section-title {
                border-left: 4px solid var(--accent-color) !important;
                background-color: #f1f5f9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    <div class="no-print-bar">
        <span class="no-print-title">Premium Guest Invoice View</span>
        <button class="no-print-btn" onclick="window.print()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="vertical-align: middle;">
                <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
            </svg>
            Print Invoice
        </button>
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
                <h1>Guest Invoice</h1>
                <p>Faktur Tagihan Tamu</p>
            </div>
        </div>

        <!-- Section: Invoice & Room Details -->
        <div class="section-title">Invoice & Stay Details / Rincian Tagihan & Kunjungan</div>
        <div class="grid-3">
            <div class="info-card">
                <div class="info-label">Invoice Number / <span class="info-label-id">No. Invoice</span></div>
                <div class="info-value">INV-{{ $reservation->reservation_number }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Room Number / <span class="info-label-id">No. Kamar</span></div>
                <div class="info-value">Room {{ $reservation->room->room_number }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Room Type / <span class="info-label-id">Jenis Kamar</span></div>
                <div class="info-value">{{ $reservation->room->roomType->name }}</div>
            </div>
        </div>
        
        <div class="grid-3">
            <div class="info-card">
                <div class="info-label">Check-In Date / <span class="info-label-id">Tgl Masuk</span></div>
                <div class="info-value">{{ \Carbon\Carbon::parse($reservation->check_in_date)->format('d M Y') }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Check-Out Date / <span class="info-label-id">Tgl Keluar</span></div>
                <div class="info-value">{{ \Carbon\Carbon::parse($reservation->check_out_date)->format('d M Y') }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Billing Status / <span class="info-label-id">Status Pembayaran</span></div>
                <div class="info-value">
                    @if($reservation->status === 'CO')
                        <span style="color: var(--success-color);">Paid / Checked Out</span>
                    @else
                        <span style="color: var(--accent-color);">Active / Checked In</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Section: Guest Details -->
        <div class="section-title">Guest Profile / Profil Tamu</div>
        <div class="grid-2">
            <div class="info-card">
                <div class="info-label">Guest Name / <span class="info-label-id">Nama Tamu</span></div>
                <div class="info-value">{{ $reservation->guest->name }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">ID Number / <span class="info-label-id">No. Identitas (NIK/Passport)</span></div>
                <div class="info-value">{{ $reservation->guest->id_number }}</div>
            </div>
        </div>
        <div class="grid-2">
            <div class="info-card">
                <div class="info-label">Address / <span class="info-label-id">Alamat</span></div>
                <div class="info-value">{{ $reservation->guest->address ?? '-' }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Country / <span class="info-label-id">Negara</span></div>
                <div class="info-value">{{ $reservation->guest->country }}</div>
            </div>
        </div>

        <!-- Section: Transaction Ledger -->
        <div class="section-title">Transaction Ledger Statement / Rincian Transaksi & Layanan</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 15%;">Date / <span class="info-label-id">Tanggal</span></th>
                    <th style="width: 25%;">Category / <span class="info-label-id">Kategori</span></th>
                    <th>Description / <span class="info-label-id">Deskripsi</span></th>
                    <th style="text-align: right; width: 20%;">Amount / <span class="info-label-id">Jumlah</span></th>
                </tr>
            </thead>
            <tbody>
                @php $totalCharges = 0; @endphp
                @if($reservation->folio && $reservation->folio->items)
                    @foreach($reservation->folio->items as $item)
                        @php $totalCharges += $item->amount; @endphp
                        <tr>
                            <td>{{ $item->created_at->format('d M Y') }}</td>
                            <td><strong>{{ $item->item_type }}</strong></td>
                            <td>{{ $item->description }}</td>
                            <td style="text-align: right; font-weight: 600;">Rp{{ number_format($item->amount, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--text-muted); py-4;">No ledger items loaded.</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- Section: Payments & Deposits -->
        <div class="section-title">Payment & Deposit Records / Catatan Pembayaran & Uang Jaminan</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 15%;">Date / <span class="info-label-id">Tanggal</span></th>
                    <th style="width: 25%;">Transaction Type / <span class="info-label-id">Jenis Transaksi</span></th>
                    <th>Payment Method & Notes / <span class="info-label-id">Metode & Catatan</span></th>
                    <th style="text-align: right; width: 20%;">Amount Paid / <span class="info-label-id">Jumlah Bayar</span></th>
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
                        <td>
                            <strong style="color: {{ $tx->type === 'payment' ? 'var(--success-color)' : 'var(--danger-color)' }}">
                                {{ ucfirst($tx->type) }}
                            </strong>
                        </td>
                        <td>{{ $tx->payment_method }} - {{ $tx->notes ?? 'Settlement' }}</td>
                        <td style="text-align: right; font-weight: 700; color: {{ $tx->type === 'payment' ? 'var(--success-color)' : 'var(--danger-color)' }}">
                            {{ $tx->type === 'payment' ? '' : '-' }}Rp{{ number_format($tx->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--text-muted); py-4;">No payments or deposits recorded.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @php
            $netPayments = $totPay - $totRef;
            $outstanding = $totalCharges - $netPayments;
        @endphp

        <!-- Statement Summary -->
        <div class="summary-wrapper">
            <div class="summary-box">
                <div class="summary-row">
                    <span>Total Charges:</span>
                    <span>Rp{{ number_format($totalCharges, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row">
                    <span>Total Paid (Net):</span>
                    <span style="color: var(--success-color);">Rp{{ number_format($netPayments, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row total">
                    <span>Outstanding:</span>
                    <span style="color: {{ $outstanding > 0.01 ? 'var(--danger-color)' : 'var(--success-color)' }};">
                        @if($outstanding > 0.01)
                            Rp{{ number_format($outstanding, 0, ',', '.') }}
                        @else
                            Rp0 (Settled)
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Signatures -->
        <div class="signatures-area">
            <div class="sig-block">
                <div class="sig-label">Guest Signature / <span class="info-label-id">Tanda Tangan Tamu</span></div>
                <div class="sig-line">{{ $reservation->guest->name }}</div>
            </div>
            <div class="sig-block">
                <div class="sig-label">Authorized Cashier / <span class="info-label-id">Kasir Hotel</span></div>
                <div class="sig-line">{{ auth()->user()->name }}</div>
            </div>
        </div>

        <div class="footer-note">
            Thank you for staying at PPKD Hotel. Have a safe journey home!<br>
            Terima kasih telah menginap di PPKD Hotel. Semoga perjalanan Anda menyenangkan!
        </div>
    </div>

</body>
</html>
