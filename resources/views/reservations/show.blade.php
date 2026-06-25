@extends('layouts.admin')

@section('title', 'Folio Details - ' . $reservation->reservation_number)

@section('content')
<div class="page-heading">
  <div class="page-heading-copy">
    <span class="page-icon"><i class="bi bi-receipt" aria-hidden="true"></i></span>
    <div>
      <p class="eyebrow mb-1">GUEST FOLIO LEDGER</p>
      <h1 class="h3 mb-1">Statement: {{ $reservation->reservation_number }}</h1>
      <p class="text-muted mb-0">Manage guest statements, add room service orders, deposits, and process final payments.</p>
    </div>
  </div>
  <div class="heading-actions d-flex gap-2">
    <a href="{{ route('fo.print-registration', $reservation->id) }}" target="_blank" class="btn btn-outline-secondary btn-sm"><i class="bi bi-printer"></i> Reg Card</a>
    <a href="{{ route('fo.print-invoice', $reservation->id) }}" target="_blank" class="btn btn-outline-primary btn-sm"><i class="bi bi-file-earmark-spreadsheet"></i> Print Invoice</a>
    <a href="{{ route('fo.reservations.index') }}" class="btn btn-light btn-sm"><i class="bi bi-arrow-left"></i> Back to History</a>
  </div>
</div>

