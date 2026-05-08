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
    <div class="auth-card row g-0 mx-auto">
        
       

        <div class="col-lg-6 info-side">
            <div class="invite-pill">
                <div class="icon-box m-0" style="background: white;">
                    <i class="bi bi-buildings-fill text-dark"></i>
                </div>
                <div>
                    <div class="role-tag">{{ $invite->role }}</div>
                    <div class="fw-bold small text-white">{{ $company->name }}</div>
                </div>
            </div>

            <h1 class="display-6 fw-bold mb-4">You've been <br><span class="text-primary">Invited to Join.</span></h1>
            
            <div class="mt-4">
                <div class="feature-point">
                    <div class="icon-box"><i class="bi bi-check-lg"></i></div>
                    <div>
                        <h6 class="mb-0 fw-bold">Enterprise Access</h6>
                        <p class="small text-secondary">Join {{ $company->name }}'s private workspace with your role permissions.</p>
                    </div>
                </div>

                <div class="feature-point">
                    <div class="icon-box"><i class="bi bi-link-45deg"></i></div>
                    <div>
                        <h6 class="mb-0 fw-bold">Link Management</h6>
                        <p class="small text-secondary">Create, track and manage short links at scale with your team.</p>
                    </div>
                </div>

                <div class="feature-point">
                    <div class="icon-box"><i class="bi bi-shield-check"></i></div>
                    <div>
                        <h6 class="mb-0 fw-bold">Secure Gateway</h6>
                        <p class="small text-secondary">All your links are protected with enterprise-grade encryption.</p>
                    </div>
                </div>
            </div>

            <div class="mt-auto pt-5 border-top border-secondary">
                <p class="small text-secondary mb-0">© 2026 ShortLink Pro v2.4. Secured Invitation System.</p>
            </div>
        </div>
         <div class="col-lg-6 form-side">
            <div class="mb-5">
                <h2 class="fw-bold mb-1">Create Account</h2>
                <p class="text-muted">Set up your profile to join the team.</p>
            </div>

            <form method="POST" action="{{ route('register', ['token' => $invite->token]) }}">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label text-uppercase small">Full Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Rahul Sharma" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-uppercase small">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="name@company.com" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-uppercase small">Mobile</label>
                        <input type="text" name="mobile" class="form-control" placeholder="+91 00000 00000" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label text-uppercase small">Create Password</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>

                    <div class="col-12 mt-4">
                        <button type="submit" class="btn-join">
                            Accept & Join Organization
                        </button>
                        <p class="text-center mt-3 small text-muted">
                            Already have an account? <a href="/login" class="text-decoration-none fw-bold text-primary">Login</a>
                        </p>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

</body>
</html>