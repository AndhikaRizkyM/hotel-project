@extends('layouts.admin')

@section('title', 'Add Laundry Service')

@section('content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-plus-circle" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">LAUNDRY SERVICES</p>
                <h1 class="h3 mb-1">Add Laundry Service</h1>
                <p class="text-muted mb-0">Register a new laundry category and price structure.</p>
            </div>
        </div>
        <div class="heading-actions">
            <a href="{{ route('master.laundry-services.index') }}" class="btn btn-outline-secondary btn-sm px-4 py-2"><i
                    class="bi bi-arrow-left"></i> Back</a>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="panel p-4">
                <form action="{{ route('master.laundry-services.store') }}" method="POST" class="needs-validation"
                    novalidate>
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="serviceName">Service Name</label>
                        <input class="form-control" name="name" id="serviceName" type="text"
                            placeholder="e.g. Dry Clean, Wash & Iron" required>
                        <div class="invalid-feedback">Service name is required.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="servicePrice">Price (Rp)</label>
                        <input class="form-control" name="price" id="servicePrice" type="number" placeholder="25000"
                            required>
                        <div class="invalid-feedback">Price is required.</div>
                    </div>

                    <button class="btn btn-primary" type="submit"><i class="bi bi-save"></i> Save Service</button>
                </form>
            </div>
        </div>
    </div>
@endsection
