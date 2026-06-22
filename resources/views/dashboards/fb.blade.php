@extends('layouts.admin')

@section('title', 'F&B Kitchen Dashboard')

@section('content')
<div class="page-heading">
  <div class="page-heading-copy">
    <span class="page-icon"><i class="bi bi-egg-fried" aria-hidden="true"></i></span>
    <div>
      <p class="eyebrow mb-1">FOOD & BEVERAGE OPERATIONS</p>
      <h1 class="h3 mb-1">Kitchen & Room Service</h1>
      <p class="text-muted mb-0">Track incoming room service orders, adjust statuses, and review breakfast lists.</p>
    </div>
  </div>
</div>

<div class="row g-3">
  <!-- Metrics cards -->
  <div class="col-6 col-md-3">
    <div class="p-3 border rounded bg-white shadow-sm text-center">
      <span class="text-secondary small d-block mb-1">Incoming Orders</span>
      <span class="h3 mb-0 text-dark">{{ $fbOrders->where('status', 'Pending')->count() }}</span>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="p-3 border rounded bg-white shadow-sm text-center">
      <span class="text-warning small d-block mb-1">Preparing</span>
      <span class="h3 mb-0 text-warning">{{ $fbOrders->where('status', 'Preparing')->count() }}</span>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="p-3 border rounded bg-white shadow-sm text-center">
      <span class="text-info small d-block mb-1">Ready for Delivery</span>
      <span class="h3 mb-0 text-info">{{ $fbOrders->where('status', 'Ready')->count() }}</span>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="p-3 border rounded bg-white shadow-sm text-center">
      <span class="text-success small d-block mb-1">Breakfast Guests</span>
      <span class="h3 mb-0 text-success">{{ $breakfastGuests->count() }}</span>
    </div>
  </div>
</div>

<div class="row g-3 mt-3">
  <!-- Room service orders queue -->
  <div class="col-12 col-xl-7">
    <div class="panel">
      <div class="panel-header border-bottom pb-2 mb-3">
        <h2 class="h5 mb-0 section-title"><i class="bi bi-bell text-danger"></i><span>Active Room Service Orders</span></h2>
      </div>

      <div class="row g-3">
        @forelse($fbOrders as $order)
          <div class="col-12">
            <div class="card shadow-xs border-light p-3">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                  <h6 class="fw-bold mb-0">Order #FNB-{{ $order->id }} &mdash; Room {{ $order->reservation->room->room_number }}</h6>
                  <small class="text-muted">Placed at: {{ $order->order_date->format('H:i') }} | Guest: {{ $order->reservation->guest->name }}</small>
                </div>
                <div>
                  <span class="badge bg-{{ $order->status === 'Pending' ? 'secondary' : ($order->status === 'Preparing' ? 'warning' : 'info') }}">{{ $order->status }}</span>
                </div>
              </div>

              <!-- Order items -->
              <ul class="list-group list-group-flush mb-3">
                @foreach($order->items as $item)
                  <li class="list-group-item d-flex justify-content-between align-items-center py-1 px-0 bg-transparent text-dark border-0">
                    <span>{{ $item->qty }}x {{ $item->menu->name }}</span>
                    <span class="font-monospace small">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                  </li>
                @endforeach
                <li class="list-group-item d-flex justify-content-between align-items-center py-1 px-0 bg-transparent fw-bold text-dark border-top">
                  <span>Total Amount</span>
                  <span>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </li>
              </ul>

              <!-- Action buttons -->
              <div class="d-flex justify-content-end gap-1">
                @if($order->status === 'Pending')
                  <form action="{{ route('fb.orders.status', $order->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="Preparing">
                    <button class="btn btn-warning btn-sm" type="submit"><i class="bi bi-play"></i> Start Preparing</button>
                  </form>
                @elseif($order->status === 'Preparing')
                  <form action="{{ route('fb.orders.status', $order->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="Ready">
                    <button class="btn btn-info btn-sm" type="submit"><i class="bi bi-check-circle"></i> Mark Ready</button>
                  </form>
                @elseif($order->status === 'Ready')
                  <form action="{{ route('fb.orders.status', $order->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="Delivered">
                    <button class="btn btn-success btn-sm" type="submit"><i class="bi bi-bicycle"></i> Mark Delivered</button>
                  </form>
                @endif
                
                @if($order->status !== 'Ready')
                  <form action="{{ route('fb.orders.status', $order->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="Cancelled">
                    <button class="btn btn-outline-danger btn-sm" type="submit"><i class="bi bi-x-circle"></i> Cancel</button>
                  </form>
                @endif
              </div>
            </div>
          </div>
        @empty
          <div class="col-12">
            <p class="text-muted text-center py-4">No active orders in the queue. All caught up!</p>
          </div>
        @endforelse
      </div>
    </div>
  </div>

  <!-- Today's Breakfast guests list -->
  <div class="col-12 col-xl-5">
    <div class="panel">
      <div class="panel-header border-bottom pb-2 mb-3">
        <h2 class="h5 mb-0 section-title"><i class="bi bi-egg-fried text-success"></i><span>Today's Breakfast List</span></h2>
      </div>

      <div class="table-responsive" style="max-height: 450px; overflow-y: auto;">
        <table class="table align-middle table-sm">
          <thead>
            <tr>
              <th>Room</th>
              <th>Guest Name</th>
              <th>Breakfast Option</th>
            </tr>
          </thead>
          <tbody>
            @forelse($breakfastGuests as $res)
              <tr>
                <td><strong>Room {{ $res->room->room_number }}</strong></td>
                <td>{{ $res->guest->name }}<br><small class="text-muted">{{ $res->guest->phone }}</small></td>
                <td>
                  @if($res->room->roomType->breakfast_included)
                    <span class="badge bg-success">Included ({{ $res->room->roomType->capacity }} Pax)</span>
                  @else
                    <span class="badge bg-warning">Optional</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="3" class="text-center text-muted py-3">No checked-in guests at the moment.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
