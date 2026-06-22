@extends('layouts.admin')

@section('title', 'Room Service Orders')

@section('content')
<div class="page-heading">
  <div class="page-heading-copy">
    <span class="page-icon"><i class="bi bi-cart-check" aria-hidden="true"></i></span>
    <div>
      <p class="eyebrow mb-1">FOOD & BEVERAGE DEPT</p>
      <h1 class="h3 mb-1">Room Service Order Queue</h1>
      <p class="text-muted mb-0">Manage kitchen order preparation, status updates, and track room service deliveries.</p>
    </div>
  </div>
</div>

<div class="row g-4">
  <!-- Active Kitchen Queue -->
  <div class="col-12 col-xl-8">
    <div class="panel shadow-sm">
      <div class="panel-header border-bottom pb-2 mb-3">
        <h2 class="h5 mb-0 section-title"><i class="bi bi-fire text-danger"></i><span>Kitchen Preparation Queue</span></h2>
      </div>

      <div class="table-responsive">
        <table class="table align-middle table-sm">
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Room No</th>
              <th>Food & Drink Items</th>
              <th>Order Cost</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($activeOrders as $order)
              <tr>
                <td class="fw-bold">#FNB-{{ $order->id }}</td>
                <td class="fw-bold">Room {{ $order->reservation->room->room_number }}</td>
                <td>
                  <ul class="margin-0 padding-left-15 small mb-0">
                    @foreach($order->items as $item)
                      <li><strong>{{ $item->menu->name }}</strong> (x{{ $item->qty }})</li>
                    @endforeach
                  </ul>
                </td>
                <td class="fw-semibold text-primary">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                <td>
                  @php
                    $colors = [
                      'Pending' => 'danger',
                      'Preparing' => 'warning',
                      'Ready' => 'info',
                      'Delivered' => 'success',
                      'Cancelled' => 'secondary'
                    ];
                  @endphp
                  <span class="badge bg-{{ $colors[$order->status] ?? 'secondary' }}">{{ $order->status }}</span>
                </td>
                <td>
                  <form action="{{ route('fb.orders.status', $order->id) }}" method="POST" class="d-inline">
                    @csrf
                    <div class="input-group input-group-xs" style="max-width: 180px;">
                      <select name="status" class="form-select form-select-xs" required>
                        <option value="">Status...</option>
                        <option value="Preparing" {{ $order->status === 'Preparing' ? 'selected' : '' }}>Preparing</option>
                        <option value="Ready" {{ $order->status === 'Ready' ? 'selected' : '' }}>Ready</option>
                        <option value="Delivered" {{ $order->status === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="Cancelled" {{ $order->status === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                      </select>
                      <button type="submit" class="btn btn-primary btn-xs">Update</button>
                    </div>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-4">No active room service orders in the queue.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Recent Deliveries Logs -->
  <div class="col-12 col-xl-4">
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <h5 class="fw-bold border-bottom pb-2 mb-3"><i class="bi bi-clock-history"></i> Recent Completed Orders</h5>
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
                  <td><strong>#FNB-{{ $comp->id }}</strong><br><small class="text-muted">{{ \Carbon\Carbon::parse($comp->order_date)->format('d/m H:i') }}</small></td>
                  <td>Room {{ $comp->reservation->room->room_number }}</td>
                  <td>Rp{{ number_format($comp->total_amount, 0, ',', '.') }}</td>
                  <td>
                    <span class="badge bg-{{ $comp->status === 'Delivered' ? 'success' : 'secondary' }}">{{ $comp->status }}</span>
                  </td>
                </tr>
              @empty
                <tr><td colspan="4" class="text-center text-muted">No completed orders history.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
