@extends('dashboard.layout.app')

@section('content')
<div class="container-fluid px-0">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Dashboard</h2>
            <p class="text-muted small">Overview of your account and shortened links.</p>
        </div>
        @if(auth()->user()->is_superadmin)
            <a href="{{ route('companies.index') }}" class="btn btn-primary btn-sm px-3 rounded-pill">
                <i class="bi bi-building me-1"></i> Manage Companies
            </a>
        @else
        <div class="d-flex align-items-center gap-2">

            {{-- CHANGE COMPANY --}}
            <form method="POST" action="/change-company" class="d-flex align-items-center">
                @csrf

                <select 
                    name="company_id"
                    class="form-select form-select-sm rounded-pill shadow-none"
                    onchange="this.form.submit()"
                    style="min-width: 220px;"
                >
                    @foreach(auth()->user()->companies as $company)
                        <option 
                            value="{{ $company->id }}"
                            {{ session('current_company_id') == $company->id ? 'selected' : '' }}
                        >
                            {{ $company->name }} ({{ ucfirst($company->pivot->role) }})
                        </option>
                    @endforeach
                </select>
            </form>

            {{-- CREATE URL BUTTON --}}
            <a href="{{ route('short-urls.index') }}" class="btn btn-primary btn-sm px-3 rounded-pill">
                <i class="bi bi-plus-lg me-1"></i> Create New URL
            </a>

        </div>
        @endif
    </div>

    
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary-subtle text-primary rounded-3 p-3 me-3">
                        <i class="bi bi-person-badge fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold text-uppercase">Total Users</div>
                            <h5 class="fw-bold mb 
                        <h5 class="fw-bold mb-0">#{{ $users }}</h5>
                       
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-success-subtle text-success rounded-3 p-3 me-3">
                        <i class="bi bi-link-45deg fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold text-uppercase">
                            Total URLs
                        </div>
                        <h5 class="fw-bold mb-0">
                            #{{ $urls->count() }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-warning-subtle text-warning rounded-3 p-3 me-3">
                        <i class="bi bi-buildings fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold text-uppercase">Current Entity</div>
                        <h5 class="fw-bold mb-0">
                            @if(auth()->user()->is_superadmin)
                                All Companies
                            @else
                                <span class="text-truncate d-inline-block" style="max-width: 150px;">
                                    {{ session('current_company_id') ?? 'Not Selected' }}
                                </span>
                            @endif
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 py-3 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Recent URLs</h5>
                <i class="bi bi-three-dots text-muted"></i>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            
                            <th class="ps-4 py-3 border-0 small text-uppercase fw-bold text-muted">S NO.</th>
                            <th class="ps-4 py-3 border-0 small text-uppercase fw-bold text-muted">Short Code</th>
                            <th class="py-3 border-0 small text-uppercase fw-bold text-muted">Original URL</th>
                            <th class="py-3 border-0 small text-uppercase fw-bold text-muted">Short URL</th>
                            <!-- <th class="py-3 border-0 small text-uppercase fw-bold text-muted text-end pe-4">Actions</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($urls->take(10) as $url)
                            <tr>
                                <td class="ps-4">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="ps-4">
                                    <span class="badge bg-blue-subtle text-primary fw-semibold px-3 py-2 rounded-2">
                                        {{ $url->short_code }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="text-truncate text-muted small" style="max-width: 300px;" title="{{ $url->original_url }}">
                                        {{ Str::limit($url->original_url, 30) }}
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
                                            {{Str::limit($short, 30) }}
                                        </a>
                                        <button class="btn btn-light btn-sm rounded-circle shadow-sm" onclick="copyToClipboard('{{ $short }}', this)">
                                            <i class="bi bi-clipboard small"></i>
                                        </button>
                                    </div>
                                </td>
                                <!-- <td class="text-end pe-4">
                                    <button class="btn btn-light btn-sm rounded-circle"><i class="bi bi-eye"></i></button>
                                    <button class="btn btn-light btn-sm rounded-circle ms-1"><i class="bi bi-clipboard"></i></button>
                                </td> -->
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-5">
                                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="50" class="opacity-25 mb-3">
                                    <p class="text-muted mb-0">No URLs found in this workspace.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($urls->count() > 0)
            <div class="card-footer bg-white border-0 py-3 text-center">
                <a href="{{ route('short-urls.index') }}" class="text-decoration-none small fw-bold">View All Links <i class="bi bi-chevron-right"></i></a>
            </div>
        @endif
    </div>

</div>
@endsection

@push('styles')
<style>
    /* Custom sub-colors for badges and icons */
    .bg-primary-subtle { background-color: #e0e7ff !important; }
    .bg-success-subtle { background-color: #dcfce7 !important; }
    .bg-warning-subtle { background-color: #fef9c3 !important; }
    .bg-blue-subtle { background-color: #eff6ff !important; }
    
    .table thead th {
        font-size: 11px;
        letter-spacing: 0.5px;
    }
</style>
@endpush