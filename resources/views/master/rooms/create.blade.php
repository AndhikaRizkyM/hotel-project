@extends('layouts.admin')

@section('title', 'Create Room')

@section('content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-plus-circle" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">HOTEL ROOMS</p>
                <h1 class="h3 mb-1">Create Room</h1>
                <p class="text-muted mb-0">Register a new room unit in the system.</p>
            </div>
        </div>
        <div class="heading-actions">
            <a href="{{ route('master.rooms.index') }}" class="btn btn-outline-secondary btn-sm px-4 py-2"><i
                    class="bi bi-arrow-left"></i> Back</a>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="panel p-4">
                <form action="{{ route('master.rooms.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="roomNumber">Room Number</label>
                        <input class="form-control" name="room_number" id="roomNumber" type="text"
                            placeholder="e.g. 101, 204" required>
                        <div class="invalid-feedback">Room number is required.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="roomFloor">Floor</label>
                        <input class="form-control" name="floor" id="roomFloor" type="number" min="1"
                            placeholder="e.g. 1, 2, 3" required>
                        <div class="invalid-feedback">Floor number is required.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="roomTypeId">Room Class / Type</label>
                        <select class="form-select" name="room_type_id" id="roomTypeId" required>
                            <option value="" disabled selected>Select Room Type</option>
                            @foreach ($roomTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}
                                    (Rp{{ number_format($type->price_per_night, 0, ',', '.') }}/night)
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Please select a room type.</div>
                    </div>

                    <button class="btn btn-primary" type="submit"><i class="bi bi-save"></i> Save Room</button>
                </form>
            </div>
        </div>
    </div>
@endsection
