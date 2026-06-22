@extends('layouts.admin')

@section('title', 'Laundry Operations')

@section('content')
<div class="page-heading">
  <div class="page-heading-copy">
    <span class="page-icon"><i class="bi bi-water" aria-hidden="true"></i></span>
    <div>
      <p class="eyebrow mb-1">HOUSEKEEPING & LAUNDRY</p>
      <h1 class="h3 mb-1">Laundry Service Orders</h1>
      <p class="text-muted mb-0">Track guest laundry requests, manage washing statuses, and log damage compensation claims.</p>
    </div>
  </div>
</div>

<div class="row g-4">
  <!-- Active Orders Queue -->
  <div class="col-12 col-xl-8">
    <div class="panel shadow-sm">
      <div class="panel-header border-bottom pb-2 mb-3">
        <h2 class="h5 mb-0 section-title"><i class="bi bi-collection text-primary"></i><span>Active Service Queue</span></h2>
      </div>

      <div class="table-responsive">
        <table class="table align-middle table-sm">
          <thead>
            <tr>
              <th>Order Ref</th>
              <th>Room & Guest</th>
              <th>Service & Items</th>
              <th>Total Cost</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($activeOrders as $order)
              <tr>
                <td class="fw-bold">#LDR-{{ $order->id }}</td>
                <td>
                  <strong>Room {{ $order->reservation->room->room_number }}</strong>
                  <br><span class="text-muted small">{{ $order->reservation->guest->name }}</span>
                </td>
                <td>
                  <span class="badge bg-light text-dark fw-bold mb-1">{{ $order->service->name }}</span>
                  <ul class="margin-0 padding-left-15 small mb-0">
                    @foreach($order->items as $item)
                      <li>{{ $item->item_name }} (x{{ $item->qty }})</li>
                    @endforeach
                  </ul>
                </td>
                <td class="fw-semibold text-primary">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                <td>
                  @php
                    $colors = [
                      'Pending' => 'danger',
                      'Collected' => 'warning',
                      'Washing' => 'info',
                      'Ready' => 'primary',
                      'Delivered' => 'success',
                      'Cancelled' => 'secondary'
                    ];
                  @endphp
                  <span class="badge bg-{{ $colors[$order->status] ?? 'secondary' }}">{{ $order->status }}</span>
                </td>
                <td>
                  <form action="{{ route('hk.laundry.status', $order->id) }}" method="POST" class="d-inline">
                    @csrf
                    <div class="input-group input-group-xs" style="max-width: 180px;">
                      <select name="status" class="form-select form-select-xs" required>
                        <option value="">Status...</option>
                        <option value="Collected" {{ $order->status === 'Collected' ? 'selected' : '' }}>Collected</option>
                        <option value="Washing" {{ $order->status === 'Washing' ? 'selected' : '' }}>Washing</option>
                        <option value="Ready" {{ $order->status === 'Ready' ? 'selected' : '' }}>Ready</option>
                        <option value="Delivered" {{ $order->status === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="Cancelled" {{ $order->status === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                      </select>
                      <button type="submit" class="btn btn-primary btn-xs">Update</button>
                    </div>
                  </form>
                  <button type="button" class="btn btn-outline-danger btn-xs mt-1" data-bs-toggle="modal" data-bs-target="#claimModal-{{ $order->id }}"><i class="bi bi-flag"></i> Report Damage</button>
                </td>
              </tr>

              <!-- Claim damage Modal -->
              <div class="modal fade" id="claimModal-{{ $order->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content text-start">
                    <div class="modal-header">
                      <h5 class="modal-title fw-bold text-danger"><i class="bi bi-exclamation-triangle"></i> Report Laundry Damage for #LDR-{{ $order->id }}</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('hk.laundry.damage', $order->id) }}" method="POST">
                      @csrf
                      <div class="modal-body">
                        <div class="mb-3">
                          <label for="item_name-{{ $order->id }}" class="form-label small fw-bold">Damaged/Lost Item Description</label>
                          <input type="text" name="item_name" id="item_name-{{ $order->id }}" class="form-control form-control-sm" required placeholder="e.g. Kemeja Batik Sutera">
                        </div>
                        <div class="mb-3">
                          <label for="issue_type-{{ $order->id }}" class="form-label small fw-bold">Issue Type</label>
                          <select name="issue_type" id="issue_type-{{ $order->id }}" class="form-select form-select-sm" required>
                            <option value="damage">Damaged (Broken/Torn/Stained)</option>
                            <option value="lost">Lost Item</option>
                          </select>
                        </div>
                        <div class="mb-3">
                          <label for="compensation-{{ $order->id }}" class="form-label small fw-bold">Compensation Amount (IDR)</label>
                          <input type="number" name="compensation_amount" id="compensation-{{ $order->id }}" min="0" value="0" class="form-control form-control-sm" required>
                        </div>
                        <div class="mb-3 form-check">
                          <input class="form-check-input" type="checkbox" name="resolve_immediately" id="resolve-{{ $order->id }}" value="1" checked>
                          <label class="form-check-label small fw-bold text-success" for="resolve-{{ $order->id }}">Resolve Immediately (Credit Guest Folio ledger directly)</label>
                        </div>
                        <div class="mb-3">
                          <label for="desc-{{ $order->id }}" class="form-label small fw-bold">Incident Description Details</label>
                          <textarea name="description" id="desc-{{ $order->id }}" rows="2" class="form-control form-control-sm" placeholder="Detail explanation of damage or loss incident..."></textarea>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger btn-sm">Log Damage Claim</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-4">No active laundry orders currently in process.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Completed Orders History -->
  <div class="col-12 col-xl-4">
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <h5 class="fw-bold border-bottom pb-2 mb-3"><i class="bi bi-clock-history"></i> Completed Orders History</h5>
        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
          <table class="table table-sm align-middle small mb-0">
            <thead>
              <tr>
                <th>Order</th>
                <th>Room</th>
                <th>Total</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @forelse($completedOrders as $comp)
                <tr>
                  <td><strong>#LDR-{{ $comp->id }}</strong><br><small class="text-muted">{{ \Carbon\Carbon::parse($comp->order_date)->format('d/m H:i') }}</small></td>
                  <td>Room {{ $comp->reservation->room->room_number }}</td>
                  <td>Rp{{ number_format($comp->total_amount, 0, ',', '.') }}</td>
                  <td>
                    <span class="badge bg-{{ $comp->status === 'Delivered' ? 'success' : 'secondary' }}">{{ $comp->status }}</span>
                  </td>
                </tr>
              @empty
                <tr><td colspan="4" class="text-center text-muted">No completed history.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
