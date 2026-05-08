@extends('dashboard.layout.app')

@section('content')
<div class="container-fluid px-0">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">🏢 Companies Management</h2>
            <p class="text-muted small">Manage organizations and generate secure invite links.</p>
        </div>
        <button class="btn btn-dark shadow-sm px-4 rounded-3" data-bs-toggle="modal" data-bs-target="#addCompanyModal">
            <i class="bi bi-plus-circle me-2"></i> Add Company
        </button>
    </div>

    <div id="alertBox"></div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 border-0 small text-uppercase fw-bold text-muted">ID</th>
                            <th class="py-3 border-0 small text-uppercase fw-bold text-muted">Company Name</th>
                            <th class="py-3 border-0 small text-uppercase fw-bold text-muted">Created Date</th>
                            <th class="py-3 border-0 small text-uppercase fw-bold text-muted text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($companies as $company)
                            <tr>
                                <td class="ps-4 fw-semibold text-muted">#{{ $company->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary-subtle text-primary rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                            <i class="bi bi-building small"></i>
                                        </div>
                                        <span class="fw-bold text-slate-700">{{ $company->name }}</span>
                                    </div>
                                </td>
                                <td class="text-muted small">{{ $company->created_at->format('M d, Y') }}</td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button class="btn btn-info btn-sm text-white rounded-pill px-3"
                                                onclick="openInviteModal({{ $company->id }}, '{{ $company->name }}')">
                                            <i class="bi bi-person-plus"></i> Invite
                                        </button>

                                        <button class="btn btn-outline-primary btn-sm rounded-pill px-3"
                                                onclick="openINewnviteModal({{ $company->id }}, '{{ $company->name }}')">
                                            <i class="bi bi-link-45deg"></i> New Invite
                                        </button>

                                        <a href="{{ route('invites.index', $company->id) }}" class="btn btn-light btn-sm rounded-circle shadow-sm">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="bi bi-buildings fs-1 opacity-25 d-block mb-3"></i>
                                    No companies found. Create your first one!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addCompanyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Create New Company</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addCompanyForm">
                @csrf
                <div class="modal-body py-4">
                    <label class="form-label small fw-bold text-muted text-uppercase">Company Name</label>
                    <input type="text" name="name" class="form-control rounded-3 py-2 bg-light border-0" placeholder="e.g. Acme Corporation" required>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark rounded-pill px-4">Save Company</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="inviteUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0">
                <h5 class="fw-bold">Invite to <span id="modalCompanyNameOld" class="text-primary"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div id="old_inviteFormSection">
                <form id="ajaxInviteFormOldMember">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="company_id" id="old_invite_company_id">

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Select Role</label>
                            <select name="role" class="form-select border-0 bg-light py-2 shadow-none rounded-3" required>
                                <option value="member">Member (Limited Access)</option>
                                <option value="admin">Admin (Full Access)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Select User</label>
                            <select class="form-select border-0 bg-light py-2 shadow-none rounded-3" name="userId" id="old_UserId" required>
                                <option value="">Select User</option>
                                @foreach($userCompanies as $row)
                                    <option value="{{ $row->user_id }}">
                                        {{ $row->user->name ?? 'N/A' }}
                                        ({{ ucfirst($row->role) }} - {{ $row->company->name ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" id="old_generateBtn" class="btn btn-primary w-100 rounded-pill py-2">
                            Send Invite Message
                        </button>
                    </div>
                </form>
            </div>

            <div id="old_inviteResultSection" style="display: none;" class="p-4">
                <div class="text-center mb-3">
                    <div class="bg-success-subtle text-success rounded-circle d-inline-flex p-3 mb-2" style="width: 60px; height: 60px; align-items: center; justify-content: center;">
                        <i class="bi bi-check2-circle fs-2"></i>
                    </div>
                    <h6 class="fw-bold">Invite Sent !!</h6>
                    <p class="small text-muted mb-0">The invitation message has been sent successfully.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="inviteNewUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0">
                <h5 class="fw-bold">New Member Invite to <span id="modalCompanyNameNew" class="text-primary"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div id="new_inviteFormSection">
                <form id="ajaxInviteFormNewMember">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="company_id" id="new_invite_company_id">

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Select Role</label>
                            <select name="role" class="form-select border-0 bg-light py-2 shadow-none rounded-3" required>
                                <option value="member">Member (Limited Access)</option>
                                <option value="admin">Admin (Full Access)</option>
                            </select>
                        </div>
                        <div class="p-3 bg-light rounded-3">
                            <p class="small text-muted mb-0">
                                <i class="bi bi-info-circle me-1 text-primary"></i>
                                After generating, you can copy the link and share it with the user.
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" id="new_generateBtn" class="btn btn-primary w-100 rounded-pill py-2">
                            Generate Invite Link
                        </button>
                    </div>
                </form>
            </div>

            <div id="new_inviteResultSection" style="display: none;" class="p-4">
                <div class="text-center mb-3">
                    <div class="bg-success-subtle text-success rounded-circle d-inline-flex p-3 mb-2" style="width: 60px; height: 60px; align-items: center; justify-content: center;">
                        <i class="bi bi-check2-circle fs-2"></i>
                    </div>
                    <h6 class="fw-bold">Invite Link Ready!</h6>
                </div>
                <div class="input-group mb-3 bg-light p-1 rounded-3">
                    <input type="text" id="new_generatedInviteLink" class="form-control bg-transparent border-0 small px-3" readonly>
                    <button class="btn btn-dark rounded-3 px-3" onclick="copyToClipboardNew()" id="new_copyBtn">
                        <i class="bi bi-clipboard me-1"></i> Copy
                    </button>
                </div>
                <button type="button" class="btn btn-link btn-sm w-100 text-decoration-none text-muted" onclick="resetNewInviteModal()">
                    <i class="bi bi-arrow-left me-1"></i> Generate Another
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Global Alert Function
    function showAlert(type, message) {
        document.getElementById('alertBox').innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        setTimeout(() => {
            document.getElementById('alertBox').innerHTML = '';
        }, 5000);
    }

    // --- 1. Add Company AJAX ---
    document.getElementById('addCompanyForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = 'Saving...';

        try {
            const response = await fetch("{{ route('companies.add') }}", {
                method: "POST",
                body: new FormData(this),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok && data.success) {
                this.reset();
                bootstrap.Modal.getInstance(document.getElementById('addCompanyModal')).hide();
                showAlert('success', data.message);
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('danger', data.message || 'Error occurred while saving.');
            }
        } catch (error) {
            showAlert('danger', 'Something went wrong.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = 'Save Company';
        }
    });

    // --- 2. Open Modals Functions ---
    function openInviteModal(companyId, companyName) {
        document.getElementById('modalCompanyNameOld').innerText = companyName;
        document.getElementById('old_invite_company_id').value = companyId;

        // Reset old modal state
        document.getElementById('old_inviteFormSection').style.display = 'block';
        document.getElementById('old_inviteResultSection').style.display = 'none';
        document.getElementById('ajaxInviteFormOldMember').reset();

        var myModal = new bootstrap.Modal(document.getElementById('inviteUserModal'));
        myModal.show();
    }

    function openINewnviteModal(companyId, companyName) {
        document.getElementById('modalCompanyNameNew').innerText = companyName;
        document.getElementById('new_invite_company_id').value = companyId;

        // Reset new modal state
        resetNewInviteModal();

        var myModal = new bootstrap.Modal(document.getElementById('inviteNewUserModal'));
        myModal.show();
    }

    // --- 3. Invite Existing/Old Member AJAX ---
    document.getElementById('ajaxInviteFormOldMember').addEventListener('submit', function (e) {
        e.preventDefault();

        const btn = document.getElementById('old_generateBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';

        fetch('/invite-old', {
            method: 'POST',
            body: new FormData(this),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('old_inviteFormSection').style.display = 'none';
                document.getElementById('old_inviteResultSection').style.display = 'block';
            } else {
                showAlert('danger', data.message || 'Error sending invitation.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'Something went wrong! Please try again.');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = 'Send Invite Message';
        });
    });

    // --- 4. Generate New Invite Link AJAX ---
    document.getElementById('ajaxInviteFormNewMember').addEventListener('submit', function(e) {
        e.preventDefault();

        const btn = document.getElementById('new_generateBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Generating...';

        fetch('/invite', {
            method: 'POST',
            body: new FormData(this),
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                document.getElementById('new_inviteFormSection').style.display = 'none';
                document.getElementById('new_inviteResultSection').style.display = 'block';
                document.getElementById('new_generatedInviteLink').value = data.link;
            } else {
                showAlert('danger', 'Something went wrong!');
            }
        })
        .catch(error => console.error('Error:', error))
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = 'Generate Invite Link';
        });
    });

    // --- 5. Copy New Generated Link ---
    function copyToClipboardNew() {
        const copyText = document.getElementById("new_generatedInviteLink");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);

        const copyBtn = document.getElementById('new_copyBtn');
        const oldHtml = copyBtn.innerHTML;

        copyBtn.innerHTML = '<i class="bi bi-check-lg"></i> Copied!';
        copyBtn.classList.replace('btn-dark', 'btn-success');

        setTimeout(() => {
            copyBtn.innerHTML = oldHtml;
            copyBtn.classList.replace('btn-success', 'btn-dark');
        }, 2000);
    }

    // --- 6. Reset New Invite Form ---
    function resetNewInviteModal() {
        document.getElementById('new_inviteFormSection').style.display = 'block';
        document.getElementById('new_inviteResultSection').style.display = 'none';
        document.getElementById('ajaxInviteFormNewMember').reset();
    }
</script>
@endpush

@push('styles')
<style>
    .bg-primary-subtle { background-color: #e0e7ff !important; }
    .bg-success-subtle { background-color: #dcfce7 !important; }
    .table-hover tbody tr:hover { background-color: #f8fafc; }
    .btn-info { background-color: #0ea5e9; border: none; }
    .btn-info:hover { background-color: #0284c7; }

    .form-control:focus, .form-select:focus {
        background-color: #fff !important;
        border: 1px solid #3b82f6 !important;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }

    #new_generatedInviteLink {
        font-family: monospace;
        font-size: 0.85rem;
        color: #475569;
    }
</style>
@endpush
@endsection