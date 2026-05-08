@extends('dashboard.layout.app')

@section('content')
<div class="container-fluid px-0">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">📨 Invitations</h2>
            <p class="text-muted small">Track and manage all sent invitation links for this organization.</p>
        </div>
        @if(auth()->user()->is_superadmin)
        <a href="/companies" class="btn btn-light shadow-sm px-4 rounded-3 border">
            <i class="bi bi-arrow-left me-2"></i> Back to Companies
        </a>
        @endif
       @if(currentUserRole() == 'admin')
        <button class="btn btn-dark shadow-sm px-4 rounded-3 border" 
                onclick="openInviteModal({{ $company->id }}, '{{ $company->name }}')">
            <i class="bi bi-person-plus me-2"></i> Invite
        </button>
       
        @endif
            
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 border-0 small text-uppercase fw-bold text-muted">S No.</th>
                            <th class="py-3 border-0 small text-uppercase fw-bold text-muted">Invited By</th>
                            <th class="py-3 border-0 small text-uppercase fw-bold text-muted">Role</th>
                            <th class="py-3 border-0 small text-uppercase fw-bold text-muted">Invitation Link</th>
                            <th class="py-3 border-0 small text-uppercase fw-bold text-muted">Status</th>
                            <th class="py-3 border-0 small text-uppercase fw-bold text-muted">Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invitations as $invite)
                            @php
                                $link = url('/invite/' . $invite->token);
                            @endphp
                            <tr>
                                <td class="ps-4 text-muted fw-semibold">
                                    {{ ($invitations->currentPage() - 1) * $invitations->perPage() + $loop->iteration }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person-circle text-secondary me-2 fs-5"></i>
                                        <span class="fw-medium">
                                            {{ $invite->invitedByUser->name 
                                                ? $invite->invitedByUser->name 
                                                : 'Unknown User' }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $invite->role == 'admin' ? 'bg-danger-subtle text-danger' : 'bg-primary-subtle text-primary' }} px-3 py-2 rounded-2 small">
                                        {{ ucfirst($invite->role) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="input-group input-group-sm" style="max-width: 300px;">
                                        <input type="text" value="{{ $link }}" id="link-{{ $invite->id }}" 
                                               class="form-control bg-light border-0 small text-truncate" readonly>
                                        <button class="btn btn-dark" onclick="copyLink('link-{{ $invite->id }}', this)">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    @if($invite->status == 'pending')
                                        <span class="text-warning fw-bold small">
                                            <i class="bi bi-clock-history me-1"></i> Pending
                                        </span>
                                    @else
                                        <span class="text-success fw-bold small">
                                            <i class="bi bi-check-circle-fill me-1"></i> {{ ucfirst($invite->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-muted small">
                                    {{ $invite->created_at->format('M d, Y h:i A') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-envelope-x fs-1 opacity-25 d-block mb-3"></i>
                                    No invitations found for this company.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($invitations->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                <div class="d-flex justify-content-center">
                    {{ $invitations->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
</div>

<div class="modal fade" id="inviteUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0">
                <h5 class="fw-bold">Invite to <span id="modalCompanyName" class="text-primary"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div id="inviteFormSection">
                <form id="ajaxInviteForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="company_id" id="invite_company_id">
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
                        <button type="submit" id="generateBtn" class="btn btn-primary w-100 rounded-pill py-2">
                            Generate Invite Link
                        </button>
                    </div>
                </form>
            </div>

            <div id="inviteResultSection" style="display: none;" class="p-4">
                <div class="text-center mb-3">
                    <div class="bg-success-subtle text-success rounded-circle d-inline-flex p-3 mb-2" style="width: 60px; height: 60px; align-items: center; justify-content: center;">
                        <i class="bi bi-check2-circle fs-2"></i>
                    </div>
                    <h6 class="fw-bold">Invite Link Ready!</h6>
                </div>
                <div class="input-group mb-3 bg-light p-1 rounded-3">
                    <input type="text" id="generatedInviteLink" class="form-control bg-transparent border-0 small px-3" readonly>
                    <button class="btn btn-dark rounded-3 px-3" onclick="copyToClipboard()" id="copyBtn">
                        <i class="bi bi-clipboard me-1"></i> Copy
                    </button>
                </div>
                <button type="button" class="btn btn-link btn-sm w-100 text-decoration-none text-muted" onclick="resetInviteModal()">
                    <i class="bi bi-arrow-left me-1"></i> Generate Another
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openInviteModal(companyId, companyName) {
        resetInviteModal(); 
        document.getElementById('modalCompanyName').innerText = companyName;
        document.getElementById('invite_company_id').value = companyId;
        
        var myModal = new bootstrap.Modal(document.getElementById('inviteUserModal'));
        myModal.show();
    }

    document.getElementById('ajaxInviteForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const btn = document.getElementById('generateBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Generating...';

        const formData = new FormData(this);

        fetch('/invite', { 
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                document.getElementById('inviteFormSection').style.display = 'none';
                document.getElementById('inviteResultSection').style.display = 'block';
                document.getElementById('generatedInviteLink').value = data.link;
            } else {
                alert('Something went wrong!');
            }
        })
        .catch(error => console.error('Error:', error))
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = 'Generate Invite Link';
        });
    });

    function copyToClipboard() {
        const copyText = document.getElementById("generatedInviteLink");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);

        const copyBtn = document.getElementById('copyBtn');
        const oldHtml = copyBtn.innerHTML;
        
        copyBtn.innerHTML = '<i class="bi bi-check-lg"></i> Copied!';
        copyBtn.classList.replace('btn-dark', 'btn-success');
        
        setTimeout(() => {
            copyBtn.innerHTML = oldHtml;
            copyBtn.classList.replace('btn-success', 'btn-dark');
        }, 2000);
    }

    function resetInviteModal() {
        document.getElementById('inviteFormSection').style.display = 'block';
        document.getElementById('inviteResultSection').style.display = 'none';
        document.getElementById('ajaxInviteForm').reset();
    }



function copyLink(inputId, btn) {
    const copyText = document.getElementById(inputId);
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    
    navigator.clipboard.writeText(copyText.value);

    const originalIcon = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-check-lg"></i>';
    btn.classList.replace('btn-dark', 'btn-success');

    setTimeout(() => {
        btn.innerHTML = originalIcon;
        btn.classList.replace('btn-success', 'btn-dark');
    }, 2000);
}
</script>
@endpush

@push('styles')
<style>
    .bg-primary-subtle { background-color: #e0e7ff !important; }
    .bg-danger-subtle { background-color: #fee2e2 !important; }
    .table-hover tbody tr:hover { background-color: #f8fafc; }
    
    /* Pagination Styling Fix */
    .pagination { margin-bottom: 0; }
    .page-link { border-radius: 8px !important; margin: 0 3px; border: none; color: #475569; }
    .page-item.active .page-link { background-color: #111827; color: white; }
    
    .form-control:focus { box-shadow: none; }
</style>
@endpush
@endsection