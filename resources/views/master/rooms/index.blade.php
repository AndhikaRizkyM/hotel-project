@extends('layouts.admin')

@section('title', 'Manage Rooms')

@section('content')
<div class="page-heading">
  <div class="page-heading-copy">
    <span class="page-icon"><i class="bi bi-door-closed" aria-hidden="true"></i></span>
    <div>
      <p class="eyebrow mb-1">SYSTEM CONFIGURATION</p>
      <h1 class="h3 mb-1">Hotel Rooms</h1>
      <p class="text-muted mb-0">Manage rooms, assign floor, assign class, and track statuses.</p>
    </div>
  </div>
  <div class="heading-actions">
    <a href="{{ route('master.rooms.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i> Create Room</a>
  </div>
</div>

<div class="panel mt-3">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead>
        <tr>
          <th>Room Number</th>
          <th>Floor</th>
          <th>Room Type</th>
          <th>Price / Night</th>
          <th>Status</th>
          <th>Active Status</th>
          <th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($rooms as $room)
          <tr>
            <td><strong>Room {{ $room->room_number }}</strong></td>
            <td>Floor {{ $room->floor }}</td>
            <td>{{ $room->roomType->name }}</td>
            <td>Rp {{ number_format($room->roomType->price_per_night, 0, ',', '.') }}</td>
            <td>
              <span class="badge bg-{{ $room->status_color }}">{{ $room->status_text }}</span>
            </td>
            <td>
              <span class="badge bg-{{ $room->is_active ? 'success' : 'secondary' }}">
                {{ $room->is_active ? 'Active' : 'Disabled' }}
              </span>
            </td>
            <td class="text-end">
              <a href="{{ route('master.rooms.edit', $room->id) }}" class="btn btn-light btn-sm"><i class="bi bi-pencil"></i> Edit</a>
              <form action="{{ route('master.rooms.toggle', $room->id) }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-outline-{{ $room->is_active ? 'danger' : 'success' }} btn-sm" type="submit">
                  <i class="bi bi-power"></i> {{ $room->is_active ? 'Disable' : 'Enable' }}
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
