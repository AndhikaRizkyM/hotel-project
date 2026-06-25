@extends('layouts.admin')

@section('title', 'Folio Details - ' . $reservation->reservation_number)

@section('content')
<div class="page-heading">
  <div class="page-heading-copy">
    <div class="page-icon" style="border-radius: 12px; background: rgba(37, 99, 235, 0.1); color: var(--admin-primary);"><i class="bi bi-receipt" aria-hidden="true"></i></div>
    <div>
      <p class="eyebrow mb-1">GUEST FOLIO LEDGER</p>
      <h1 class="h3 mb-1 fw-bold">Statement: {{ $reservation->reservation_number }}</h1>
      <p class="text-muted mb-0">Manage guest statements, add room service orders, deposits, and process final payments.</p>
    </div>
  </div>
  <div class="heading-actions d-flex gap-2">
    <a href="{{ route('fo.print-registration', $reservation->id) }}" target="_blank" class="btn btn-outline-secondary btn-sm btn-tactile"><i class="bi bi-printer"></i> Reg Card</a>
    <a href="{{ route('fo.print-invoice', $reservation->id) }}" target="_blank" class="btn btn-outline-primary btn-sm btn-tactile"><i class="bi bi-file-earmark-spreadsheet"></i> Print Invoice</a>
    <a href="{{ route('fo.reservations.index') }}" class="btn btn-light btn-sm btn-tactile"><i class="bi bi-arrow-left"></i> Back</a>
  </div>
</div>

