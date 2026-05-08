<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') | ShortLink Pro</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    //jquery
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>


    <style>
        :root {
            --sidebar-bg: #111827;
            --sidebar-hover: #1f2937;
            --accent-blue: #3b82f6;
            --top-nav-height: 65px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            left: 0; top: 0;
            z-index: 1000;
            transition: all 0.3s;
        }

        .sidebar-brand {
            height: var(--top-nav-height);
            display: flex;
            align-items: center;
            padding: 0 25px;
            background: rgba(0,0,0,0.1);
        }

        .nav-link {
            color: #9ca3af;
            padding: 12px 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            transition: 0.2s;
        }

        .nav-link:hover, .nav-link.active {
            color: #fff;
            background: var(--sidebar-hover);
            border-left: 4px solid var(--accent-blue);
        }

        /* Main Content Wrapper */
        .main-wrapper {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .top-navbar {
            height: var(--top-nav-height);
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            padding: 0 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0; z-index: 999;
        }

        .content-area {
            padding: 30px;
            flex: 1;
        }

        footer {
            background: #fff;
            padding: 20px 30px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 0.9rem;
        }

        @media (max-width: 992px) {
            .sidebar { margin-left: -260px; }
            .main-wrapper { margin-left: 0; }
            .sidebar.active { margin-left: 0; }
        }

        @stack('styles')
    </style>
</head>
<body>

    @include('dashboard.layout.partials._sidebar')

    <div class="main-wrapper">
        @include('dashboard.layout.partials._navbar')

        <main class="content-area">
            @yield('content')
        </main>

        @include('dashboard.layout.partials._footer')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
        }
    </script>
    @stack('scripts')
</body>
</html>