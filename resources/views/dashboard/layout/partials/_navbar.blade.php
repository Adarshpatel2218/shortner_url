<nav class="top-navbar">
    <div class="d-flex align-items-center">
        <button class="btn d-lg-none me-3" onclick="toggleSidebar()">
            <i class="bi bi-list fs-4"></i>
        </button>
        <h5 class="mb-0 fw-semibold text-dark">Welcome back, {{ auth()->user()->name }}</h5>
    </div>
    
    <div class="d-flex align-items-center gap-3">
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2">
            {{ auth()->user()->is_superadmin ? 'Super Admin' : currentUserRole() }}
        </span>
        <div class="vr mx-2 text-secondary opacity-25"></div>
        <i class="bi bi-person-circle fs-4 text-secondary"></i>
    </div>
</nav>