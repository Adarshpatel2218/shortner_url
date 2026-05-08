@extends('dashboard.layout.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">🔗 URL Shortener</h2>
            <p class="text-muted small">Create short links and track their performance.</p>
        </div>
    </div>


    @if(currentUserRole() === 'admin' || currentUserRole() === 'member')
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3 text-uppercase small text-muted">Create New Link</h6>
            <div class="row g-2">
                <div class="col-md-9">
                    <input type="url" id="original_url" class="form-control rounded-3 py-2 border-light bg-light" 
                           placeholder="https://example.com/very-long-link-to-shorten" style="box-shadow: none;">
                </div>
                <div class="col-md-3">
                    <button id="generateBtn" class="btn btn-primary w-100 rounded-3 py-2 fw-bold">
                        <i class="bi bi-magic me-1"></i> Generate
                    </button>
                </div>
            </div>
            <div id="result" class="mt-3"></div>
        </div>
    </div>

    @endif


    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 py-3 px-4">
            <h5 class="fw-bold mb-0">My Shortened URLs</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 border-0 small text-uppercase fw-bold text-muted">S NO.</th>
                            <th class="ps-4 py-3 border-0 small text-uppercase fw-bold text-muted">Company</th>
                            <th class="ps-4 py-3 border-0 small text-uppercase fw-bold text-muted">Created By</th>
                            <th class="py-3 border-0 small text-uppercase fw-bold text-muted">Original URL</th>
                            <th class="py-3 border-0 small text-uppercase fw-bold text-muted">Short URL</th>
                            <th class="py-3 border-0 small text-uppercase fw-bold text-muted text-center">Clicks</th>
                            <!-- <th class="py-3 border-0 small text-uppercase fw-bold text-muted text-end pe-4">Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($urls as $url)
                            <tr>
                                <td class="ps-4 text-muted small">#{{ $loop->iteration }}</td>
                                <td>{{ $url->company->name ?? 'N/A' }}</td>
                                <td>{{ $url->user->name ?? 'N/A' }}</td>
                                <td>
                                     <div class="d-flex align-items-center">
                                        <div class="text-truncate text-muted small" style="max-width: 300px;" title="{{ $url->original_url }}">
                                        {{ Str::limit($url->original_url, 10) }}
                                    </div>
                                     <button class="btn btn-light btn-sm rounded-circle shadow-sm" onclick="copyToClipboard('{{ $url->original_url }}', this)">
                                            <i class="bi bi-clipboard small"></i>
                                        </button>
                                    </div>
                                    
                                </td>
                                <td>
                                    @php $short = url('/s/' . $url->short_code); @endphp
                                    <div class="d-flex align-items-center">
                                        <a href="{{ $short }}" target="_blank" class="text-primary fw-bold text-decoration-none me-2">
                                            {{Str::limit($short, 10) }}
                                        </a>
                                        <button class="btn btn-light btn-sm rounded-circle shadow-sm" onclick="copyToClipboard('{{ $short }}', this)">
                                            <i class="bi bi-clipboard small"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-dark rounded-pill px-3">{{ $url->clicks }}</span>
                                </td>
                                <!-- <td class="text-end pe-4">
                                    <button class="btn btn-outline-danger btn-sm rounded-circle"><i class="bi bi-trash"></i></button>
                                </td> -->
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-5 text-muted small">No URLs found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$("#generateBtn").click(function () {
    let original_url = $("#original_url").val();
    let btn = $(this);
    

    if(!original_url) {
        Swal.fire('Error', 'Please enter a URL first', 'error');
        return;
    }

    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

    $.ajax({
        url: "/short-urls/store",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            original_url: original_url
        },
        success: function (response) {
            if (response.status) {
                $("#original_url").val('');
                
                Swal.fire({
                    title: 'Short URL Created!',
                    html: `
                        <div class="p-3 bg-light rounded-3 mb-3 text-start">
                            <label class="small fw-bold text-muted mb-1 d-block text-uppercase">Your Short Link:</label>
                            <div class="input-group">
                                <input type="text" id="generated_link" class="form-control border-0 shadow-none" value="${response.short_url}" readonly>
                                <button class="btn btn-dark" type="button" onclick="copyFromModal()">
                                    <i class="bi bi-clipboard me-1"></i> Copy
                                </button>
                            </div>
                        </div>
                        <p class="small text-muted mb-0"><i class="bi bi-info-circle me-1"></i> Page will reload once you close this modal.</p>
                    `,
                    icon: 'success',
                    showConfirmButton: true,
                    confirmButtonText: 'Done & Refresh',
                    confirmButtonColor: '#3b82f6',
                    allowOutsideClick: false // Taaki galti se bahar click karke reload na ho jaye
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.reload();
                    }
                });
            }
        },
        error: function (xhr) {
            btn.prop('disabled', false).html('<i class="bi bi-magic me-1"></i> Generate');
            let errorMsg = xhr.responseJSON.message || "Something went wrong";
            Swal.fire('Oops!', errorMsg, 'error');
        },
        complete: function() {
            btn.prop('disabled', false).html('<i class="bi bi-magic me-1"></i> Generate');
        }
    });
});

function copyFromModal() {
    const copyText = document.getElementById("generated_link");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);

    const copyBtn = event.target.closest('button');
    const originalHtml = copyBtn.innerHTML;
    copyBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Copied!';
    copyBtn.classList.replace('btn-dark', 'btn-success');
    
    setTimeout(() => {
        copyBtn.innerHTML = originalHtml;
        copyBtn.classList.replace('btn-success', 'btn-dark');
    }, 2000);
}

function copyToClipboard(text, btn) {
    navigator.clipboard.writeText(text);
    const oldIcon = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-check text-success"></i>';
    setTimeout(() => btn.innerHTML = oldIcon, 2000);
}
</script>
@endpush
@endsection