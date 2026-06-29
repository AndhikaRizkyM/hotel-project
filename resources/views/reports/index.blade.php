@extends('layouts.admin')

@section('title', 'Analytics Reports')

@section('content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-bar-chart-line" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">MANAGEMENT PANEL</p>
                <h1 class="h3 mb-1">Hotel Performance & Financial Reports</h1>
                <p class="text-muted mb-0">Monitor room occupancy rates, service revenue breakdowns, and cash flow ledgers.
                </p>
            </div>
        </div>
        <div class="heading-actions">
            <a href="{{ route('reports.export-excel', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                class="btn btn-success btn-tactile btn-sm"><i class="bi bi-file-earmark-excel p-2"></i> Export to
                Excel
                (.xls)</a>
        </div>
    </div>

    <!-- Filters Panel -->
    <div class="panel-premium mb-4 shadow-sm p-4">
        <form method="GET" action="{{ route('reports.index') }}" class="row g-3 align-items-end">
            <div class="col-12 col-md-4">
                <label for="start_date" class="form-label small fw-bold">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate->toDateString() }}"
                    class="form-control form-control-sm">
            </div>
            <div class="col-12 col-md-4">
                <label for="end_date" class="form-label small fw-bold">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate->toDateString() }}"
                    class="form-control form-control-sm">
            </div>
            <div class="col-12 col-md-4 d-flex">
                <button type="submit" class="btn btn-primary btn-tactile btn-sm me-2 w-100"><i class="bi bi-funnel"></i>
                    Generate Report</button>
                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-tactile btn-sm w-100"><i
                        class="bi bi-arrow-clockwise"></i> Reset</a>
            </div>
        </form>
    </div>

    <!-- Stats / KPIs Cards -->
    <section class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="metric-card metric-primary shadow-sm h-100">
                <div class="metric-top">
                    <span class="metric-label">Live Occupancy</span>
                    <span class="metric-icon"><i class="bi bi-door-open"></i></span>
                </div>
                <div class="metric-value" style="font-size: 1.65rem;">{{ $occupiedRooms }} / {{ $totalRooms }} Rooms</div>
                <div class="metric-meta text-muted">Occupancy rate: <strong
                        class="text-primary">{{ number_format($occupancyRate, 1) }}%</strong></div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="metric-card metric-success shadow-sm h-100">
                <div class="metric-top">
                    <span class="metric-label">Billing Revenue</span>
                    <span class="metric-icon"><i class="bi bi-wallet2"></i></span>
                </div>
                <div class="metric-value" style="font-size: 1.65rem;">Rp{{ number_format($totalRevenue, 0, ',', '.') }}
                </div>
                <div class="metric-meta text-muted">From guest folio items logged</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="metric-card metric-info shadow-sm h-100">
                <div class="metric-top">
                    <span class="metric-label">Cash Inflow</span>
                    <span class="metric-icon"><i class="bi bi-cash-stack"></i></span>
                </div>
                <div class="metric-value" style="font-size: 1.65rem;">Rp{{ number_format($cashInflow, 0, ',', '.') }}</div>
                <div class="metric-meta text-muted">Deposits & payments collected</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="metric-card {{ $netCashFlow >= 0 ? 'metric-success' : 'metric-danger' }} shadow-sm h-100">
                <div class="metric-top">
                    <span class="metric-label">Net Cash Flow</span>
                    <span class="metric-icon"><i class="bi bi-arrow-left-right"></i></span>
                </div>
                <div class="metric-value"
                    style="font-size: 1.65rem; color: var(--{{ $netCashFlow >= 0 ? 'admin-success' : 'admin-danger' }}) !important;">
                    Rp{{ number_format($netCashFlow, 0, ',', '.') }}</div>
                <div class="metric-meta text-muted">Refund Outflow: Rp{{ number_format($cashOutflow, 0, ',', '.') }}</div>
            </div>
        </div>
    </section>

    <div class="row g-4">
        <!-- Revenue Breakdown Table -->
        <div class="col-12 col-lg-5">
            <div class="panel-premium shadow-sm h-100 p-4">
                <div class="panel-header border-bottom pb-2 mb-3">
                    <h2 class="h5 mb-0 section-title"><i class="bi bi-pie-chart text-primary"></i><span>Revenue by
                            Category</span></h2>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-sm small mb-0">
                        <thead>
                            <tr>
                                <th>Service Category</th>
                                <th class="text-end">Revenue Amount</th>
                                <th class="text-end">Share %</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($revenueDetails as $cat => $amount)
                                @php
                                    $share = $totalRevenue > 0 ? ($amount / $totalRevenue) * 100 : 0;
                                @endphp
                                <tr>
                                    <td><strong>{{ $cat }}</strong></td>
                                    <td class="text-end fw-semibold text-primary">
                                        Rp{{ number_format($amount, 0, ',', '.') }}</td>
                                    <td class="text-end font-monospace text-muted">{{ number_format($share, 1) }}%</td>
                                </tr>
                            @endforeach
                            <tr class="table-light fw-bold border-top"
                                style="border-top: 2px solid var(--admin-border) !important; background: rgba(0,0,0,0.02);">
                                <td>TOTAL REVENUE:</td>
                                <td class="text-end text-success">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</td>
                                <td class="text-end font-monospace">100.0%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Ledger Transactions -->
        <div class="col-12 col-lg-7">
            <div class="panel-premium shadow-sm h-100 p-4">
                <div class="panel-header border-bottom pb-2 mb-3">
                    <h2 class="h5 mb-0 section-title"><i class="bi bi-list-stars text-primary"></i><span>Deposit Ledger
                            Transactions</span></h2>
                </div>
                <div class="table-responsive" style="max-height: 380px; overflow-y: auto;">
                    <table class="table table-hover table-sm small align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Res No.</th>
                                <th>Guest & Room</th>
                                <th>Method</th>
                                <th>Type</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $tx)
                                <tr>
                                    <td class="text-muted">
                                        {{ \Carbon\Carbon::parse($tx->transaction_date)->format('d/m H:i') }}</td>
                                    <td class="fw-bold">#{{ $tx->reservation->reservation_number ?? 'N/A' }}</td>
                                    <td>
                                        <strong>{{ $tx->reservation->guest->name ?? 'N/A' }}</strong>
                                        <br><small class="text-muted">Room
                                            {{ $tx->reservation->room->room_number ?? 'N/A' }}</small>
                                    </td>
                                    <td><span class="badge badge-soft-secondary">{{ $tx->payment_method }}</span></td>
                                    <td>
                                        <span
                                            class="badge {{ $tx->type === 'payment' ? 'badge-soft-success' : 'badge-soft-danger' }}">{{ ucfirst($tx->type) }}</span>
                                    </td>
                                    <td
                                        class="text-end fw-bold {{ $tx->type === 'payment' ? 'text-success' : 'text-danger' }}">
                                        Rp{{ number_format($tx->amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No transactions recorded in
                                        this period.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
