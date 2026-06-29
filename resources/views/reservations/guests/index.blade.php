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
        <div class="heading-actions">
            <button type="button" class="btn btn-primary btn-tactile btn-sm px-4 py-2" data-bs-toggle="modal"
                data-bs-target="#createGuestModal"><i class="bi bi-plus-circle"></i> Add New Guest</button>
        </div>
    </div>

    <div class="panel-premium shadow-sm p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Guest Name</th>
                        <th>NIK / Passport</th>
                        <th>Contact Details</th>
                        <th>Country</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guests as $guest)
                        <tr>
                            <td>
                                <strong>{{ $guest->name }}</strong>
                                <br><span class="badge badge-soft-secondary small mt-1"
                                    style="font-size: 0.7rem;">{{ $guest->gender }}</span>
                            </td>
                            <td class="font-monospace text-muted">{{ $guest->id_number }}</td>
                            <td>
                                <span class="d-block small"><i class="bi bi-telephone text-muted me-1"></i>
                                    {{ $guest->phone }}</span>
                                @if ($guest->email)
                                    <span class="d-block small mt-1"><i class="bi bi-envelope text-muted me-1"></i>
                                        {{ $guest->email }}</span>
                                @endif
                            </td>
                            <td><span class="badge badge-soft-primary">{{ $guest->country }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('fo.guests.show', $guest->id) }}"
                                    class="btn btn-primary btn-tactile btn-sm me-2 px-3 py-2"><i class="bi bi-pencil"></i>
                                    Edit</a>
                                <form action="{{ route('fo.guests.destroy', $guest->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this guest profile? All associated reservation history for this guest will also be deleted.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-tactile btn-sm px-3 py-2"><i
                                            class="bi bi-trash"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No guest profiles recorded in the system.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Add New Guest -->
    <div class="modal fade" id="createGuestModal" tabindex="-1" aria-labelledby="createGuestModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold" id="createGuestModalLabel"><i
                            class="bi bi-person-plus-fill text-primary"></i> Add Guest Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('fo.guests.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="new_name" class="form-label small fw-bold">Full Name</label>
                                <input type="text" name="name" id="new_name" class="form-control form-control-sm"
                                    required placeholder="e.g. John Doe">
                            </div>

                            <div class="col-12">
                                <label for="new_id_number" class="form-label small fw-bold">NIK / Passport</label>
                                <input type="text" name="id_number" id="new_id_number"
                                    class="form-control form-control-sm" required placeholder="e.g. 3171012345678901">
                            </div>

                            <div class="col-6">
                                <label for="new_birth_date" class="form-label small fw-bold">Birth Date</label>
                                <input type="date" name="birth_date" id="new_birth_date"
                                    class="form-control form-control-sm" required>
                            </div>

                            <div class="col-6">
                                <label for="new_gender" class="form-label small fw-bold">Gender</label>
                                <select name="gender" id="new_gender" class="form-select form-select-sm" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>

                            <div class="col-6">
                                <label for="new_phone" class="form-label small fw-bold">Phone Number</label>
                                <input type="text" name="phone" id="new_phone" class="form-control form-control-sm"
                                    required placeholder="e.g. 08123456789">
                            </div>

                            <div class="col-6">
                                <label for="new_email" class="form-label small fw-bold">Email</label>
                                <input type="email" name="email" id="new_email" class="form-control form-control-sm"
                                    placeholder="e.g. john@example.com">
                            </div>

                            <div class="col-12">
                                <label for="new_country" class="form-label small fw-bold">Nationality</label>
                                <input type="text" name="country" id="new_country" value="Indonesia"
                                    class="form-control form-control-sm" required>
                            </div>

                            <div class="col-12">
                                <label for="new_address" class="form-label small fw-bold">Full Address</label>
                                <textarea name="address" id="new_address" rows="2" class="form-control form-control-sm"
                                    placeholder="e.g. Jl. Sudirman No. 10, Jakarta"></textarea>
                            </div>

                            <div class="col-12">
                                <label for="new_vehicle_no" class="form-label small fw-bold">Vehicle Number
                                    (Optional)</label>
                                <input type="text" name="vehicle_no" id="new_vehicle_no"
                                    class="form-control form-control-sm" placeholder="e.g. B 1234 CD">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-light btn-tactile btn-sm"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-tactile btn-sm"><i class="bi bi-save"></i> Save
                            Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
