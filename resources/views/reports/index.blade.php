@extends('layouts.admin')

@section('title', 'Analytics Reports')

@section('content')
<div class="page-heading">
  <div class="page-heading-copy">
    <span class="page-icon"><i class="bi bi-bar-chart-line" aria-hidden="true"></i></span>
    <div>
      <p class="eyebrow mb-1">MANAGEMENT PANEL</p>
      <h1 class="h3 mb-1">Hotel Performance & Financial Reports</h1>
      <p class="text-muted mb-0">Monitor room occupancy rates, service revenue breakdowns, and cash flow ledgers.</p>
    </div>
  </div>
  <div class="heading-actions">
    <a href="{{ route('reports.export-excel', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="btn btn-success btn-sm"><i class="bi bi-file-earmark-excel"></i> Export to Excel (.xls)</a>
  </div>
</div>

<!-- Filters Panel -->
<div class="panel mb-4 shadow-sm">
  <form method="GET" action="{{ route('reports.index') }}" class="row g-3 align-items-end">
    <div class="col-12 col-md-4">
      <label for="start_date" class="form-label small fw-bold">Start Date</label>
      <input type="date" name="start_date" id="start_date" value="{{ $startDate->toDateString() }}" class="form-control form-control-sm">
    </div>
    <div class="col-12 col-md-4">
      <label for="end_date" class="form-label small fw-bold">End Date</label>
      <input type="date" name="end_date" id="end_date" value="{{ $endDate->toDateString() }}" class="form-control form-control-sm">
    </div>
    <div class="col-12 col-md-4 d-flex">
      <button type="submit" class="btn btn-primary btn-sm me-2 w-100"><i class="bi bi-funnel"></i> Generate Report</button>
      <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm w-100"><i class="bi bi-arrow-clockwise"></i> Reset</a>
    </div>
  </form>
</div>

<!-- Stats / KPIs Cards -->
<section class="row g-3 mb-4">
  <div class="col-12 col-sm-6 col-md-3">
    <div class="metric-card metric-primary p-3 border rounded bg-white shadow-sm">
      <span class="text-muted small d-block mb-1">Total Rooms / Live Occupancy</span>
      <span class="h3 mb-0 text-primary">{{ $occupiedRooms }} / {{ $totalRooms }} Rooms</span>
      <small class="text-muted d-block mt-1">Occupancy: <strong>{{ number_format($occupancyRate, 1) }}%</strong></small>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-md-3">
    <div class="metric-card metric-success p-3 border rounded bg-white shadow-sm">
      <span class="text-muted small d-block mb-1">Total Billing Revenue</span>
      <span class="h3 mb-0 text-success">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</span>
      <small class="text-muted d-block mt-1">From guest folio items logged</small>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-md-3">
    <div class="metric-card metric-info p-3 border rounded bg-white shadow-sm">
      <span class="text-muted small d-block mb-1">Cash Inflow Settlements</span>
      <span class="h3 mb-0 text-info">Rp{{ number_format($cashInflow, 0, ',', '.') }}</span>
      <small class="text-muted d-block mt-1">Deposits & payments collected</small>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-md-3">
    <div class="metric-card metric-danger p-3 border rounded bg-white shadow-sm">
      <span class="text-muted small d-block mb-1">Net Cash Flow</span>
      <span class="h3 mb-0 text-{{ $netCashFlow >= 0 ? 'success' : 'danger' }}">Rp{{ number_format($netCashFlow, 0, ',', '.') }}</span>
      <small class="text-muted d-block mt-1">Refund Outflow: Rp{{ number_format($cashOutflow, 0, ',', '.') }}</small>
    </div>
  </div>
</section>

<div class="row g-3">
  <!-- Revenue Breakdown Chart/Table -->
  <div class="col-12 col-lg-5">
    <div class="panel shadow-sm h-100">
      <div class="panel-header border-bottom pb-2 mb-3">
        <h2 class="h5 mb-0 section-title"><i class="bi bi-pie-chart text-primary"></i><span>Revenue by Category</span></h2>
      </div>
      <div class="table-responsive">
        <table class="table align-middle table-sm small">
          <thead>
            <tr>
              <th>Service Category</th>
              <th class="text-end">Revenue Amount</th>
              <th class="text-end">Share %</th>
            </tr>
          </thead>
          <tbody>
            @foreach($revenueDetails as $cat => $amount)
              @php
                $share = $totalRevenue > 0 ? ($amount / $totalRevenue) * 100 : 0;
              @endphp
              <tr>
                <td><strong>{{ $cat }}</strong></td>
                <td class="text-end fw-semibold text-primary">Rp{{ number_format($amount, 0, ',', '.') }}</td>
                <td class="text-end font-monospace text-muted">{{ number_format($share, 1) }}%</td>
              </tr>
            @endforeach
            <tr class="table-light fw-bold border-top" style="border-top: 2px solid #ccc !important;">
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
    <div class="panel shadow-sm h-100">
      <div class="panel-header border-bottom pb-2 mb-3">
        <h2 class="h5 mb-0 section-title"><i class="bi bi-list-stars text-primary"></i><span>Deposit Ledger Transactions</span></h2>
      </div>
      <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
        <table class="table table-striped table-sm small align-middle">
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
                <td>{{ \Carbon\Carbon::parse($tx->transaction_date)->format('d/m H:i') }}</td>
                <td class="fw-bold">{{ $tx->reservation->reservation_number ?? 'N/A' }}</td>
                <td>
                  <strong>{{ $tx->reservation->guest->name ?? 'N/A' }}</strong>
                  <br><small class="text-muted">Room {{ $tx->reservation->room->room_number ?? 'N/A' }}</small>
                </td>
                <td>{{ $tx->payment_method }}</td>
                <td>
                  <span class="badge bg-{{ $tx->type === 'payment' ? 'success' : 'danger' }}">{{ ucfirst($tx->type) }}</span>
                </td>
                <td class="text-end fw-bold {{ $tx->type === 'payment' ? 'text-success' : 'text-danger' }}">
                  Rp{{ number_format($tx->amount, 0, ',', '.') }}
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-4">No transactions recorded in this period.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
