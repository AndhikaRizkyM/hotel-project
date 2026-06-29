@extends('layouts.admin')

@section('title', 'F&B Kitchen Dashboard')

@section('content')
<div class="page-heading">
  <div class="page-heading-copy">
    <div class="page-icon" style="border-radius: 12px; background: rgba(239, 68, 68, 0.1); color: #ef4444;"><i class="bi bi-egg-fried" aria-hidden="true"></i></div>
    <div>
      <p class="eyebrow mb-1">FOOD & BEVERAGE OPERATIONS</p>
      <h1 class="h3 mb-1 fw-bold">Kitchen & Room Service</h1>
      <p class="text-muted mb-0">Track incoming room service orders, adjust statuses, and review breakfast lists.</p>
    </div>
  </div>
</div>

<!-- Metrics row -->
<section class="row g-3" aria-label="F&B metrics">
  <div class="col-12 col-sm-6 col-xl-3">
    <article class="metric-card metric-primary panel-premium border-0 shadow-sm" style="min-height: 120px; border-radius: 14px; padding: 1.15rem;">
      <div class="metric-top">
        <span class="metric-label" style="font-weight: 700; font-size: 0.72rem;">Incoming Orders</span>
        <span class="metric-icon" style="background: rgba(37, 99, 235, 0.15); color: var(--admin-primary);"><i class="bi bi-bell" aria-hidden="true"></i></span>
      </div>
      <div class="metric-value" style="font-weight: 800; font-size: 1.75rem; margin-top: 0.5rem;">{{ $fbOrders->where('status', 'Pending')->count() }}</div>
    </article>
  </div>
  <div class="col-12 col-sm-6 col-xl-3">
    <article class="metric-card metric-warning panel-premium border-0 shadow-sm" style="min-height: 120px; border-radius: 14px; padding: 1.15rem;">
      <div class="metric-top">
        <span class="metric-label" style="font-weight: 700; font-size: 0.72rem;">Preparing</span>
        <span class="metric-icon" style="background: rgba(245, 158, 11, 0.15); color: #f59e0b;"><i class="bi bi-fire" aria-hidden="true"></i></span>
      </div>
      <div class="metric-value" style="font-weight: 800; font-size: 1.75rem; margin-top: 0.5rem;">{{ $fbOrders->where('status', 'Preparing')->count() }}</div>
    </article>
  </div>
  <div class="col-12 col-sm-6 col-xl-3">
    <article class="metric-card metric-info panel-premium border-0 shadow-sm" style="min-height: 120px; border-radius: 14px; padding: 1.15rem;">
      <div class="metric-top">
        <span class="metric-label" style="font-weight: 700; font-size: 0.72rem;">Ready for Delivery</span>
        <span class="metric-icon" style="background: rgba(6, 182, 212, 0.15); color: #06b6d4;"><i class="bi bi-check-all" aria-hidden="true"></i></span>
      </div>
      <div class="metric-value" style="font-weight: 800; font-size: 1.75rem; margin-top: 0.5rem;">{{ $fbOrders->where('status', 'Ready')->count() }}</div>
    </article>
  </div>
  <div class="col-12 col-sm-6 col-xl-3">
    <article class="metric-card metric-success panel-premium border-0 shadow-sm" style="min-height: 120px; border-radius: 14px; padding: 1.15rem;">
      <div class="metric-top">
        <span class="metric-label" style="font-weight: 700; font-size: 0.72rem;">Breakfast Guests</span>
        <span class="metric-icon" style="background: rgba(16, 185, 129, 0.15); color: #10b981;"><i class="bi bi-egg-fried" aria-hidden="true"></i></span>
      </div>
      <div class="metric-value" style="font-weight: 800; font-size: 1.75rem; margin-top: 0.5rem;">{{ $breakfastGuests->count() }}</div>
    </article>
  </div>
</section>