<div class="row g-4">
  <!-- Guest & Stay details -->
  <div class="col-12 col-xl-4">
    <!-- Guest Profile Card -->
    <div class="panel mb-4 shadow-sm border-0" style="border-radius: 14px; background: var(--admin-surface);">
      <h5 class="fw-bold border-bottom pb-2 mb-3 section-title h6">
        <i class="bi bi-person-badge" style="background: rgba(37, 99, 235, 0.1); color: var(--admin-primary);"></i>
        <span>Guest Profile</span>
      </h5>
      <table class="table table-sm table-borderless small mb-0 text-body">
        <tr><td class="fw-bold text-muted" style="width: 38%;">Guest Name:</td><td class="fw-semibold">{{ $reservation->guest->name }}</td></tr>
        <tr><td class="fw-bold text-muted">NIK/Passport:</td><td class="fw-semibold">{{ $reservation->guest->id_number }}</td></tr>
        <tr><td class="fw-bold text-muted">Nationality:</td><td class="fw-semibold">{{ $reservation->guest->country }}</td></tr>
        <tr><td class="fw-bold text-muted">Contact No:</td><td class="fw-semibold">{{ $reservation->guest->phone }}</td></tr>
        <tr><td class="fw-bold text-muted">Email:</td><td class="fw-semibold">{{ $reservation->guest->email ?? '-' }}</td></tr>
        <tr><td class="fw-bold text-muted">Vehicle Plate:</td><td class="fw-semibold">{{ $reservation->guest->vehicle_no ?? '-' }}</td></tr>
      </table>
    </div>

    <!-- Booking Stay Card -->
    <div class="panel mb-4 shadow-sm border-0" style="border-radius: 14px; background: var(--admin-surface);">
      <h5 class="fw-bold border-bottom pb-2 mb-3 section-title h6">
        <i class="bi bi-door-open" style="background: rgba(37, 99, 235, 0.1); color: var(--admin-primary);"></i>
        <span>Booking Stay</span>
      </h5>
      <table class="table table-sm table-borderless small mb-0 text-body">
        <tr><td class="fw-bold text-muted" style="width: 38%;">Room Number:</td><td><strong class="text-primary">Room {{ $reservation->room->room_number }}</strong></td></tr>
        <tr><td class="fw-bold text-muted">Room Type:</td><td class="fw-semibold">{{ $reservation->room->roomType->name }}</td></tr>
        <tr><td class="fw-bold text-muted">Check-in:</td><td><span class="badge badge-soft-secondary">{{ $reservation->check_in_date->format('d M Y') }}</span></td></tr>
        <tr><td class="fw-bold text-muted">Check-out:</td><td><span class="badge badge-soft-secondary">{{ $reservation->check_out_date->format('d M Y') }}</span></td></tr>
        <tr>
          <td class="fw-bold text-muted">Status:</td>
          <td>
            @php
              $statusColors = ['RSV' => 'info', 'CI' => 'success', 'CO' => 'secondary', 'CAN' => 'danger', 'NS' => 'warning'];
              $statusNames = ['RSV' => 'Reserved', 'CI' => 'Checked In', 'CO' => 'Checked Out', 'CAN' => 'Cancelled', 'NS' => 'No Show'];
            @endphp
            <span class="badge badge-soft-{{ $statusColors[$reservation->status] ?? 'light' }} room-status-badge">{{ $statusNames[$reservation->status] ?? $reservation->status }}</span>
          </td>
        </tr>
        @if($reservation->status !== 'RSV' && $reservation->guarantee_type)
          <tr>
            <td class="fw-bold text-muted">Guarantee:</td>
            <td>
              <span class="badge bg-light text-dark border fw-bold">{{ $reservation->guarantee_type }}</span>
              @if($reservation->guarantee_detail)
                <div class="text-muted small mt-1">{{ $reservation->guarantee_detail }}</div>
              @endif
            </td>
          </tr>
        @endif
      </table>

      @if($reservation->status === 'RSV')
        <div class="mt-3 border-top pt-3 d-flex flex-column gap-2">
          @php
            $hasPaid = $outstanding <= 0.01;
          @endphp

          <div class="d-flex align-items-center justify-content-between p-2 rounded {{ $hasPaid ? 'alert-success' : 'alert-warning' }} mb-1 border-0" style="padding: 0.5rem 0.75rem !important;">
            <span class="small fw-bold">
              @if($hasPaid)
                <i class="bi bi-check-circle-fill"></i> Fully Paid (Ready)
              @else
                <i class="bi bi-exclamation-circle-fill"></i> Unpaid (Outstanding: Rp{{ number_format($outstanding, 0, ',', '.') }})
              @endif
            </span>
          </div>

          <button type="button" class="btn btn-success btn-tactile btn-sm w-100 py-2" data-bs-toggle="modal" data-bs-target="#checkInModal">
            <i class="bi bi-door-open-fill"></i> Process Check-In
          </button>
        </div>
      @endif
    </div>

    <!-- Breakfast Tracker Card -->
    <div class="panel mb-4 shadow-sm border-0" style="border-radius: 14px; background: var(--admin-surface);">
      <h5 class="fw-bold border-bottom pb-2 mb-3 section-title h6">
        <i class="bi bi-egg-fried" style="background: rgba(37, 99, 235, 0.1); color: var(--admin-primary);"></i>
        <span>Breakfast Tracker</span>
      </h5>
      @if($reservation->breakfastRecords && $reservation->breakfastRecords->isNotEmpty())
        <div class="list-group list-group-flush small text-body">
          @foreach($reservation->breakfastRecords as $br)
            <div class="list-group-item px-0 py-2 bg-transparent border-0 border-bottom">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="fw-bold text-secondary">{{ $br->date->format('d M Y') }}</span>
                @php
                  $brColors = ['Pending' => 'secondary', 'Preparing' => 'warning', 'Delivered' => 'success', 'Skipped' => 'danger'];
                @endphp
                <span class="badge badge-soft-{{ $brColors[$br->status] ?? 'light' }} room-status-badge">{{ $br->status }}</span>
              </div>
              @if($br->timeline && is_array($br->timeline))
                <div class="ps-1 my-1">
                  @foreach($br->timeline as $index => $step)
                    <div class="breakfast-timeline-item {{ $index === count($br->timeline) - 1 ? 'active-step' : 'done-step' }}">
                      <span class="fw-bold text-body" style="font-size: 0.7rem;">{{ \Carbon\Carbon::parse($step['time'])->format('H:i') }}</span>
                      <span class="text-muted">: {{ $step['status'] }}</span>
                      <span class="text-muted font-monospace" style="font-size: 0.68rem;">({{ $step['user'] ?? 'Staff' }})</span>
                    </div>
                  @endforeach
                </div>
              @endif
              @if($br->notes)
                <div class="text-xs text-muted mt-2 italic" style="font-size: 0.72rem;"><i class="bi bi-chat-left-text me-1"></i> "{{ $br->notes }}"</div>
              @endif
            </div>
          @endforeach
        </div>
      @else
        <p class="text-muted small mb-0">No breakfast tracking logged yet for this stay.</p>
      @endif
    </div>

    <!-- Payments Card -->
    <div class="panel shadow-sm border-0" style="border-radius: 14px; background: var(--admin-surface);">
      <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
        <h5 class="fw-bold mb-0 section-title h6">
          <i class="bi bi-piggy-bank" style="background: rgba(37, 99, 235, 0.1); color: var(--admin-primary);"></i>
          <span>Payments Ledger</span>
        </h5>
        @if($reservation->status === 'CI' || $reservation->status === 'RSV')
          <button type="button" class="btn btn-outline-primary btn-tactile btn-xs rounded-pill px-2" data-bs-toggle="modal" data-bs-target="#depositModal"><i class="bi bi-plus"></i> Add</button>
        @endif
      </div>
      <div class="table-responsive">
        <table class="table table-sm align-middle small mb-0">
          <thead>
            <tr>
              <th style="font-size: 0.72rem;">Date</th>
              <th style="font-size: 0.72rem;">Type</th>
              <th style="font-size: 0.72rem;">Method</th>
              <th style="font-size: 0.72rem;" class="text-end">Amount</th>
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
                <td><small class="fw-semibold text-muted">{{ \Carbon\Carbon::parse($tx->transaction_date)->format('d/m H:i') }}</small></td>
                <td>
                  <span class="badge badge-soft-{{ $tx->type === 'payment' ? 'success' : 'danger' }} room-status-badge">{{ ucfirst($tx->type) }}</span>
                </td>
                <td><span class="badge bg-light text-dark border small" style="font-size: 0.65rem;">{{ $tx->payment_method }}</span></td>
                <td class="text-end fw-bold {{ $tx->type === 'payment' ? 'text-success' : 'text-danger' }}">
                  Rp{{ number_format($tx->amount, 0, ',', '.') }}
                </td>
              </tr>
            @empty
              <tr><td colspan="4" class="text-center text-muted py-3">No payments or deposits logged.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Folio Statement ledger & Active POS forms -->
  <div class="col-12 col-xl-8">
    <!-- Ledger Card -->
    <div class="panel shadow-sm mb-4 border-0" style="border-radius: 14px; background: var(--admin-surface); padding: 1.25rem;">
      <div class="panel-header border-bottom pb-2 mb-3">
        <h2 class="h6 mb-0 section-title fw-bold">
          <i class="bi bi-list-stars" style="background: rgba(37, 99, 235, 0.1); color: var(--admin-primary);"></i>
          <span>Guest Folio Ledger Statement</span>
        </h2>
      </div>

      <div class="table-responsive">
        <table class="table align-middle table-sm table-hover mb-0">
          <thead>
            <tr>
              <th style="width: 18%; font-size: 0.72rem; letter-spacing: 0.5px;">Date</th>
              <th style="width: 25%; font-size: 0.72rem; letter-spacing: 0.5px;">Category</th>
              <th style="width: 42%; font-size: 0.72rem; letter-spacing: 0.5px;">Description</th>
              <th style="width: 15%; font-size: 0.72rem; letter-spacing: 0.5px;" class="text-end">Amount</th>
            </tr>
          </thead>
          <tbody>
            @php
              $totalCharges = 0;
              $catBadges = [
                'Room Charge' => ['color' => 'primary', 'icon' => 'door-closed'],
                'Food & Beverage' => ['color' => 'warning', 'icon' => 'egg-fried'],
                'Laundry' => ['color' => 'info', 'icon' => 'water'],
                'Extra Bed' => ['color' => 'secondary', 'icon' => 'box-fill'],
                'Damage Charge' => ['color' => 'danger', 'icon' => 'tools'],
                'Lost Item Charge' => ['color' => 'danger', 'icon' => 'exclamation-triangle'],
                'Miscellaneous Charge' => ['color' => 'secondary', 'icon' => 'receipt']
              ];
            @endphp
            @if($reservation->folio && $reservation->folio->items)
              @foreach($reservation->folio->items as $item)
                @php 
                  $totalCharges += $item->amount; 
                  $badge = $catBadges[$item->item_type] ?? ['color' => 'secondary', 'icon' => 'file-text'];
                @endphp
                <tr>
                  <td><small class="text-muted fw-semibold">{{ $item->created_at->format('d M Y H:i') }}</small></td>
                  <td>
                    <span class="badge badge-soft-{{ $badge['color'] }} room-status-badge d-inline-flex align-items-center gap-1">
                      <i class="bi bi-{{ $badge['icon'] }}"></i> {{ $item->item_type }}
                    </span>
                  </td>
                  <td>{{ $item->description }}</td>
                  <td class="text-end fw-bold">Rp{{ number_format($item->amount, 0, ',', '.') }}</td>
                </tr>
              @endforeach
            @else
              <tr>
                <td colspan="4" class="text-center text-muted py-5" style="font-size: 0.85rem;">Folio has not been generated. Guest must be Checked In first.</td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>

      <!-- Balance Calculator Summary -->
      @if($reservation->folio)
        <div class="row g-2 mt-3 pt-3 border-top justify-content-end text-end small text-body">
          <div class="col-8 col-md-5 text-muted fw-semibold">Total Folio Charges:</div>
          <div class="col-4 col-md-3 fw-bold">Rp{{ number_format($totalCharges, 0, ',', '.') }}</div>
        </div>
        <div class="row g-2 justify-content-end text-end small text-body">
          <div class="col-8 col-md-5 text-muted fw-semibold">Total Deposits & Settled Paid:</div>
          <div class="col-4 col-md-3 text-success fw-bold">Rp{{ number_format($totPay, 0, ',', '.') }}</div>
        </div>
        @if($totRef > 0)
          <div class="row g-2 justify-content-end text-end small text-body">
            <div class="col-8 col-md-5 text-muted fw-semibold">Total Refunds Returned:</div>
            <div class="col-4 col-md-3 text-danger fw-bold">-Rp{{ number_format($totRef, 0, ',', '.') }}</div>
          </div>
        @endif
        @php
          $outstanding = $totalCharges - ($totPay - $totRef);
        @endphp
        <div class="row g-2 justify-content-end text-end h6 mt-2 pt-2 border-top">
          <div class="col-8 col-md-5 fw-bold text-body">Outstanding Statement Balance:</div>
          <div class="col-4 col-md-3 fw-bold">
            @if($outstanding > 0.01)
              <span class="badge badge-soft-danger px-3 py-2" style="font-size: 0.95rem; border-radius: 8px;">Rp{{ number_format($outstanding, 0, ',', '.') }}</span>
            @elseif($outstanding < -0.01)
              <span class="badge badge-soft-success px-3 py-2" style="font-size: 0.95rem; border-radius: 8px;">Refund Due: Rp{{ number_format(abs($outstanding), 0, ',', '.') }}</span>
            @else
              <span class="badge badge-soft-secondary px-3 py-2" style="font-size: 0.95rem; border-radius: 8px;">Settled (Rp0)</span>
            @endif
          </div>
        </div>
      @endif
    </div>

    <!-- Active Folio Guest Services Forms (Unified Tabs) -->
    @if($reservation->status === 'CI' && $reservation->folio)
      <div class="panel shadow-sm border-0 mb-4" style="border-radius: 14px; background: var(--admin-surface); padding: 1.25rem;">
        <h5 class="fw-bold border-bottom pb-2 mb-3 section-title h6">
          <i class="bi bi-plus-circle" style="background: rgba(37, 99, 235, 0.1); color: var(--admin-primary);"></i>
          <span>Add Guest Services Requisition</span>
        </h5>

        <!-- Service Tabs -->
        <ul class="nav nav-pills nav-fill flex-nowrap overflow-auto pb-2 mb-3" id="serviceTabs" role="tablist" style="-webkit-overflow-scrolling: touch; white-space: nowrap; gap: 0.5rem;">
          <li class="nav-item" role="presentation">
            <button class="nav-link py-2 px-3 fw-bold btn-tactile active" id="fb-service-tab" data-bs-toggle="tab" data-bs-target="#tab-fb-service" type="button" role="tab" aria-selected="true" style="font-size: 0.82rem; border-radius: 20px;">
              <i class="bi bi-egg-fried me-1"></i> F&B Room Service
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link py-2 px-3 fw-bold btn-tactile" id="laundry-service-tab" data-bs-toggle="tab" data-bs-target="#tab-laundry-service" type="button" role="tab" aria-selected="false" style="font-size: 0.82rem; border-radius: 20px;">
              <i class="bi bi-water me-1"></i> Laundry Order
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link py-2 px-3 fw-bold btn-tactile" id="extrabed-service-tab" data-bs-toggle="tab" data-bs-target="#tab-extrabed-service" type="button" role="tab" aria-selected="false" style="font-size: 0.82rem; border-radius: 20px;">
              <i class="bi bi-box-fill me-1"></i> Extra Bed
            </button>
          </li>
        </ul>

        <div class="tab-content" id="serviceTabsContent">
          <!-- TAB 1: FOOD & BEVERAGE SERVICE -->
          <div class="tab-pane fade show active" id="tab-fb-service" role="tabpanel" aria-labelledby="fb-service-tab">
            <div class="row g-3">
              <div class="col-12 col-md-5">
                <div class="p-3 bg-light-subtle rounded border mb-2" style="border-radius: 10px !important;">
                  <h6 class="fw-bold mb-2 small text-secondary">Add Menu Items</h6>
                  <div class="mb-2">
                    <label for="pos_menu_id" class="form-label small fw-bold">Select Menu Item</label>
                    <select id="pos_menu_id" class="form-select form-select-sm" style="border-radius: 8px;">
                      <option value="">Choose item...</option>
                      @foreach($fbMenus as $menu)
                        <option value="{{ $menu->id }}" data-price="{{ $menu->price }}" data-name="{{ $menu->name }}">
                          {{ $menu->name }} (Rp{{ number_format($menu->price, 0, ',', '.') }})
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="pos_qty" class="form-label small fw-bold">Quantity</label>
                    <div class="input-group input-group-sm">
                      <input type="number" id="pos_qty" value="1" min="1" class="form-control" style="border-radius: 8px 0 0 8px;">
                      <button type="button" class="btn btn-secondary btn-tactile btn-sm" id="btn-add-to-cart"><i class="bi bi-plus-lg"></i> Add</button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-12 col-md-7">
                <form action="{{ route('fo.reservations.fb-order', $reservation->id) }}" method="POST" class="d-flex flex-column h-100" id="pos-form">
                  @csrf
                  <div class="table-responsive mb-2" style="max-height: 180px; min-height: 120px; overflow-y: auto;">
                    <table class="table table-sm align-middle small mb-0" id="pos-cart-table">
                      <thead>
                        <tr class="table-light">
                          <th>Item</th>
                          <th class="text-end">Price</th>
                          <th class="text-center">Qty</th>
                          <th class="text-end">Subtotal</th>
                          <th class="text-center"></th>
                        </tr>
                      </thead>
                      <tbody id="pos-cart-body">
                        <tr class="cart-empty-row">
                          <td colspan="5" class="text-center text-muted py-4">Cart is empty. Add food or drinks.</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <div class="border-top pt-2 mt-auto d-flex justify-content-between align-items-center">
                    <div class="small fw-bold">
                      <span>Total: </span><span id="pos-cart-total" class="text-primary">Rp0</span>
                    </div>
                    <button type="submit" class="btn btn-primary btn-tactile btn-sm px-4" id="btn-submit-order" disabled style="border-radius: 8px;">
                      <i class="bi bi-cart-check"></i> Place F&B Order
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!-- TAB 2: LAUNDRY SERVICE -->
          <div class="tab-pane fade" id="tab-laundry-service" role="tabpanel" aria-labelledby="laundry-service-tab">
            <div class="row g-3">
              <div class="col-12 col-md-5">
                <div class="p-3 bg-light-subtle rounded border mb-2" style="border-radius: 10px !important;">
                  <h6 class="fw-bold mb-2 small text-secondary">Add Laundry Service</h6>
                  <div class="mb-2">
                    <label for="laundry_service_id" class="form-label small fw-bold mb-1">Select Service</label>
                    <select id="laundry_service_id" class="form-select form-select-sm" style="border-radius: 8px;">
                      <option value="">Choose service...</option>
                      @foreach($laundryServices as $svc)
                        <option value="{{ $svc->id }}" data-price="{{ $svc->price }}" data-name="{{ $svc->name }}">
                          {{ $svc->name }} (Rp{{ number_format($svc->price, 0, ',', '.') }}/pcs)
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="mb-2">
                    <label for="laundry_item_name" class="form-label small fw-bold mb-1">Item Description</label>
                    <input type="text" id="laundry_item_name" class="form-control form-control-sm" placeholder="e.g. Kaos, Celana Jeans" style="border-radius: 8px;">
                  </div>
                  <div class="mb-3">
                    <label for="laundry_qty" class="form-label small fw-bold mb-1">Quantity</label>
                    <div class="input-group input-group-sm">
                      <input type="number" id="laundry_qty" value="1" min="1" class="form-control" style="border-radius: 8px 0 0 8px;">
                      <button type="button" class="btn btn-secondary btn-tactile btn-sm" id="btn-add-laundry"><i class="bi bi-plus-lg"></i> Add</button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-12 col-md-7">
                <form action="{{ route('fo.reservations.laundry-order', $reservation->id) }}" method="POST" class="d-flex flex-column h-100" id="laundry-pos-form">
                  @csrf
                  <input type="hidden" name="laundry_service_id" id="hidden_laundry_service_id" value="">
                  <div class="table-responsive mb-2" style="max-height: 180px; min-height: 120px; overflow-y: auto;">
                    <table class="table table-sm align-middle small mb-0" id="laundry-cart-table">
                      <thead>
                        <tr class="table-light">
                          <th>Item Description</th>
                          <th class="text-end">Price</th>
                          <th class="text-center">Qty</th>
                          <th class="text-end">Subtotal</th>
                          <th class="text-center"></th>
                        </tr>
                      </thead>
                      <tbody id="laundry-cart-body">
                        <tr class="laundry-cart-empty-row">
                          <td colspan="5" class="text-center text-muted py-4">Cart is empty. Add laundry items.</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <div class="border-top pt-2 mt-auto d-flex justify-content-between align-items-center">
                    <div class="small fw-bold">
                      <span>Total: </span><span id="laundry-cart-total" class="text-primary">Rp0</span>
                    </div>
                    <button type="submit" class="btn btn-primary btn-tactile btn-sm px-4" id="btn-submit-laundry" disabled style="border-radius: 8px;">
                      <i class="bi bi-cart-check"></i> Settle Laundry
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!-- TAB 3: EXTRA BED SERVICE -->
          <div class="tab-pane fade" id="tab-extrabed-service" role="tabpanel" aria-labelledby="extrabed-service-tab">
            <div class="p-3 bg-light-subtle rounded border" style="border-radius: 10px !important;">
              <h6 class="fw-bold mb-2 small text-secondary">Extra Bed Configuration</h6>
              @if(!$reservation->room->roomType->extra_bed_available)
                <div class="alert alert-warning py-2 mb-0 border-0 small" style="border-left: 4px solid var(--admin-warning) !important;">
                  <i class="bi bi-exclamation-triangle-fill"></i> Extra Bed is not supported for room type ({{ $reservation->room->roomType->name }}).
                </div>
              @else
                <form action="{{ route('fo.reservations.extrabed', $reservation->id) }}" method="POST">
                  @csrf
                  <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-8">
                      <label for="qty" class="form-label small fw-bold">Select Quantity</label>
                      <select name="qty" id="qty" class="form-select form-select-sm" required style="border-radius: 8px;">
                        <option value="1">1 Extra Bed (Rp150.000 / Night)</option>
                        <option value="2">2 Extra Beds (Rp300.000 / Night)</option>
                      </select>
                    </div>
                    <div class="col-12 col-md-4">
                      <button type="submit" class="btn btn-primary btn-tactile btn-sm w-100 py-2" style="border-radius: 8px;"><i class="bi bi-plus-circle"></i> Add Extra Bed</button>
                    </div>
                  </div>
                </form>
              @endif
            </div>
          </div>
        </div>
      </div>

      <!-- Active Laundry Orders and Room Inspections List -->
      <div class="row g-3 mb-4">
        <!-- Active Laundry Orders -->
        <div class="col-12">
          <div class="panel shadow-sm border-0" style="border-radius: 14px; background: var(--admin-surface); padding: 1.25rem;">
            <h5 class="fw-bold border-bottom pb-2 mb-2 section-title h6">
              <i class="bi bi-water" style="background: rgba(37, 99, 235, 0.1); color: var(--admin-primary);"></i>
              <span>Active Guest Laundry Orders</span>
            </h5>
            <div class="table-responsive">
              <table class="table table-sm align-middle table-hover small mb-0">
                <thead>
                  <tr>
                    <th style="font-size: 0.72rem;">Order Ref</th>
                    <th style="font-size: 0.72rem;">Service Type</th>
                    <th style="font-size: 0.72rem;">Items</th>
                    <th style="font-size: 0.72rem;" class="text-end">Total Amount</th>
                    <th style="font-size: 0.72rem;">Status</th>
                    <th style="font-size: 0.72rem;" class="text-end">Order Date</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($reservation->laundryOrders as $ldr)
                    <tr>
                      <td class="fw-bold">#LDR-{{ $ldr->id }}</td>
                      <td><span class="badge bg-light text-dark border fw-bold">{{ $ldr->service->name }}</span></td>
                      <td>
                        <ul class="m-0 ps-3 small text-body">
                          @foreach($ldr->items as $item)
                            <li>{{ $item->item_name }} (x{{ $item->qty }})</li>
                          @endforeach
                        </ul>
                      </td>
                      <td class="text-end fw-bold text-primary">Rp{{ number_format($ldr->total_amount, 0, ',', '.') }}</td>
                      <td>
                        @php
                          $ldrColors = [
                            'Pending' => 'danger',
                            'Collected' => 'warning',
                            'Washing' => 'info',
                            'Ready' => 'primary',
                            'Delivered' => 'success',
                            'Cancelled' => 'secondary'
                          ];
                        @endphp
                        <span class="badge badge-soft-{{ $ldrColors[$ldr->status] ?? 'secondary' }} room-status-badge">{{ $ldr->status }}</span>
                      </td>
                      <td class="text-end text-muted"><small class="fw-semibold">{{ $ldr->order_date ? \Carbon\Carbon::parse($ldr->order_date)->timezone('Asia/Jakarta')->format('d M Y H:i') : '-' }}</small></td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="6" class="text-center text-muted py-3" style="font-size: 0.8rem;">No laundry orders logged.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Room Inspection & Pre-Checkout Issues Review -->
        <div class="col-12">
          <div class="panel shadow-sm border-0 panel-soft p-4" style="border-radius: 14px;">
            <h5 class="fw-bold border-bottom pb-2 mb-3 text-info section-title h6">
              <i class="bi bi-clipboard-check" style="background: rgba(6, 182, 212, 0.1); color: #06b6d4;"></i>
              <span>Pre-Checkout Room Inspection & Issues Review</span>
            </h5>
            
            @php
              $activeInspectionTask = \App\Models\HousekeepingTask::where('reservation_id', $reservation->id)
                  ->where('task_type', 'inspection')
                  ->latest()
                  ->first();
              
              $unresolvedDamages = \App\Models\DamageReport::where('reservation_id', $reservation->id)->where('status', 'pending')->get();
              $unresolvedLost = \App\Models\LostFoundReport::where('reservation_id', $reservation->id)->where('status', 'lost')->get();
              
              $isInspectionComplete = $activeInspectionTask && $activeInspectionTask->status === 'completed';
              $hasPendingIssues = $unresolvedDamages->isNotEmpty() || $unresolvedLost->isNotEmpty();
              
              $canCheckOut = $isInspectionComplete && !$hasPendingIssues;
            @endphp

            <div class="row g-3 align-items-center">
              <div class="col-12 col-md-8">
                <!-- Inspection Status Banner -->
                @if(!$activeInspectionTask)
                  <div class="alert alert-warning py-2 mb-0 small border-0" style="border-left: 4px solid var(--admin-warning) !important;">
                    <i class="bi bi-exclamation-triangle-fill"></i> <strong>Notice:</strong> Room inspection has not been requested yet. Please request inspection before checking out the guest.
                  </div>
                @elseif($activeInspectionTask->status === 'pending' || $activeInspectionTask->status === 'cleaning')
                  <div class="alert alert-info py-2 mb-0 small border-0" style="border-left: 4px solid var(--admin-primary) !important;">
                    <i class="bi bi-clock-history"></i> <strong>Under Inspection:</strong> Room inspection request sent to Housekeeping (Attendant: {{ $activeInspectionTask->assignedTo->name ?? 'Unassigned' }}). Waiting for completion.
                  </div>
                @elseif($activeInspectionTask->status === 'ready_for_inspection')
                  <div class="alert alert-primary py-2 mb-0 small border-0" style="border-left: 4px solid var(--admin-primary) !important;">
                    <i class="bi bi-eye-fill"></i> <strong>Pending Quality Control:</strong> Housekeeping completed room inspection. Pending Quality Control (QC) verification.
                  </div>
                @else
                  <div class="alert alert-success py-2 mb-0 small border-0" style="border-left: 4px solid #10b981 !important;">
                    <i class="bi bi-check-circle-fill"></i> <strong>Inspection Completed:</strong> Room inspection report successfully submitted by Housekeeping.
                  </div>
                @endif
              </div>
              <div class="col-12 col-md-4 text-end">
                @if(!$activeInspectionTask)
                  <form action="{{ route('fo.reservations.request-inspection', $reservation->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-info btn-tactile text-white btn-sm w-100 py-2" style="border-radius: 8px;"><i class="bi bi-send"></i> Request Room Inspection</button>
                  </form>
                @endif
              </div>
            </div>

            <!-- Issues list and FO action buttons -->
            @if($activeInspectionTask && ($reservation->damageReports->isNotEmpty() || $reservation->lostFoundReports->isNotEmpty()))
              <div class="mt-3 border-top pt-3">
                <h6 class="fw-bold text-danger mb-2 small"><i class="bi bi-exclamation-octagon"></i> Reported Room Damage & Lost Items</h6>
                <div class="table-responsive">
                  <table class="table table-sm align-middle table-hover small mb-0">
                    <thead>
                      <tr>
                        <th style="font-size: 0.72rem;">Type</th>
                        <th style="font-size: 0.72rem;">Item Description</th>
                        <th style="font-size: 0.72rem;">Estimated Cost</th>
                        <th style="font-size: 0.72rem;">Status</th>
                        <th style="font-size: 0.72rem;" class="text-center">Front Office Action (Negotiation)</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- Damage Reports -->
                      @foreach($reservation->damageReports as $dmg)
                        <tr>
                          <td><span class="badge badge-soft-danger room-status-badge">Damage</span></td>
                          <td><strong>{{ $dmg->item_name }}</strong><br><span class="text-muted" style="font-size: 0.7rem;">{{ $dmg->description }}</span></td>
                          <td class="fw-bold">Rp{{ number_format($dmg->estimated_cost, 0, ',', '.') }}</td>
                          <td>
                            @if($dmg->status === 'pending')
                              <span class="badge badge-soft-warning room-status-badge">Pending Review</span>
                            @elseif($dmg->is_charged_to_folio)
                              <span class="badge badge-soft-success room-status-badge">Charged</span>
                            @else
                              <span class="badge badge-soft-secondary room-status-badge">Waived</span>
                            @endif
                          </td>
                          <td class="text-center">
                            @if($dmg->status === 'pending')
                              <div class="d-flex justify-content-center gap-1">
                                <form action="{{ route('fo.reservations.damage-process', [$reservation->id, $dmg->id, 'charge']) }}" method="POST">
                                  @csrf
                                  <button type="submit" class="btn btn-danger btn-tactile btn-xs">Charge</button>
                                </form>
                                <form action="{{ route('fo.reservations.damage-process', [$reservation->id, $dmg->id, 'waive']) }}" method="POST">
                                  @csrf
                                  <button type="submit" class="btn btn-outline-secondary btn-tactile btn-xs">Waive</button>
                                </form>
                              </div>
                            @else
                              <span class="text-muted small">Processed</span>
                            @endif
                          </td>
                        </tr>
                      @endforeach

                      <!-- Lost Items -->
                      @foreach($reservation->lostFoundReports as $lf)
                        <tr>
                          <td><span class="badge badge-soft-warning room-status-badge">Lost Item</span></td>
                          <td><strong>{{ $lf->item_description }}</strong><br><span class="text-muted" style="font-size: 0.7rem;">Found at: {{ $lf->location_found }}</span></td>
                          <td class="fw-bold">-</td>
                          <td>
                            @if($lf->status === 'lost')
                              <span class="badge badge-soft-warning room-status-badge">Pending Review</span>
                            @else
                              @php
                                $isCharged = $reservation->folio && $reservation->folio->items()->where('item_type', 'Lost Item Charge')->where('reference_id', $lf->id)->exists();
                              @endphp
                              @if($isCharged)
                                <span class="badge badge-soft-success room-status-badge">Charged</span>
                              @else
                                <span class="badge badge-soft-secondary room-status-badge">Waived</span>
                              @endif
                            @endif
                          </td>
                          <td class="text-center">
                            @if($lf->status === 'lost')
                              <div class="d-flex justify-content-center align-items-center gap-1">
                                <form action="{{ route('fo.reservations.lost-process', [$reservation->id, $lf->id, 'charge']) }}" method="POST" class="d-inline-flex align-items-center gap-1">
                                  @csrf
                                  <input type="number" name="charge_amount" class="form-control form-control-xs py-0" style="max-width: 90px; height: 24px; font-size: 0.75rem;" placeholder="Penalty..." required min="0" value="100000">
                                  <button type="submit" class="btn btn-danger btn-tactile btn-xs">Charge</button>
                                </form>
                                <form action="{{ route('fo.reservations.lost-process', [$reservation->id, $lf->id, 'waive']) }}" method="POST">
                                  @csrf
                                  <button type="submit" class="btn btn-outline-secondary btn-tactile btn-xs">Waive</button>
                                </form>
                              </div>
                            @else
                              <span class="text-muted small">Processed</span>
                            @endif
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            @endif
          </div>
        </div>

        <!-- Folio Checkout Settlement Panel -->
        <div class="col-12" id="checkout-section">
          @if($canCheckOut)
            <div class="panel border-0 shadow-sm panel-soft p-4" style="border-radius: 14px; border: 1px solid rgba(239, 68, 68, 0.1) !important;">
              <h5 class="fw-bold border-bottom pb-2 mb-3 text-danger section-title h6">
                <i class="bi bi-box-arrow-right" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;"></i>
                <span>Check-Out Settlement Panel</span>
              </h5>

              <form action="{{ route('fo.reservations.check-out', $reservation->id) }}" method="POST">
                @csrf
                <div class="row g-3 align-items-center">
                  <div class="col-12 col-md-7">
                    <p class="mb-0 text-muted small">
                      @if($outstanding > 0.01)
                        An outstanding balance of <strong>Rp{{ number_format($outstanding, 0, ',', '.') }}</strong> remains. Select the payment method to process final settlement.
                      @elseif($outstanding < -0.01)
                        A refund of <strong>Rp{{ number_format(abs($outstanding), 0, ',', '.') }}</strong> is due. Please refund the guest and complete checkout.
                      @else
                        The bill is fully settled. You may check out the guest directly.
                      @endif
                    </p>
                  </div>
                  <div class="col-12 col-md-5 d-flex gap-2">
                    @if(abs($outstanding) > 0.01)
                      <select name="payment_method" class="form-select form-select-sm" required style="border-radius: 8px;">
                        <option value="">Payment Method...</option>
                        <option value="Cash">Cash</option>
                        <option value="Debit Card">Debit Card</option>
                        <option value="Credit Card">Credit Card</option>
                        <option value="QRIS">QRIS</option>
                        <option value="Transfer">Bank Transfer</option>
                      </select>
                    @endif
                    <button type="submit" class="btn btn-danger btn-tactile btn-sm text-nowrap w-100 py-2" style="border-radius: 8px;">
                      <i class="bi bi-lock-fill"></i> Complete Check-Out
                    </button>
                  </div>
                </div>
              </form>
            </div>
          @else
            <div class="alert alert-danger shadow-sm border-0 py-3 mb-0" style="border-left: 4px solid #ef4444 !important; border-radius: 12px;">
              <h6 class="fw-bold mb-1"><i class="bi bi-lock-fill text-danger"></i> Checkout Process Locked</h6>
              <p class="small mb-0">Please request and complete a <strong>Room Inspection</strong> by Housekeeping first. Ensure all reported damages or lost items are resolved (charged or waived) by the Front Office before final settlement.</p>
            </div>
          @endif
        </div>
      </div>
    @endif
  </div>
