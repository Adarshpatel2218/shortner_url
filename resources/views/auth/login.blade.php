<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal - URL Shortener</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .gradient-bg {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-0 md:p-6">

    <div class="bg-white w-full max-w-5xl min-h-[600px] flex flex-col md:flex-row rounded-none md:rounded-3xl shadow-2xl overflow-hidden border border-slate-200">
        
        <div class="gradient-bg md:w-1/2 p-12 text-white flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-2 mb-12">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight">ShortLink <span class="text-blue-400">Pro</span></span>
                </div>

                <h1 class="text-4xl font-bold leading-tight mb-6">Master Your Links. <br><span class="text-blue-400">Scale Your Reach.</span></h1>
                
                <ul class="space-y-6">
                    <li class="flex items-start gap-4">
                        <div class="mt-1 bg-white/10 p-1 rounded-md">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-lg">Advanced Analytics</h4>
                            <p class="text-slate-400 text-sm">Track every click with geographical and device-level data.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-4">
                        <div class="mt-1 bg-white/10 p-1 rounded-md">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-lg">SuperAdmin Control</h4>
                            <p class="text-slate-400 text-sm">Manage users, domains, and global settings from one place.</p>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="pt-8 border-t border-white/10">
                <p class="text-xs text-slate-500">© 2026 ShortLink Pro v2.4. Built for Enterprises.</p>
            </div>
        </div>

        <div class="md:w-1/2 p-12 flex flex-col justify-center bg-white">
            <div class="max-w-sm mx-auto w-full">
                <div class="mb-10">
                    <h2 class="text-3xl font-bold text-slate-800">Admin Login</h2>
                    <p class="text-slate-500 mt-2">Enter your credentials to access the panel</p>
                </div>

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm flex items-center rounded-r-lg">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="/login" id="loginForm" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Work Email</label>
                        <input type="email" name="email" id="email" 
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all placeholder:text-slate-400"
                            placeholder="admin@company.com" value="{{ old('email') }}" required>
                        <p id="emailError" class="text-xs text-red-500 mt-1 hidden">Invalid email format</p>
                    </div>

                    <div>
                        <div class="flex justify-between mb-2">
                            <label class="block text-sm font-semibold text-slate-700">Password</label>
                        </div>
                        <input type="password" name="password" id="password" 
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all placeholder:text-slate-400"
                            placeholder="••••••••" required>
                        <p id="passwordError" class="text-xs text-red-500 mt-1 hidden">Min. 8 characters required</p>
                    </div>


                    <button type="submit" id="submitBtn"
                        class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-4 rounded-xl transition-all transform active:scale-[0.98] shadow-xl shadow-slate-200 flex justify-center items-center">
                        <span>Sign In to Dashboard</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('loginForm');
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        const submitBtn = document.getElementById('submitBtn');

        form.addEventListener('submit', function(e) {
            let hasError = false;

            if (!email.value.includes('@')) {
                document.getElementById('emailError').classList.remove('hidden');
                email.classList.add('border-red-500');
                hasError = true;
            } else {
                document.getElementById('emailError').classList.add('hidden');
                email.classList.remove('border-red-500');
            }

            if (password.value.length < 8) {
                document.getElementById('passwordError').classList.remove('hidden');
                password.classList.add('border-red-500');
                hasError = true;
            } else {
                document.getElementById('passwordError').classList.add('hidden');
                password.classList.remove('border-red-500');
            }

            if (hasError) {
                e.preventDefault();
            } else {
                submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
                submitBtn.disabled = true;
            }
        });
    </script>
</body>
</html>