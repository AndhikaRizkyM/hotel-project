@extends('layouts.admin')

@section('title', 'Edit Room Type')

@section('content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-pencil" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">ROOM TYPES</p>
                <h1 class="h3 mb-1">Edit Room Type</h1>
                <p class="text-muted mb-0">Modify configuration for class <strong>{{ $roomType->name }}</strong>.</p>
            </div>
        </div>
        <div class="heading-actions">
            <a href="{{ route('master.room-types.index') }}" class="btn btn-outline-secondary btn-sm px-4 py-2"><i
                    class="bi bi-arrow-left"></i> Back</a>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="panel p-4">
                <form action="{{ route('master.room-types.update', $roomType->id) }}" method="POST"
                    class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="typeName">Room Type Name</label>
                            <input class="form-control" name="name" id="typeName" type="text"
                                value="{{ old('name', $roomType->name) }}" required>
                            <div class="invalid-feedback">Room Type name is required.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="typePrice">Price Per Night (Rp)</label>
                            <input class="form-control" name="price_per_night" id="typePrice" type="number" step="0.01"
                                value="{{ old('price_per_night', $roomType->price_per_night) }}" required>
                            <div class="invalid-feedback">Price per night is required.</div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="typeCapacity">Max Occupancy (Adults)</label>
                            <input class="form-control" name="capacity" id="typeCapacity" type="number"
                                value="{{ old('capacity', $roomType->capacity) }}" required>
                            <div class="invalid-feedback">Max capacity is required.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="typeSize">Room Size (m²)</label>
                            <input class="form-control" name="size" id="typeSize" type="number"
                                value="{{ old('size', $roomType->size) }}" required>
                            <div class="invalid-feedback">Room size is required.</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="typeFacilities">Facilities (comma separated)</label>
                        <input class="form-control" name="facilities" id="typeFacilities" type="text"
                            value="{{ old('facilities', $roomType->facilities) }}"
                            placeholder="Queen Bed, TV, AC, WiFi, Shower">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="typeDesc">Description</label>
                        <textarea class="form-control" name="description" id="typeDesc" rows="3"
                            placeholder="Enter short category description...">{{ old('description', $roomType->description) }}</textarea>
                    </div>

                    <div class="row mb-4">
                        <div class="col-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="breakfast_included" id="breakfastInc"
                                    value="1"
                                    {{ old('breakfast_included', $roomType->breakfast_included) ? 'checked' : '' }}>
                                <label class="form-check-label" for="breakfastInc">Breakfast Included by Default</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="extra_bed_available"
                                    id="extraBedAvail" value="1"
                                    {{ old('extra_bed_available', $roomType->extra_bed_available) ? 'checked' : '' }}>
                                <label class="form-check-label" for="extraBedAvail">Supports Extra Bed Request</label>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-primary" type="submit"><i class="bi bi-save"></i> Save Changes</button>
                </form>
            </div>
        </div>
    </div>
@endsection
