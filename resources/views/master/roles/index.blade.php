@extends('layouts.admin')

@section('title', 'Manage Roles & Permissions')

@section('content')
    <div class="page-heading mb-4">
        <div class="page-heading-copy">
            <span class="page-icon" style="background: rgba(37, 99, 235, 0.1); color: var(--admin-primary);"><i class="bi bi-shield-lock" aria-hidden="true"></i></span>
            <div>
                <p class="eyebrow mb-1">SYSTEM CONFIGURATION</p>
                <h1 class="h3 mb-1 text-dark fw-bold">Roles & Permissions</h1>
                <p class="text-muted mb-0">Create staff roles and configure their corresponding menu accessibility rules.</p>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <!-- Left Column: Role List CRUD -->
        <div class="col-12 col-xl-5">
            <div class="panel border-0 shadow-sm p-4 h-100" style="border-radius: 14px; background: var(--admin-surface);">
                <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                    <div>
                        <h2 class="h5 mb-1 fw-bold text-dark">Staff Roles</h2>
                        <p class="text-muted small mb-0">List of roles available in the system.</p>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 py-2 btn-tactile" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                        <i class="bi bi-plus-circle me-1"></i> Add Role
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Role Code</th>
                                <th>Display Name</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rolesData as $role)
                                <tr class="btn-tactile-row">
                                    <td>
                                        <code class="px-2 py-1 rounded bg-light text-dark font-monospace small">{{ $role->name }}</code>
                                    </td>
                                    <td>
                                        <strong class="text-dark">{{ $role->display_name }}</strong>
                                        @if(in_array($role->name, ['Admin', 'FO', 'HK', 'FB']))
                                            <span class="badge bg-light text-muted border ms-1" style="font-size: 0.65rem;">System</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-light btn-sm rounded-pill px-3 btn-tactile me-1" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editRoleModal"
                                            data-id="{{ $role->id }}"
                                            data-name="{{ $role->name }}"
                                            data-display-name="{{ $role->display_name }}">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </button>

                                        @if(!in_array($role->name, ['Admin', 'FO', 'HK', 'FB']))
                                            <form action="{{ route('master.roles.destroyRole', $role->id) }}" method="POST" class="d-inline delete-role-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 btn-tactile" onclick="return confirm('Are you sure you want to delete this role? All associated permissions will be permanently removed.');">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" disabled style="opacity: 0.5;" title="System roles cannot be deleted.">
                                                <i class="bi bi-lock-fill"></i> Locked
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column: Permissions Settings -->
        <div class="col-12 col-xl-7">
            <div class="panel border-0 shadow-sm p-4 h-100" style="border-radius: 14px; background: var(--admin-surface);">
                <form action="{{ route('master.roles.update') }}" method="POST">
                    @csrf
                    
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                        <div>
                            <h2 class="h5 mb-1 fw-bold text-dark">Menu Permissions</h2>
                            <p class="text-muted small mb-0">Select which dashboard menus are active for each role.</p>
                        </div>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold btn-tactile">
                            <i class="bi bi-check-circle-fill me-1"></i> Save Permissions
                        </button>
                    </div>

                    <!-- Inner Nav Pills for Permissions selection -->
                    <ul class="nav nav-pills mb-4" id="roleTabs" role="tablist" style="gap: 5px;">
                        @foreach ($roles as $roleName)
                            @php
                                $roleObject = $rolesData->firstWhere('name', $roleName);
                            @endphp
                             <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill px-3 py-2 fw-semibold btn-tactile {{ $loop->first ? 'active' : '' }}" 
                                    id="tab-{{ $roleName }}" 
                                    data-bs-toggle="tab" 
                                    data-bs-target="#panel-{{ $roleName }}" 
                                    type="button" 
                                    role="tab" 
                                    aria-controls="panel-{{ $roleName }}" 
                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                    {{ $roleObject ? $roleObject->display_name : $roleName }}
                                </button>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content" id="roleTabsContent">
                        @foreach ($roles as $roleName)
                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="panel-{{ $roleName }}" role="tabpanel" aria-labelledby="tab-{{ $roleName }}">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h3 class="h6 mb-0 fw-bold text-muted text-uppercase tracking-wider">
                                        {{ $roleName === 'Admin' ? 'Superadmin Permissions (Full)' : 'Custom Access Settings' }}
                                    </h3>
                                    @if($roleName === 'Admin')
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1" style="font-size: 0.72rem;">
                                            <i class="bi bi-lock-fill me-1"></i> Locked Full Access
                                        </span>
                                    @endif
                                </div>

                                <div class="row g-3">
                                    @foreach ($menus as $category => $items)
                                        <div class="col-12 col-md-6">
                                            <div class="card border-0 p-3 h-100" style="border-radius: 12px; background: var(--admin-surface-soft) !important; border: 1px solid rgba(0,0,0,0.03) !important;">
                                                <h4 class="h6 fw-bold mb-3 text-primary text-uppercase tracking-wider" style="font-size: 0.72rem; letter-spacing: 0.8px;">
                                                    {{ $category }}
                                                </h4>
                                                <div class="d-flex flex-column gap-3">
                                                    @foreach ($items as $menuKey => $menuName)
                                                        @php
                                                            $hasPermission = isset($permissions[$roleName]) && in_array($menuKey, $permissions[$roleName]);
                                                        @endphp
                                                        <div class="form-check form-switch d-flex align-items-center justify-content-between p-0 m-0">
                                                            <label class="form-check-label text-body small fw-semibold" for="switch-{{ $roleName }}-{{ $menuKey }}" style="cursor: pointer; user-select: none;">
                                                                {{ $menuName }}
                                                            </label>
                                                            <input class="form-check-input btn-tactile" type="checkbox" 
                                                                id="switch-{{ $roleName }}-{{ $menuKey }}" 
                                                                name="permissions[{{ $roleName }}][{{ $menuKey }}]"
                                                                {{ $roleName === 'Admin' ? 'checked disabled' : ($hasPermission ? 'checked' : '') }}
                                                                style="width: 2.3em; height: 1.15em; cursor: pointer; float: none; margin-left: 0;">
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Add Role -->
    <div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; background: var(--admin-surface);">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark" id="addRoleModalLabel">Add New Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('master.roles.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-body py-3">
                        <div class="mb-3">
                            <label for="roleCode" class="form-label fw-semibold text-dark">Role Code (Unique)</label>
                            <input type="text" class="form-control" id="roleCode" name="name" placeholder="e.g. Supervisor (letters and numbers only)" pattern="[a-zA-Z0-9]+" required>
                            <div class="invalid-feedback">Please enter a valid unique role code (alphanumeric only).</div>
                            <small class="text-muted" style="font-size: 0.72rem;">This string will be used internally (e.g. <code>Supervisor</code>).</small>
                        </div>
                        <div class="mb-3">
                            <label for="roleDisplayName" class="form-label fw-semibold text-dark">Display Name</label>
                            <input type="text" class="form-control" id="roleDisplayName" name="display_name" placeholder="e.g. Hotel Supervisor" required>
                            <div class="invalid-feedback">Please enter a display name.</div>
                            <small class="text-muted" style="font-size: 0.72rem;">This is shown to users on dropdowns and menus (e.g. <code>Hotel Supervisor</code>).</small>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0 justify-content-end">
                        <button type="button" class="btn btn-light rounded-pill px-4 btn-tactile" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 btn-tactile">Create Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Edit Role -->
    <div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; background: var(--admin-surface);">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark" id="editRoleModalLabel">Edit Role Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editRoleForm" action="" method="POST" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="modal-body py-3">
                        <div class="mb-3">
                            <label for="editRoleName" class="form-label fw-semibold text-dark">Role Code (Locked)</label>
                            <input type="text" class="form-control bg-light" id="editRoleName" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="editRoleDisplayName" class="form-label fw-semibold text-dark">Display Name</label>
                            <input type="text" class="form-control" id="editRoleDisplayName" name="display_name" placeholder="e.g. Hotel Supervisor" required>
                            <div class="invalid-feedback">Please enter a display name.</div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0 justify-content-end">
                        <button type="button" class="btn btn-light rounded-pill px-4 btn-tactile" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 btn-tactile">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    /* Premium style refinements */
    #roleTabs .nav-link {
        background: var(--admin-surface-soft);
        color: var(--admin-text-muted);
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.15s ease-in-out;
    }

    #roleTabs .nav-link.active {
        background: var(--admin-primary) !important;
        color: #fff !important;
        border-color: var(--admin-primary) !important;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.18);
    }

    #roleTabs .nav-link:not(.active):hover {
        background: rgba(0, 0, 0, 0.04) !important;
        color: var(--admin-text-strong) !important;
        border-color: rgba(0, 0, 0, 0.1) !important;
    }
    
    .form-switch .form-check-input:focus {
        border-color: var(--admin-primary);
        box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.15);
    }
    
    .modal-content {
        border: 1px solid rgba(0,0,0,0.05) !important;
    }
    
    .table td, .table th {
        border-bottom: 1px solid rgba(0, 0, 0, 0.03);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Edit Role modal population
        const editModal = document.getElementById('editRoleModal');
        if (editModal) {
            editModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const displayName = button.getAttribute('data-display-name');
                
                const form = editModal.querySelector('#editRoleForm');
                form.action = `/master/roles/${id}/update`;
                
                const inputName = editModal.querySelector('#editRoleName');
                inputName.value = name;
                
                const inputDisplayName = editModal.querySelector('#editRoleDisplayName');
                inputDisplayName.value = displayName;
            });
        }
        
        // Bootstrap 5 client-side validation
        const forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    });
</script>
@endpush
