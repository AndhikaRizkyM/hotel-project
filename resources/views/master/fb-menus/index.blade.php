@extends('layouts.admin')

@block('title', 'Manage F&B Menu')

@section('content')
<div class="page-heading">
  <div class="page-heading-copy">
    <span class="page-icon"><i class="bi bi-egg-fried" aria-hidden="true"></i></span>
    <div>
      <p class="eyebrow mb-1">SYSTEM CONFIGURATION</p>
      <h1 class="h3 mb-1">Restaurant Menu</h1>
      <p class="text-muted mb-0">Manage food and beverage items, edit pricing, and toggle item availability.</p>
    </div>
  </div>
  <div class="heading-actions">
    <a href="{{ route('master.fb-menus.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i> Add Menu Item</a>
  </div>
</div>

<div class="panel mt-3">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead>
        <tr>
          <th>Item Name</th>
          <th>Type</th>
          <th>Price</th>
          <th>Status</th>
          <th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($menus as $menu)
          <tr>
            <td><strong>{{ $menu->name }}</strong></td>
            <td>
              <span class="badge bg-{{ $menu->type === 'food' ? 'primary' : 'info' }}">{{ strtoupper($menu->type) }}</span>
            </td>
            <td>Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
            <td>
              <span class="badge bg-{{ $menu->is_active ? 'success' : 'secondary' }}">
                {{ $menu->is_active ? 'Available' : 'Unavailable' }}
              </span>
            </td>
            <td class="text-end">
              <a href="{{ route('master.fb-menus.edit', $menu->id) }}" class="btn btn-light btn-sm"><i class="bi bi-pencil"></i> Edit</a>
              <form action="{{ route('master.fb-menus.toggle', $menu->id) }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-outline-{{ $menu->is_active ? 'danger' : 'success' }} btn-sm" type="submit">
                  <i class="bi bi-power"></i> {{ $menu->is_active ? 'Deactivate' : 'Activate' }}
                </button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
