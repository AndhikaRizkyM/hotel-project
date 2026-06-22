@extends('layouts.admin')

@block('title', 'Manage Users')

@section('content')
<div class="page-heading">
  <div class="page-heading-copy">
    <span class="page-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
    <div>
      <p class="eyebrow mb-1">SYSTEM CONFIGURATION</p>
      <h1 class="h3 mb-1">User Accounts</h1>
      <p class="text-muted mb-0">Manage staff login details, roles, and status.</p>
    </div>
  </div>
  <div class="heading-actions">
    <a href="{{ route('master.users.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-person-plus"></i> Add Staff Account</a>
  </div>
</div>

<div class="panel mt-3">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Status</th>
          <th>Created At</th>
          <th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $user)
          <tr>
            <td>
              <div class="d-flex align-items-center gap-2">
                <img class="avatar-img avatar-sm" src="{{ asset('template/assets/images/avatar/avatar.jpg') }}" alt="Avatar">
                <strong>{{ $user->name }}</strong>
              </div>
            </td>
            <td>{{ $user->email }}</td>
            <td>
              <span class="badge bg-secondary">{{ $user->role }}</span>
            </td>
            <td>
              <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'danger' }}">{{ ucfirst($user->status) }}</span>
            </td>
            <td>{{ $user->created_at->format('d M Y') }}</td>
            <td class="text-end">
              <a href="{{ route('master.users.edit', $user->id) }}" class="btn btn-light btn-sm"><i class="bi bi-pencil"></i> Edit</a>
              <form action="{{ route('master.users.toggle', $user->id) }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-outline-{{ $user->status === 'active' ? 'danger' : 'success' }} btn-sm" type="submit">
                  <i class="bi bi-power"></i> {{ $user->status === 'active' ? 'Deactivate' : 'Activate' }}
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
