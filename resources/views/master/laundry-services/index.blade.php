@extends('layouts.admin')

@block('title', 'Manage Laundry Services')

@section('content')
<div class="page-heading">
  <div class="page-heading-copy">
    <span class="page-icon"><i class="bi bi-water" aria-hidden="true"></i></span>
    <div>
      <p class="eyebrow mb-1">SYSTEM CONFIGURATION</p>
      <h1 class="h3 mb-1">Laundry Services</h1>
      <p class="text-muted mb-0">Manage services, define standard prices, and toggle service availability.</p>
    </div>
  </div>
  <div class="heading-actions">
    <a href="{{ route('master.laundry-services.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i> Add Laundry Service</a>
  </div>
</div>

<div class="panel mt-3">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead>
        <tr>
          <th>Service Name</th>
          <th>Price</th>
          <th>Status</th>
          <th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($services as $service)
          <tr>
            <td><strong>{{ $service->name }}</strong></td>
            <td>Rp {{ number_format($service->price, 0, ',', '.') }}</td>
            <td>
              <span class="badge bg-{{ $service->is_active ? 'success' : 'secondary' }}">
                {{ $service->is_active ? 'Active' : 'Disabled' }}
              </span>
            </td>
            <td class="text-end">
              <a href="{{ route('master.laundry-services.edit', $service->id) }}" class="btn btn-light btn-sm"><i class="bi bi-pencil"></i> Edit</a>
              <form action="{{ route('master.laundry-services.toggle', $service->id) }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-outline-{{ $service->is_active ? 'danger' : 'success' }} btn-sm" type="submit">
                  <i class="bi bi-power"></i> {{ $service->is_active ? 'Disable' : 'Enable' }}
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
