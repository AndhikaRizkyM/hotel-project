@extends('layouts.admin')

@section('title', 'Add Menu Item')

@section('content')
<div class="page-heading">
  <div class="page-heading-copy">
    <span class="page-icon"><i class="bi bi-plus-circle" aria-hidden="true"></i></span>
    <div>
      <p class="eyebrow mb-1">RESTAURANT MENU</p>
      <h1 class="h3 mb-1">Add Menu Item</h1>
      <p class="text-muted mb-0">Register a new food or beverage item in the hotel database.</p>
    </div>
  </div>
  <div class="heading-actions">
    <a href="{{ route('master.fb-menus.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Back</a>
  </div>
</div>

<div class="row mt-3">
  <div class="col-12 col-md-8 col-lg-6">
    <div class="panel p-4">
      <form action="{{ route('master.fb-menus.store') }}" method="POST" class="needs-validation" novalidate>
        @csrf
        
        <div class="mb-3">
          <label class="form-label" for="menuName">Item Name</label>
          <input class="form-control" name="name" id="menuName" type="text" placeholder="e.g. Club Sandwich" required>
          <div class="invalid-feedback">Name is required.</div>
        </div>

        <div class="mb-3">
          <label class="form-label" for="menuType">Item Type</label>
          <select class="form-select" name="type" id="menuType" required>
            <option value="" disabled selected>Select Type</option>
            <option value="food">Food</option>
            <option value="beverage">Beverage</option>
          </select>
          <div class="invalid-feedback">Please select a type.</div>
        </div>

        <div class="mb-3">
          <label class="form-label" for="menuPrice">Price (Rp)</label>
          <input class="form-control" name="price" id="menuPrice" type="number" placeholder="45000" required>
          <div class="invalid-feedback">Price is required.</div>
        </div>

        <button class="btn btn-primary" type="submit"><i class="bi bi-save"></i> Save Item</button>
      </form>
    </div>
  </div>
</div>
@endsection