<!-- Active Kanban Order Board & Breakfast Summary -->
<div class="row g-3 mt-3">
  <!-- Room service orders Kanban board -->
  <div class="col-12 col-xl-9">
    <div class="panel border-0 shadow-sm h-100" style="border-radius: 14px; background: var(--admin-surface); padding: 1.25rem;">
      <div class="panel-header border-bottom pb-2 mb-3">
        <h2 class="h6 mb-0 section-title fw-bold">
          <i class="bi bi-bell" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;"></i>
          <span>Active Room Service Order Board</span>
        </h2>
      </div>

      @php
        $pendingOrders = $fbOrders->where('status', 'Pending');
        $preparingOrders = $fbOrders->where('status', 'Preparing');
        $readyOrders = $fbOrders->where('status', 'Ready');
      @endphp

      <div class="fb-kanban-board">
        <!-- COLUMN 1: PENDING -->
        <div class="fb-kanban-col">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0 text-secondary" style="font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">
              <span class="indicator-online me-2 bg-secondary" style="animation: none;"></span> Incoming Queue
            </h6>
            <span class="badge bg-secondary rounded-pill" style="font-size: 0.7rem;">{{ $pendingOrders->count() }}</span>
          </div>

          <div style="max-height: 480px; overflow-y: auto; padding-right: 2px;">
            @forelse($pendingOrders as $order)
              <div class="fb-order-card border-warning-subtle" style="border-left: 4px solid #f59e0b !important;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <div>
                    <h6 class="fw-bold mb-0" style="font-size: 0.85rem;">Room {{ $order->reservation->room->room_number }}</h6>
                    <small class="text-muted" style="font-size: 0.7rem;">#FNB-{{ $order->id }} | {{ $order->order_date->format('H:i') }}</small>
                  </div>
                  <span class="badge badge-soft-warning" style="font-size: 0.65rem;">Pending</span>
                </div>
                
                <ul class="list-group list-group-flush mb-3 p-0" style="font-size: 0.76rem;">
                  @foreach($order->items as $item)
                    <li class="list-group-item d-flex justify-content-between py-1 px-0 bg-transparent text-body border-0">
                      <span>{{ $item->qty }}x {{ $item->menu->name }}</span>
                    </li>
                  @endforeach
                </ul>

                <div class="d-flex gap-1 justify-content-end">
                  <form action="{{ route('fb.orders.status', $order->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="Preparing">
                    <button class="btn btn-warning btn-tactile btn-xs" type="submit"><i class="bi bi-play-fill"></i> Start Cook</button>
                  </form>
                  <form action="{{ route('fb.orders.status', $order->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="Cancelled">
                    <button class="btn btn-outline-danger btn-tactile btn-xs" type="submit"><i class="bi bi-x-circle"></i> Cancel</button>
                  </form>
                </div>
              </div>
            @empty
              <p class="text-muted text-center py-4 small">No pending orders.</p>
            @endforelse
          </div>
        </div>

        <!-- COLUMN 2: PREPARING -->
        <div class="fb-kanban-col">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0 text-warning" style="font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">
              <span class="indicator-online me-2 bg-warning" style="animation: breathing-pulse 2s infinite;"></span> Kitchen Cooking
            </h6>
            <span class="badge bg-warning rounded-pill" style="font-size: 0.7rem;">{{ $preparingOrders->count() }}</span>
          </div>

          <div style="max-height: 480px; overflow-y: auto; padding-right: 2px;">
            @forelse($preparingOrders as $order)
              <div class="fb-order-card border-primary-subtle" style="border-left: 4px solid var(--admin-primary) !important;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <div>
                    <h6 class="fw-bold mb-0" style="font-size: 0.85rem;">Room {{ $order->reservation->room->room_number }}</h6>
                    <small class="text-muted" style="font-size: 0.7rem;">#FNB-{{ $order->id }} | {{ $order->order_date->format('H:i') }}</small>
                  </div>
                  <span class="badge badge-soft-primary" style="font-size: 0.65rem;">Cooking</span>
                </div>
                
                <ul class="list-group list-group-flush mb-3 p-0" style="font-size: 0.76rem;">
                  @foreach($order->items as $item)
                    <li class="list-group-item d-flex justify-content-between py-1 px-0 bg-transparent text-body border-0">
                      <span>{{ $item->qty }}x {{ $item->menu->name }}</span>
                    </li>
                  @endforeach
                </ul>

                <div class="d-flex gap-1 justify-content-end">
                  <form action="{{ route('fb.orders.status', $order->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="Ready">
                    <button class="btn btn-info btn-tactile btn-xs text-white" type="submit"><i class="bi bi-check-circle-fill"></i> Done</button>
                  </form>
                  <form action="{{ route('fb.orders.status', $order->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="Cancelled">
                    <button class="btn btn-outline-danger btn-tactile btn-xs" type="submit"><i class="bi bi-x-circle"></i> Cancel</button>
                  </form>
                </div>
              </div>
            @empty
              <p class="text-muted text-center py-4 small">No items currently cooking.</p>
            @endforelse
          </div>
        </div>

        <!-- COLUMN 3: READY FOR DELIVERY -->
        <div class="fb-kanban-col">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0 text-success" style="font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">
              <span class="indicator-online me-2" style="animation: breathing-pulse 2s infinite;"></span> Ready & Wait
            </h6>
            <span class="badge bg-success rounded-pill" style="font-size: 0.7rem;">{{ $readyOrders->count() }}</span>
          </div>

          <div style="max-height: 480px; overflow-y: auto; padding-right: 2px;">
            @forelse($readyOrders as $order)
              <div class="fb-order-card border-success-subtle" style="border-left: 4px solid #10b981 !important;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <div>
                    <h6 class="fw-bold mb-0" style="font-size: 0.85rem;">Room {{ $order->reservation->room->room_number }}</h6>
                    <small class="text-muted" style="font-size: 0.7rem;">#FNB-{{ $order->id }} | {{ $order->order_date->format('H:i') }}</small>
                  </div>
                  <span class="badge badge-soft-success" style="font-size: 0.65rem;">Ready</span>
                </div>
                
                <ul class="list-group list-group-flush mb-3 p-0" style="font-size: 0.76rem;">
                  @foreach($order->items as $item)
                    <li class="list-group-item d-flex justify-content-between py-1 px-0 bg-transparent text-body border-0">
                      <span>{{ $item->qty }}x {{ $item->menu->name }}</span>
                    </li>
                  @endforeach
                </ul>

                <div class="d-flex gap-1 justify-content-end">
                  <form action="{{ route('fb.orders.status', $order->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="Delivered">
                    <button class="btn btn-success btn-tactile btn-xs" type="submit"><i class="bi bi-bicycle"></i> Deliver</button>
                  </form>
                </div>
              </div>
            @empty
              <p class="text-muted text-center py-4 small">No orders ready for delivery.</p>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Today's Breakfast guests list -->
  <div class="col-12 col-xl-3">
    <div class="panel border-0 shadow-sm h-100" style="border-radius: 14px; background: var(--admin-surface); padding: 1.25rem;">
      <div class="panel-header border-bottom pb-2 mb-3">
        <h2 class="h6 mb-0 section-title fw-bold">
          <i class="bi bi-egg-fried" style="background: rgba(16, 185, 129, 0.1); color: #10b981;"></i>
          <span>Today's Breakfast</span>
        </h2>
      </div>

      <div class="table-responsive" style="max-height: 450px; overflow-y: auto;">
        <table class="table align-middle table-sm table-hover mb-0">
          <thead>
            <tr>
              <th style="font-size: 0.72rem;">Room</th>
              <th style="font-size: 0.72rem;">Guest Name</th>
            </tr>
          </thead>
          <tbody>
            @forelse($breakfastGuests as $res)
              <tr>
                <td><strong>Room {{ $res->room->room_number }}</strong></td>
                <td>
                  <div class="fw-semibold text-body" style="font-size: 0.8rem;">{{ $res->guest->name }}</div>
                  @if($res->room->roomType->breakfast_included)
                    <span class="badge badge-soft-success" style="font-size: 0.6rem; padding: 1px 4px;">Complimentary</span>
                  @else
                    <span class="badge badge-soft-warning" style="font-size: 0.6rem; padding: 1px 4px;">Optional</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="2" class="text-center text-muted py-4" style="font-size: 0.85rem;">No guests checked-in yet.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
