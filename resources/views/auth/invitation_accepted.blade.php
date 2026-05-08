<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accept Invitation | {{ $company->name }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --brand-dark: #161e2d; /* Matching your image */
            --brand-blue: #3b82f6;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-card {
            background: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            border: none;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            max-width: 1000px;
            width: 100%;
        }

        /* Information Side (Dark Theme) */
        .info-side {
            background-color: var(--brand-dark);
            color: #ffffff;
            padding: 4rem 3.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* Form Side (Light Theme) */
        .form-side {
            padding: 4rem 3.5rem;
        }

        .invite-pill {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
            padding: 8px 16px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 2rem;
        }

        .role-tag {
            background: var(--brand-blue);
            color: white;
            font-size: 10px;
            font-weight: 800;
            padding: 2px 8px;
            border-radius: 4px;
            text-transform: uppercase;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #334155;
            margin-bottom: 8px;
        }

        .form-control {
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
            font-size: 0.95rem;
        }

        .form-control:focus {
            border-color: var(--brand-blue);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .btn-join {
            background-color: var(--brand-dark);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            width: 100%;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }

        .btn-join:hover {
            background-color: #000;
            transform: translateY(-2px);
        }

        .feature-point {
            display: flex;
            gap: 15px;
            margin-bottom: 1.5rem;
        }

        .icon-box {
            background: rgba(255,255,255,0.05);
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--brand-blue);
            flex-shrink: 0;
        }

        @media (max-width: 991px) {
            .info-side { order: -1; padding: 2rem; }
            .form-side { padding: 2.5rem; }
        }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">

            <div class="text-center mb-4">
                <div class="avatar-lg bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="bi bi-envelope-check fs-1"></i>
                </div>
                <h2 class="fw-bold text-dark">Join Organization</h2>
                <p class="text-muted">Complete your details to accept the invitation and get started.</p>
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="{{ route('joinregister', ['token' => $invite->token]) }}">
                        @csrf

                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label text-uppercase small fw-bold text-muted">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-person text-secondary"></i></span>
                                    <input type="text"
                                           name="name"
                                           class="form-control bg-light border-0 py-2 shadow-none {{ $user ? 'text-muted' : '' }}"
                                           value="{{ $user->name ?? '' }}"
                                           {{ $user ? 'readonly' : '' }}
                                           placeholder="Enter your full name"
                                           required>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label text-uppercase small fw-bold text-muted">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-envelope text-secondary"></i></span>
                                    <input type="email"
                                           name="email"
                                           class="form-control bg-light border-0 py-2 shadow-none {{ $user ? 'text-muted' : '' }}"
                                           value="{{ $user->email ?? '' }}"
                                           {{ $user ? 'readonly' : '' }}
                                           placeholder="email@example.com"
                                           required>
                                </div>
                            </div>

                            @if(!$user)
                            <div class="col-12">
                                <label class="form-label text-uppercase small fw-bold text-muted">Create Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-lock text-secondary"></i></span>
                                    <input type="password"
                                           name="password"
                                           class="form-control bg-light border-0 py-2 shadow-none"
                                           placeholder="Min. 8 characters"
                                           required>
                                </div>
                                <div class="form-text small text-muted">Make sure it's secure and unique.</div>
                            </div>
                            @else
                            <div class="col-12">
                                <div class="alert alert-light border-0 rounded-3 mb-0 py-2">
                                    <small class="text-muted"><i class="bi bi-info-circle me-1"></i> Since you already have an account, just click below to join.</small>
                                </div>
                            </div>
                            @endif

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm">
                                    Accept & Join Organization <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <p class="text-center text-muted small mt-4">
                By joining, you agree to the organization's terms and privacy policies.
            </p>

        </div>
    </div>
</div>

@push('styles')
<style>
    .bg-primary-subtle { background-color: #e0e7ff !important; }
    .input-group-text { border-radius: 12px 0 0 12px !important; }
    .form-control { border-radius: 0 12px 12px 0 !important; }
    .btn-primary { background-color: #3b82f6; border: none; transition: 0.3s; }
    .btn-primary:hover { background-color: #2563eb; transform: translateY(-1px); }
    .card { border: 1px solid rgba(0,0,0,0.05) !important; }
</style>
@endpush
</body>
</html>

