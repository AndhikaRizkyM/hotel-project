@extends('layouts.admin')

@section('title', 'Manage Room Types')

@section('content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-grid-3x3-gap" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">SYSTEM CONFIGURATION</p>
                <h1 class="h3 mb-1">Room Types</h1>
                <p class="text-muted mb-0">Define room classes, pricing, occupancy limits, and breakfast/extra-bed
                    parameters.</p>
            </div>
        </div>
        <div class="heading-actions">
            <a href="{{ route('master.room-types.create') }}" class="btn btn-primary btn-sm px-4 py-2"><i
                    class="bi bi-plus-circle"></i> Create Room Type</a>
        </div>
    </div>

    <div class="panel mt-3">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Capacity</th>
                        <th>Size</th>
                        <th>Price / Night</th>
                        <th>Breakfast</th>
                        <th>Extra Bed</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roomTypes as $type)
                        <tr>
                            <td><strong>{{ $type->name }}</strong><br><small
                                    class="text-muted">{{ Str::limit($type->description, 50) }}</small></td>
                            <td>{{ $type->capacity }} Adults</td>
                            <td>{{ $type->size }} m²</td>
                            <td>Rp {{ number_format($type->price_per_night, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-{{ $type->breakfast_included ? 'success' : 'secondary' }}">
                                    {{ $type->breakfast_included ? 'Included' : 'Optional' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $type->extra_bed_available ? 'success' : 'secondary' }}">
                                    {{ $type->extra_bed_available ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td>
                                <span
                                    class="badge bg-{{ $type->status === 'active' ? 'success' : 'danger' }}">{{ ucfirst($type->status) }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('master.room-types.edit', $type->id) }}" class="btn btn-light btn-sm"><i
                                        class="bi bi-pencil"></i> Edit</a>
                                <form action="{{ route('master.room-types.destroy', $type->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm" type="submit"
                                        onclick="return confirm('Are you sure you want to delete this room type?')">
                                        <i class="bi bi-trash"></i> Delete
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