<div class="row g-4">
  <!-- Guest & Stay details -->
  <div class="col-12 col-xl-4">
    <div class="panel mb-4 shadow-sm">
      <h5 class="fw-bold border-bottom pb-2 mb-3"><i class="bi bi-person-badge text-primary"></i> Guest Profile</h5>
      <table class="table table-sm table-borderless small mb-0">
        <tr><td class="fw-semibold" style="width: 35%;">Guest Name:</td><td>{{ $reservation->guest->name }}</td></tr>
        <tr><td class="fw-semibold">NIK/Passport:</td><td>{{ $reservation->guest->id_number }}</td></tr>
        <tr><td class="fw-semibold">Nationality:</td><td>{{ $reservation->guest->country }}</td></tr>
        <tr><td class="fw-semibold">Contact No:</td><td>{{ $reservation->guest->phone }}</td></tr>
        <tr><td class="fw-semibold">Email:</td><td>{{ $reservation->guest->email ?? '-' }}</td></tr>
        <tr><td class="fw-semibold">Vehicle Plate:</td><td>{{ $reservation->guest->vehicle_no ?? '-' }}</td></tr>
      </table>
    </div>

    <div class="panel mb-4 shadow-sm">
      <h5 class="fw-bold border-bottom pb-2 mb-3"><i class="bi bi-door-open text-primary"></i> Booking Stay</h5>
      <table class="table table-sm table-borderless small mb-0">
        <tr><td class="fw-semibold" style="width: 35%;">Room Number:</td><td><strong class="text-primary">Room {{ $reservation->room->room_number }}</strong></td></tr>
        <tr><td class="fw-semibold">Room Type:</td><td>{{ $reservation->room->roomType->name }}</td></tr>
        <tr><td class="fw-semibold">Check-in:</td><td><span class="badge badge-soft-secondary">{{ $reservation->check_in_date }}</span></td></tr>
        <tr><td class="fw-semibold">Check-out:</td><td><span class="badge badge-soft-secondary">{{ $reservation->check_out_date }}</span></td></tr>
        <tr>
          <td class="fw-semibold">Status:</td>
          <td>
            @php
              $statusColors = ['RSV' => 'info', 'CI' => 'success', 'CO' => 'secondary', 'CAN' => 'danger', 'NS' => 'warning'];
              $statusNames = ['RSV' => 'Reserved', 'CI' => 'Checked In', 'CO' => 'Checked Out', 'CAN' => 'Cancelled', 'NS' => 'No Show'];
            @endphp
            <span class="badge badge-soft-{{ $statusColors[$reservation->status] ?? 'light' }}">{{ $statusNames[$reservation->status] ?? $reservation->status }}</span>
          </td>
        </tr>
      </table>

      @if($reservation->status === 'RSV')
        <div class="mt-3 border-top pt-3 d-flex gap-1">
          <form action="{{ route('fo.reservations.check-in', $reservation->id) }}" method="POST" class="w-100">
            @csrf
            <button type="submit" class="btn btn-success btn-sm w-100"><i class="bi bi-check-circle"></i> Check-in Guest</button>
          </form>
        </div>
      @endif
    </div>

    <!-- Deposits section -->
    <div class="panel shadow-sm">
      <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
        <h5 class="fw-bold mb-0"><i class="bi bi-piggy-bank text-primary"></i> Cash Deposits</h5>
        @if($reservation->status === 'CI' || $reservation->status === 'RSV')
          <button type="button" class="btn btn-outline-primary btn-xs" data-bs-toggle="modal" data-bs-target="#depositModal"><i class="bi bi-plus"></i> Add</button>
        @endif
      </div>
      <div class="table-responsive">
        <table class="table table-sm align-middle small mb-0">
          <thead>
            <tr>
              <th>Date</th>
              <th>Type</th>
              <th>Method</th>
              <th class="text-end">Amount</th>
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
                <td>{{ \Carbon\Carbon::parse($tx->transaction_date)->format('d/m H:i') }}</td>
                <td>
                  <span class="badge badge-soft-{{ $tx->type === 'payment' ? 'success' : 'danger' }}">{{ ucfirst($tx->type) }}</span>
                </td>
                <td>{{ $tx->payment_method }}</td>
                <td class="text-end fw-bold {{ $tx->type === 'payment' ? 'text-success' : 'text-danger' }}">
                  Rp{{ number_format($tx->amount, 0, ',', '.') }}
                </td>
              </tr>
            @empty
              <tr><td colspan="4" class="text-center text-muted">No deposits logged.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Folio Statement ledger -->
  <div class="col-12 col-xl-8">
    <div class="panel shadow-sm mb-4">
      <div class="panel-header border-bottom pb-2 mb-3">
        <h2 class="h5 mb-0 section-title"><i class="bi bi-list-stars text-primary"></i><span>Guest Folio Ledger Statement</span></h2>
      </div>

      <div class="table-responsive">
        <table class="table table-striped align-middle table-sm small">
          <thead>
            <tr>
              <th>Date</th>
              <th>Category</th>
              <th>Description</th>
              <th class="text-end">Amount</th>
            </tr>
          </thead>
          <tbody>
            @php
              $totalCharges = 0;
            @endphp
            @if($reservation->folio && $reservation->folio->items)
              @foreach($reservation->folio->items as $item)
                @php $totalCharges += $item->amount; @endphp
                <tr>
                  <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                  <td><span class="badge badge-soft-secondary">{{ $item->item_type }}</span></td>
                  <td>{{ $item->description }}</td>
                  <td class="text-end fw-semibold">Rp{{ number_format($item->amount, 0, ',', '.') }}</td>
                </tr>
              @endforeach
            @else
              <tr>
                <td colspan="4" class="text-center text-muted py-4">Folio has not been generated. Guest must be Checked In first.</td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>

      <!-- Balance Calculator Summary -->
      @if($reservation->folio)
        <div class="row g-2 mt-3 pt-3 border-top justify-content-end text-end small">
          <div class="col-8 col-md-5">Total Charges:</div>
          <div class="col-4 col-md-3 fw-bold">Rp{{ number_format($totalCharges, 0, ',', '.') }}</div>
        </div>
        <div class="row g-2 justify-content-end text-end small">
          <div class="col-8 col-md-5">Total Deposits & Paid:</div>
          <div class="col-4 col-md-3 text-success fw-bold">Rp{{ number_format($totPay, 0, ',', '.') }}</div>
        </div>
        @if($totRef > 0)
          <div class="row g-2 justify-content-end text-end small">
            <div class="col-8 col-md-5">Total Refunds:</div>
            <div class="col-4 col-md-3 text-danger fw-bold">-Rp{{ number_format($totRef, 0, ',', '.') }}</div>
          @endif
        </div>
        @php
          $outstanding = $totalCharges - ($totPay - $totRef);
        @endphp
        <div class="row g-2 justify-content-end text-end h6 mt-2 pt-2 border-top">
          <div class="col-8 col-md-5 fw-bold">Outstanding Balance:</div>
          <div class="col-4 col-md-3 fw-bold text-{{ $outstanding > 0.01 ? 'danger' : ($outstanding < -0.01 ? 'success' : 'dark') }}">
            @if($outstanding > 0.01)
              Rp{{ number_format($outstanding, 0, ',', '.') }}
            @elseif($outstanding < -0.01)
              Refund Due: Rp{{ number_format(abs($outstanding), 0, ',', '.') }}
            @else
              Settled (Rp0)
            @endif
          </div>
        </div>
      @endif
    </div>

    <!-- Active Folio Guest Services Forms -->
    @if($reservation->status === 'CI' && $reservation->folio)
      <div class="row g-3">
        <!-- 1. Extra Bed Request Form -->
        <div class="col-12 col-md-6">
          <div class="panel border shadow-xs h-100">
            <h6 class="fw-bold border-bottom pb-2 mb-2"><i class="bi bi-box-fill text-primary"></i> Order Extra Bed</h6>
            @if(!$reservation->room->roomType->extra_bed_available)
              <div class="alert alert-warning py-2 mb-0 small">Extra Bed is not supported for this room type ({{ $reservation->room->roomType->name }}).</div>
            @else
              <form action="{{ route('fo.reservations.extrabed', $reservation->id) }}" method="POST">
                @csrf
                <div class="row g-2 align-items-end">
                  <div class="col-8">
                    <label for="qty" class="form-label small fw-bold">Quantity (Bed)</label>
                    <select name="qty" id="qty" class="form-select form-select-sm" required>
                      <option value="1">1 Extra Bed (Rp150.000 / Night)</option>
                      <option value="2">2 Extra Beds (Rp300.000 / Night)</option>
                    </select>
                  </div>
                  <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-sm w-100">Add Bed</button>
                  </div>
                </div>
              </form>
            @endif
          </div>
        </div>

        <!-- 2. Room Service Food Order (POS Style) -->
        <div class="col-12 col-md-6">
          <div class="panel border shadow-xs h-100 d-flex flex-column">
            <h6 class="fw-bold border-bottom pb-2 mb-2"><i class="bi bi-egg-fried text-primary"></i> F&B Room Service POS</h6>
            
            <!-- Quick Selection Form -->
            <div class="row g-2 align-items-end mb-3">
              <div class="col-7">
                <label for="pos_menu_id" class="form-label small fw-bold">Select Menu Item</label>
                <select id="pos_menu_id" class="form-select form-select-sm">
                  <option value="">Choose item...</option>
                  @foreach($fbMenus as $menu)
                    <option value="{{ $menu->id }}" data-price="{{ $menu->price }}" data-name="{{ $menu->name }}">
                      {{ $menu->name }} (Rp{{ number_format($menu->price, 0, ',', '.') }})
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-5">
                <label for="pos_qty" class="form-label small fw-bold">Qty</label>
                <div class="input-group input-group-sm">
                  <input type="number" id="pos_qty" value="1" min="1" class="form-control">
                  <button type="button" class="btn btn-secondary btn-sm" id="btn-add-to-cart"><i class="bi bi-plus-lg"></i> Add</button>
                </div>
              </div>
            </div>

            <!-- Cart Table -->
            <form action="{{ route('fo.reservations.fb-order', $reservation->id) }}" method="POST" class="flex-grow-1 d-flex flex-column" id="pos-form">
              @csrf
              <div class="table-responsive flex-grow-1 mb-2" style="max-height: 180px; min-height: 120px; overflow-y: auto;">
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
                      <td colspan="5" class="text-center text-muted py-3">Cart is empty. Add food or drinks above.</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Cart Total and Submit Button -->
              <div class="border-top pt-2 mt-auto">
                <div class="d-flex justify-content-between align-items-center mb-2 small fw-bold">
                  <span>Cart Total:</span>
                  <span id="pos-cart-total">Rp0</span>
                </div>
                <button type="submit" class="btn btn-primary btn-sm w-100" id="btn-submit-order" disabled>
                  <i class="bi bi-cart-check"></i> Place Room Service Order
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- 3. Laundry Order Form -->
        <div class="col-12">
          <div class="panel border shadow-xs">
            <h6 class="fw-bold border-bottom pb-2 mb-2"><i class="bi bi-water text-primary"></i> Laundry Requisition Form</h6>
            <form action="{{ route('fo.reservations.laundry-order', $reservation->id) }}" method="POST">
              @csrf
              <div class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                  <label for="laundry_service_id" class="form-label small fw-bold">Service Type</label>
                  <select name="laundry_service_id" id="laundry_service_id" class="form-select form-select-sm" required>
                    <option value="">Choose service...</option>
                    @foreach($laundryServices as $svc)
                      <option value="{{ $svc->id }}">{{ $svc->name }} (Rp{{ number_format($svc->price, 0, ',', '.') }}/pcs)</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-6 col-md-4">
                  <label for="item_name" class="form-label small fw-bold">Item Description</label>
                  <input type="text" name="items[0][name]" id="item_name" class="form-control form-control-sm" required placeholder="e.g. Kemeja, Kaos, Celana">
                </div>
                <div class="col-6 col-md-4">
                  <label for="ld_qty" class="form-label small fw-bold">Qty (pcs)</label>
                  <div class="input-group input-group-sm">
                    <input type="number" name="items[0][qty]" id="ld_qty" value="1" min="1" class="form-control" required>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i> Add Laundry</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- 4. Folio Checkout Settlement Panel -->
        <div class="col-12" id="checkout-section">
          <div class="panel border-danger shadow-xs panel-soft">
            <h6 class="fw-bold border-bottom pb-2 mb-3 text-danger"><i class="bi bi-box-arrow-right"></i> Check-Out Settlement Panel</h6>
            
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
                    <select name="payment_method" class="form-select form-select-sm" required>
                      <option value="">Payment Method...</option>
                      <option value="Cash">Cash</option>
                      <option value="Debit Card">Debit Card</option>
                      <option value="Credit Card">Credit Card</option>
                      <option value="QRIS">QRIS</option>
                      <option value="Transfer">Bank Transfer</option>
                    </select>
                  @endif
                  <button type="submit" class="btn btn-danger btn-sm text-nowrap w-100">
                    <i class="bi bi-lock-fill"></i> Complete Check-Out
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    @endif
  </div>
