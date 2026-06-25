@extends('layouts.admin')

@section('title', 'Edit Staff User')

@section('content')
<div class="page-heading">
  <div class="page-heading-copy">
    <span class="page-icon"><i class="bi bi-pencil" aria-hidden="true"></i></span>
    <div>
      <p class="eyebrow mb-1">USER ACCOUNTS</p>
      <h1 class="h3 mb-1">Edit Staff Account</h1>
      <p class="text-muted mb-0">Modify details for account <strong>{{ $user->name }}</strong>.</p>
    </div>
  </div>
  <div class="heading-actions">
    <a href="{{ route('master.users.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Back</a>
  </div>
</div>

<div class="row mt-3">
  <div class="col-12 col-md-8 col-lg-6">
    <div class="panel p-4">
      @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-3 small" role="alert" style="border-left: 4px solid #ef4444 !important;">
          <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('master.users.update', $user->id) }}" method="POST" class="needs-validation" novalidate>
        @csrf
        @method('PUT')
        
        <div class="mb-3">
          <label class="form-label" for="userName">Full Name</label>
          <input class="form-control" name="name" id="userName" type="text" value="{{ old('name', $user->name) }}" required>
          <div class="invalid-feedback">Please enter name.</div>
        </div>

        <div class="mb-3">
          <label class="form-label" for="userEmail">Email address</label>
          <input class="form-control" name="email" id="userEmail" type="email" value="{{ old('email', $user->email) }}" required>
          <div class="invalid-feedback">Enter a valid email.</div>
        </div>

        <div class="mb-3">
          <label class="form-label" for="userRole">Account Role</label>
          <select class="form-select" name="role" id="userRole" required>
            <option value="Admin" {{ old('role', $user->role) === 'Admin' ? 'selected' : '' }}>Admin / Superadmin</option>
            <option value="FO" {{ old('role', $user->role) === 'FO' ? 'selected' : '' }}>Front Office (FO)</option>
            <option value="HK" {{ old('role', $user->role) === 'HK' ? 'selected' : '' }}>Housekeeping (HK)</option>
            <option value="FB" {{ old('role', $user->role) === 'FB' ? 'selected' : '' }}>Food & Beverage (FB)</option>
          </select>
          <div class="invalid-feedback">Please select a role.</div>
        </div>

        <div class="mb-3">
          <label class="form-label" for="userPassword">Password <small class="text-muted">(leave empty to keep current password)</small></label>
          <input class="form-control" name="password" id="userPassword" type="password">
        </div>

        <button class="btn btn-primary" type="submit"><i class="bi bi-save"></i> Save Changes</button>
      </form>
    </div>
  </div>
</div>
@endsection
