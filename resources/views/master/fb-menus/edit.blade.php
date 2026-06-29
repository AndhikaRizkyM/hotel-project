@extends('layouts.admin')

@section('title', 'Edit Menu Item')

@section('content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-pencil" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">RESTAURANT MENU</p>
                <h1 class="h3 mb-1">Edit Menu Item</h1>
                <p class="text-muted mb-0">Modify details for F&B item <strong>{{ $menu->name }}</strong>.</p>
            </div>
        </div>
        <div class="heading-actions">
            <a href="{{ route('master.fb-menus.index') }}" class="btn btn-outline-secondary btn-sm px-4 py-2"><i
                    class="bi bi-arrow-left"></i> Back</a>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="panel p-4">
                <form action="{{ route('master.fb-menus.update', $menu->id) }}" method="POST" class="needs-validation"
                    novalidate>
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label" for="menuName">Item Name</label>
                        <input class="form-control" name="name" id="menuName" type="text"
                            value="{{ old('name', $menu->name) }}" required>
                        <div class="invalid-feedback">Name is required.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="menuType">Item Type</label>
                        <select class="form-select" name="type" id="menuType" required>
                            <option value="food" {{ old('type', $menu->type) === 'food' ? 'selected' : '' }}>Food</option>
                            <option value="beverage" {{ old('type', $menu->type) === 'beverage' ? 'selected' : '' }}>
                                Beverage</option>
                        </select>
                        <div class="invalid-feedback">Please select a type.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="menuPrice">Price (Rp)</label>
                        <input class="form-control" name="price" id="menuPrice" type="number"
                            value="{{ old('price', $menu->price) }}" required>
                        <div class="invalid-feedback">Price is required.</div>
                    </div>

                    <button class="btn btn-primary" type="submit"><i class="bi bi-save"></i> Save Changes</button>
                </form>
            </div>
        </div>
    </div>
@endsection