</div>

<!-- Modal Cash Deposit -->
<div class="modal fade" id="depositModal" tabindex="-1" aria-labelledby="depositModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg border-0" style="border-radius: 14px;">
      <div class="modal-header border-bottom">
        <h5 class="modal-title fw-bold" id="depositModalLabel"><i class="bi bi-cash-coin text-success"></i> Add Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('fo.reservations.deposit', $reservation->id) }}" method="POST">
        @csrf
        <div class="modal-body p-4">
          <div class="mb-3">
            <label for="deposit_amount" class="form-label small fw-bold">Payment Amount (IDR)</label>
            <input type="number" name="amount" id="deposit_amount" min="1000" class="form-control form-control-sm" required placeholder="e.g. 200000" style="border-radius: 8px;">
          </div>
          <div class="mb-3">
            <label for="payment_method" class="form-label small fw-bold">Payment Method</label>
            <select name="payment_method" id="payment_method" class="form-select form-select-sm" required style="border-radius: 8px;">
              <option value="Cash">Cash</option>
              <option value="Debit Card">Debit Card</option>
              <option value="Credit Card">Credit Card</option>
              <option value="QRIS">QRIS</option>
              <option value="Transfer">Bank Transfer</option>
            </select>
          </div>
          <div class="mb-0">
            <label for="deposit_notes" class="form-label small fw-bold">Notes (Optional)</label>
            <textarea name="notes" id="deposit_notes" rows="2" class="form-control form-control-sm" placeholder="e.g. Security deposit or key card deposit" style="border-radius: 8px;"></textarea>
          </div>
        </div>
        <div class="modal-footer border-top">
          <button type="button" class="btn btn-light btn-sm btn-tactile" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success btn-sm btn-tactile">Process Deposit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Unified Modal for Check-In & Settlement -->
