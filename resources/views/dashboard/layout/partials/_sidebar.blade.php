<aside class="sidebar text-white">
    <div class="sidebar-brand">
        <i class="bi bi-link-45deg fs-3 text-primary"></i>
        <span class="ms-2 fw-bold fs-5">ShortLink <span class="text-primary">Pro</span></span>
    </div>

    <div class="nav flex-column mt-3">
        <a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        @if(auth()->user()->is_superadmin)
            <div class="px-4 small text-uppercase opacity-50 mt-4 mb-2" style="font-size: 10px;">Admin Management</div>
            <a href="/companies" class="nav-link {{ request()->is('companies*') ? 'active' : '' }}">
                <i class="bi bi-buildings"></i> All Companies
            </a>
        @endif
        @if(auth()->user()->is_superadmin || currentUserRole() == 'admin')
            <a href="{{ route('users.index') }}" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                <i class="bi bi-person-plus"></i> Users
            </a>
        @endif
        <a href="{{ route('invites.index') }}" class="nav-link {{ request()->is('invites*') ? 'active' : '' }}">
           <i class="bi bi-envelope-plus"></i> Invites
       </a>

        <div class="px-4 small text-uppercase opacity-50 mt-4 mb-2" style="font-size: 10px;">Resources</div>
        <a href="{{ route('short-urls.index') }}" class="nav-link {{ request()->is('short-urls*') ? 'active' : '' }}">
            <i class="bi bi-link-45deg"></i> Short URLs
        </a>

        <div class="mt-auto p-4">
            <form method="POST" action="/logout">
                @csrf
                <button class="btn btn-outline-danger btn-sm w-100 py-2">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </button>
            </form>
        </div>
    </div>
</aside>