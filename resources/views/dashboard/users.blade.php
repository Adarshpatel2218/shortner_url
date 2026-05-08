@extends('dashboard.layout.app')

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">👥 All Users</h2>
            <p class="text-muted small">Manage users and their roles for your organization.</p>
        </div>

    </div>

    {{-- CARD --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-bold text-muted">S No</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted">Name</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted">Email</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted">Invited By</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted">Company</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted text-center">Role</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted">Joined Date</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($users as $row)

                            @php
                                $user = $row->user;
                                $company = $row->company;
                                $role = $row->role ?? 'member';
                            @endphp

                            <tr>
                                <td class="ps-4 text-muted">
                                    {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                                </td>

                                <td>
                                    <div class="d-flex align-items-center">

                                        <div class="avatar-sm me-3 bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                             style="width:40px;height:40px;">
                                            {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                        </div>

                                        <div>
                                            <div class="fw-bold text-dark">
                                                {{ $user->name ?? 'N/A' }}
                                            </div>

                                            @if(!empty($user->is_superadmin) && $user->is_superadmin)
                                                <span class="badge bg-danger-subtle text-danger px-1 small" style="font-size:10px;">
                                                    SYSTEM SA
                                                </span>
                                            @endif
                                        </div>

                                    </div>
                                </td>

                                <td class="text-muted small">
                                    {{ $user->email ?? 'N/A' }}
                                </td>
                                <td class="text-muted small">

                                {{ optional(optional($row->invitation)->inviter)->name ?? 'Self Registered' }}

                                </td>

                                <td class="text-muted small">
                                    {{ $company->name ?? 'N/A' }}
                                </td>

                                <td class="text-center">
                                    <span class="badge {{ $role == 'admin' ? 'bg-dark' : 'bg-light text-dark border' }} rounded-pill px-3 py-2 small">
                                        {{ ucfirst($role) }}
                                    </span>
                                </td>
                                <td class="text-muted small">
                                    {{ $row->created_at ? $row->created_at->format('d M Y') : '-' }}
                                </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-people fs-1 opacity-25 d-block mb-2"></i>
                                    No users found in this team.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>

            {{-- PAGINATION --}}
            @if($users->hasPages())
                <div class="p-3 border-top d-flex justify-content-end">
                    {{ $users->links() }}
                </div>
            @endif

        </div>
    </div>

</div>

{{-- STYLES --}}
@push('styles')
<style>
    .bg-primary-subtle { background-color:#e0e7ff !important; }
    .bg-danger-subtle { background-color:#fee2e2 !important; }

    .table-hover tbody tr:hover {
        background-color:#f9fafb;
        transition:0.2s;
    }

    .avatar-sm {
        font-size:14px;
    }

    .pagination {
        margin:0;
    }

    .page-link {
        border-radius:8px !important;
        margin:0 3px;
    }
</style>
@endpush

@endsection