<div class="modal fade" id="checkInModal" tabindex="-1" aria-labelledby="checkInModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg border-0" style="border-radius: 14px;">
      <div class="modal-header border-bottom">
        <h5 class="modal-title fw-bold" id="checkInModalLabel"><i class="bi bi-door-open-fill text-success"></i> Guest Check-In & Settlement</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('fo.reservations.check-in', $reservation->id) }}" method="POST">
        @csrf
        <div class="modal-body text-start p-4">
          <div class="p-3 bg-light rounded border mb-3 text-body" style="border-radius: 10px !important;">
            <h6 class="fw-bold mb-2 text-secondary small">Reservation Summary</h6>
            <div class="d-flex justify-content-between small mb-1">
              <span>Room Number:</span>
              <strong class="text-primary">Room {{ $reservation->room->room_number }}</strong>
            </div>
            <div class="d-flex justify-content-between small mb-1">
              <span>Guest Name:</span>
              <strong class="text-body">{{ $reservation->guest->name }}</strong>
            </div>
            <div class="d-flex justify-content-between small mb-1">
              <span>Total Charge:</span>
              <strong class="text-body">Rp{{ number_format($reservation->total_charge, 0, ',', '.') }}</strong>
            </div>
            <div class="d-flex justify-content-between small mb-1">
              <span>Amount Paid:</span>
              <strong class="text-success">Rp{{ number_format($totalPaid, 0, ',', '.') }}</strong>
            </div>
            <hr class="my-2">
            <div class="d-flex justify-content-between fw-bold">
              <span>Outstanding Balance:</span>
              <strong class="{{ $outstanding > 0.01 ? 'text-danger' : 'text-success' }}">
                Rp{{ number_format(max(0, $outstanding), 0, ',', '.') }}
              </strong>
            </div>
          </div>

          <!-- Payment Section: only show if outstanding balance exists -->
          @if($outstanding > 0.01)
            <div class="mb-3 border p-3 rounded bg-light-subtle" style="border-radius: 10px !important;">
              <h6 class="fw-bold text-danger mb-2 small"><i class="bi bi-cash-coin"></i> Settle Outstanding Payment</h6>
              <div class="mb-2">
                <label for="settle_amount" class="form-label small fw-bold">Settlement Amount (IDR)</label>
                <input type="number" name="amount" id="settle_amount" min="{{ $outstanding }}" value="{{ $outstanding }}" class="form-control form-control-sm" required style="border-radius: 8px;">
                <div class="form-text text-muted text-xs">Pre-filled with the outstanding balance. Check-in requires full payment.</div>
              </div>
              <div class="mb-0">
                <label for="settle_method" class="form-label small fw-bold">Payment Method</label>
                <select name="payment_method" id="settle_method" class="form-select form-select-sm" required style="border-radius: 8px;">
                  <option value="Cash">Cash</option>
                  <option value="Debit Card">Debit Card</option>
                  <option value="Credit Card">Credit Card</option>
                  <option value="QRIS">QRIS</option>
                  <option value="Transfer">Bank Transfer</option>
                </select>
              </div>
            </div>
          @endif

          <!-- Guest Guarantee Section -->
          <div class="mb-0 border p-3 rounded bg-light-subtle" style="border-radius: 10px !important;">
            <h6 class="fw-bold text-primary mb-2 small"><i class="bi bi-shield-check"></i> Guest Guarantee</h6>
            <div class="mb-3">
              <label for="guarantee_type_select" class="form-label small fw-bold">Guarantee Type</label>
              <select name="guarantee_type" id="guarantee_type_select" class="form-select form-select-sm" required onchange="toggleGuaranteeInputs(this.value)" style="border-radius: 8px;">
                <option value="">Select Guarantee...</option>
                <option value="Identity Card">Identity Card (ID / Passport)</option>
                <option value="Cash">Cash Deposit</option>
              </select>
            </div>
            
            <!-- Card Number Input -->
            <div class="mb-0 d-none" id="guarantee_card_container">
              <label for="guarantee_card_number" class="form-label small fw-bold">ID / Passport Number</label>
              <input type="text" name="guarantee_card_number" id="guarantee_card_number" class="form-control form-control-sm" placeholder="e.g. KTP - 3171XXXXXXXX or Passport No" value="{{ $reservation->guest->id_number }}" style="border-radius: 8px;">
            </div>

            <!-- Cash Guarantee Input -->
            <div class="mb-0 d-none" id="guarantee_cash_container">
              <label for="guarantee_cash_amount" class="form-label small fw-bold">Cash Deposit Amount (IDR)</label>
              <input type="number" name="guarantee_cash_amount" id="guarantee_cash_amount" min="0" value="200000" class="form-control form-control-sm" placeholder="e.g. 200000" style="border-radius: 8px;">
            </div>
          </div>
        </div>
        <div class="modal-footer border-top">
          <button type="button" class="btn btn-light btn-sm btn-tactile" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success btn-sm btn-tactile">Settle & Check-In</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // 1. Food POS Cart
    const btnAdd = document.getElementById('btn-add-to-cart');
    const selectMenu = document.getElementById('pos_menu_id');
    const inputQty = document.getElementById('pos_qty');
    const cartBody = document.getElementById('pos-cart-body');
    const cartTotalEl = document.getElementById('pos-cart-total');
    const btnSubmit = document.getElementById('btn-submit-order');

    let cartItems = [];

    if (btnAdd) {
      btnAdd.addEventListener('click', function() {
        const selectedOption = selectMenu.options[selectMenu.selectedIndex];
        if (!selectedOption.value) {
          alert('Please select a menu item first.');
          return;
        }

        const menuId = selectedOption.value;
        const menuName = selectedOption.getAttribute('data-name');
        const price = parseFloat(selectedOption.getAttribute('data-price'));
        const qty = parseInt(inputQty.value);

        if (qty < 1 || isNaN(qty)) {
          alert('Quantity must be at least 1.');
          return;
        }

        const existingItem = cartItems.find(item => item.menuId === menuId);
        if (existingItem) {
          existingItem.qty += qty;
        } else {
          cartItems.push({
            menuId: menuId,
            name: menuName,
            price: price,
            qty: qty
          });
        }

        selectMenu.value = '';
        inputQty.value = '1';

        renderCart();
      });
    }

    function renderCart() {
      cartBody.innerHTML = '';

      if (cartItems.length === 0) {
        cartBody.innerHTML = `
          <tr class="cart-empty-row">
            <td colspan="5" class="text-center text-muted py-4">Cart is empty. Add food or drinks.</td>
          </tr>
        `;
        cartTotalEl.textContent = 'Rp0';
        btnSubmit.disabled = true;
        return;
      }

      let grandTotal = 0;

      cartItems.forEach((item, index) => {
        const subtotal = item.price * item.qty;
        grandTotal += subtotal;

        const row = document.createElement('tr');
        row.innerHTML = `
          <td>
            <strong>${item.name}</strong>
            <input type="hidden" name="items[${index}][menu_id]" value="${item.menuId}">
            <input type="hidden" name="items[${index}][qty]" value="${item.qty}">
          </td>
          <td class="text-end">Rp${formatNumber(item.price)}</td>
          <td class="text-center">${item.qty}</td>
          <td class="text-end fw-bold">Rp${formatNumber(subtotal)}</td>
          <td class="text-center">
            <button type="button" class="btn btn-outline-danger btn-tactile btn-xs py-0 px-2 btn-remove-item" data-index="${index}">
              <i class="bi bi-trash"></i>
            </button>
          </td>
        `;
        cartBody.appendChild(row);
      });

      cartTotalEl.textContent = 'Rp' + formatNumber(grandTotal);
      btnSubmit.disabled = false;

      document.querySelectorAll('.btn-remove-item').forEach(btn => {
        btn.addEventListener('click', function() {
          const index = parseInt(this.getAttribute('data-index'));
          cartItems.splice(index, 1);
          renderCart();
        });
      });
    }

    // 2. Laundry POS Cart
    const btnAddLaundry = document.getElementById('btn-add-laundry');
    const selectLaundryService = document.getElementById('laundry_service_id');
    const inputLaundryItemName = document.getElementById('laundry_item_name');
    const inputLaundryQty = document.getElementById('laundry_qty');
    const laundryCartBody = document.getElementById('laundry-cart-body');
    const laundryCartTotalEl = document.getElementById('laundry-cart-total');
    const btnSubmitLaundry = document.getElementById('btn-submit-laundry');
    const hiddenLaundryServiceId = document.getElementById('hidden_laundry_service_id');

    let laundryItems = [];

    if (btnAddLaundry) {
      btnAddLaundry.addEventListener('click', function() {
        const selectedOption = selectLaundryService.options[selectLaundryService.selectedIndex];
        if (!selectedOption.value) {
          alert('Please select a laundry service first.');
          return;
        }

        const serviceId = selectedOption.value;
        const serviceName = selectedOption.getAttribute('data-name');
        const price = parseFloat(selectedOption.getAttribute('data-price'));
        const itemName = inputLaundryItemName.value.trim();
        const qty = parseInt(inputLaundryQty.value);

        if (!itemName) {
          alert('Please input item description (e.g. Kaos, Celana).');
          return;
        }

        if (qty < 1 || isNaN(qty)) {
          alert('Quantity must be at least 1.');
          return;
        }

        if (laundryItems.length > 0 && laundryItems[0].serviceId !== serviceId) {
          alert('You can only add items for the same laundry service type in one order.');
          return;
        }

        hiddenLaundryServiceId.value = serviceId;

        const existingItem = laundryItems.find(item => item.name.toLowerCase() === itemName.toLowerCase());
        if (existingItem) {
          existingItem.qty += qty;
        } else {
          laundryItems.push({
            serviceId: serviceId,
            serviceName: serviceName,
            price: price,
            name: itemName,
            qty: qty
          });
        }

        selectLaundryService.disabled = true;
        inputLaundryItemName.value = '';
        inputLaundryQty.value = '1';

        renderLaundryCart();
      });
    }

    function renderLaundryCart() {
      laundryCartBody.innerHTML = '';

      if (laundryItems.length === 0) {
        laundryCartBody.innerHTML = `
          <tr class="laundry-cart-empty-row">
            <td colspan="5" class="text-center text-muted py-4">Cart is empty. Add laundry items.</td>
          </tr>
        `;
        laundryCartTotalEl.textContent = 'Rp0';
        btnSubmitLaundry.disabled = true;
        selectLaundryService.disabled = false;
        hiddenLaundryServiceId.value = '';
        return;
      }

      let grandTotal = 0;

      laundryItems.forEach((item, index) => {
        const subtotal = item.price * item.qty;
        grandTotal += subtotal;

        const row = document.createElement('tr');
        row.innerHTML = `
          <td>
            <strong>${item.name}</strong>
            <input type="hidden" name="items[${index}][name]" value="${item.name}">
            <input type="hidden" name="items[${index}][qty]" value="${item.qty}">
          </td>
          <td class="text-end">Rp${formatNumber(item.price)}</td>
          <td class="text-center">${item.qty}</td>
          <td class="text-end fw-bold">Rp${formatNumber(subtotal)}</td>
          <td class="text-center">
            <button type="button" class="btn btn-outline-danger btn-tactile btn-xs py-0 px-2 btn-remove-laundry-item" data-index="${index}">
              <i class="bi bi-trash"></i>
            </button>
          </td>
        `;
        laundryCartBody.appendChild(row);
      });

      laundryCartTotalEl.textContent = 'Rp' + formatNumber(grandTotal);
      btnSubmitLaundry.disabled = false;

      document.querySelectorAll('.btn-remove-laundry-item').forEach(btn => {
        btn.addEventListener('click', function() {
          const index = parseInt(this.getAttribute('data-index'));
          laundryItems.splice(index, 1);
          renderLaundryCart();
        });
      });
    }

    function formatNumber(num) {
      return num.toLocaleString('id-ID');
    }
  });

  function toggleGuaranteeInputs(type) {
    const cardContainer = document.getElementById('guarantee_card_container');
    const cashContainer = document.getElementById('guarantee_cash_container');
    const cardInput = document.getElementById('guarantee_card_number');
    const cashInput = document.getElementById('guarantee_cash_amount');

    if (type === 'Identity Card') {
      cardContainer.classList.remove('d-none');
      cashContainer.classList.add('d-none');
      cardInput.required = true;
      cardInput.disabled = false;
      cashInput.required = false;
      cashInput.disabled = true;
    } else if (type === 'Cash') {
      cardContainer.classList.add('d-none');
      cashContainer.classList.remove('d-none');
      cardInput.required = false;
      cardInput.disabled = true;
      cashInput.required = true;
      cashInput.disabled = false;
    } else {
      cardContainer.classList.add('d-none');
      cashContainer.classList.add('d-none');
      cardInput.required = false;
      cardInput.disabled = true;
      cashInput.required = false;
      cashInput.disabled = true;
    }
  }
</script>
@endpush
