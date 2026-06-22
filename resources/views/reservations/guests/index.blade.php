@extends('layouts.admin')

@section('title', 'Guest Profiles')

@section('content')
<div class="page-heading">
  <div class="page-heading-copy">
    <span class="page-icon"><i class="bi bi-person-lines-fill" aria-hidden="true"></i></span>
    <div>
      <p class="eyebrow mb-1">FRONT OFFICE OPERATIONS</p>
      <h1 class="h3 mb-1">Guest Profiles Directory</h1>
      <p class="text-muted mb-0">View all guests histories, demographics, and contact information.</p>
    </div>
  </div>
</div>

<div class="panel shadow-sm">
  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>Guest Name</th>
          <th>NIK / Passport</th>
          <th>Contact details</th>
          <th>Country</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($guests as $guest)
          <tr>
            <td>
              <strong>{{ $guest->name }}</strong>
              <br><span class="badge bg-light text-dark small" style="font-size: 0.7rem;">{{ $guest->gender }}</span>
            </td>
            <td class="font-monospace">{{ $guest->id_number }}</td>
            <td>
              <span><i class="bi bi-telephone text-muted me-1"></i> {{ $guest->phone }}</span>
              @if($guest->email)
                <br><span><i class="bi bi-envelope text-muted me-1"></i> {{ $guest->email }}</span>
              @endif
            </td>
            <td>{{ $guest->country }}</td>
            <td>
              <a href="{{ route('fo.guests.show', $guest->id) }}" class="btn btn-primary btn-xs"><i class="bi bi-eye"></i> View Profile History</a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center text-muted py-4">No guest profiles recorded in the system.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
