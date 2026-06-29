@extends('layouts.admin')

@section('title', 'Add Staff User')

@section('content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-person-plus" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">USER ACCOUNTS</p>
                <h1 class="h3 mb-1">Create Staff Account</h1>
                <p class="text-muted mb-0">Register a new login account for hotel staff.</p>
            </div>
        </div>
        <div class="heading-actions">
            <a href="{{ route('master.users.index') }}" class="btn btn-outline-secondary btn-sm px-4 py-2"><i
                    class="bi bi-arrow-left"></i> Back</a>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="panel p-4">
                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm mb-3 small" role="alert"
                        style="border-left: 4px solid #ef4444 !important;">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('master.users.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="userName">Full Name</label>
                        <input class="form-control" name="name" id="userName" type="text" value="{{ old('name') }}"
                            required>
                        <div class="invalid-feedback">Please enter name.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="userEmail">Email address</label>
                        <input class="form-control" name="email" id="userEmail" type="email" value="{{ old('email') }}"
                            required>
                        <div class="invalid-feedback">Enter a valid email.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="userRole">Account Role</label>
                        <select class="form-select" name="role" id="userRole" required>
                            <option value="" disabled selected>Select Role</option>
                            @foreach ($roles as $r)
                                <option value="{{ $r->name }}" {{ old('role') === $r->name ? 'selected' : '' }}>
                                    {{ $r->display_name }} ({{ $r->name }})
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Please select a role.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="userPassword">Password</label>
                        <input class="form-control" name="password" id="userPassword" type="password" required>
                        <div class="invalid-feedback">Password is required.</div>
                    </div>

                    <button class="btn btn-primary" type="submit"><i class="bi bi-save"></i> Save Account</button>
                </form>
            </div>
        </div>
    </div>
@endsection