</div>

<!-- Modal Cash Deposit -->
<div class="modal fade" id="depositModal" tabindex="-1" aria-labelledby="depositModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="depositModalLabel"><i class="bi bi-cash-coin text-success"></i> Add Cash Deposit</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('fo.reservations.deposit', $reservation->id) }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label for="deposit_amount" class="form-label small fw-bold">Deposit Amount (IDR)</label>
            <input type="number" name="amount" id="deposit_amount" min="1000" class="form-control form-control-sm" required placeholder="e.g. 200000">
          </div>
          <div class="mb-3">
            <label for="payment_method" class="form-label small fw-bold">Payment Method</label>
            <select name="payment_method" id="payment_method" class="form-select form-select-sm" required>
              <option value="Cash">Cash</option>
              <option value="Debit Card">Debit Card</option>
              <option value="Credit Card">Credit Card</option>
              <option value="QRIS">QRIS</option>
              <option value="Transfer">Bank Transfer</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="deposit_notes" class="form-label small fw-bold">Notes (Optional)</label>
            <textarea name="notes" id="deposit_notes" rows="2" class="form-control form-control-sm" placeholder="e.g. Security deposit or key card deposit"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success btn-sm">Process Deposit</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
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
        
        // Check if item already in cart, if so, increase qty
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
        
        // Reset inputs
        selectMenu.value = '';
        inputQty.value = '1';
        
        renderCart();
      });
    }
    
    function renderCart() {
      // Clear rows
      cartBody.innerHTML = '';
      
      if (cartItems.length === 0) {
        cartBody.innerHTML = `
          <tr class="cart-empty-row">
            <td colspan="5" class="text-center text-muted py-3">Cart is empty. Add food or drinks above.</td>
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
          <td class="text-end fw-semibold">Rp${formatNumber(subtotal)}</td>
          <td class="text-center">
            <button type="button" class="btn btn-outline-danger btn-xs py-0 px-1 btn-remove-item" data-index="${index}">
              <i class="bi bi-trash"></i>
            </button>
          </td>
        `;
        cartBody.appendChild(row);
      });
      
      cartTotalEl.textContent = 'Rp' + formatNumber(grandTotal);
      btnSubmit.disabled = false;
      
      // Add event listeners to remove buttons
      document.querySelectorAll('.btn-remove-item').forEach(btn => {
        btn.addEventListener('click', function() {
          const index = parseInt(this.getAttribute('data-index'));
          cartItems.splice(index, 1);
          renderCart();
        });
      });
    }
    
    function formatNumber(num) {
      return num.toLocaleString('id-ID');
    }
  });
</script>
@endpush
