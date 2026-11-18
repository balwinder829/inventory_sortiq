<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Admin Account - StockFlow Systems</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#2874F0',
                            600: '#1a5bc7',
                            700: '#15469b',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="h-full font-sans antialiased bg-[#F8FAFC] text-slate-800 flex flex-col justify-center py-12 sm:px-6 lg:px-8 selection:bg-[#2874F0] selection:text-white relative overflow-x-hidden">

    <!-- Background Accents -->
    <div class="fixed inset-0 pointer-events-none -z-10 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Header / Brand -->
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center space-y-3">
        <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2.5">
            <img src="{{ asset('assets/inventory/navbarlogo.png') }}" 
                 alt="StockFlow Systems" 
                 class="h-16 w-auto object-contain max-w-[280px]" 
                 onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'flex items-center gap-2\'><span class=\'w-10 h-10 rounded-xl bg-[#2874F0] flex items-center justify-center text-white font-black text-xl shadow-md\'>S</span><span class=\'font-black text-2xl tracking-tight text-slate-800\'>Stock<span class=\'text-[#2874F0]\'>Flow</span></span></div>';" />
        </a>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-100 text-[10px] font-bold text-blue-700 uppercase tracking-wider">
            <span class="w-2 h-2 rounded-full bg-[#2874F0]"></span>
            Create Admin Account
        </div>
    </div>

    <!-- Form Container Card -->
    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0">
        <div class="bg-white py-8 px-6 sm:px-10 shadow-2xl rounded-3xl border border-slate-100 space-y-6">
            
            <div class="space-y-1">
                <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Register New Admin</h2>
                <p class="text-xs text-slate-500 font-medium">Create your administrative credentials to manage inventory and stores.</p>
            </div>

            <!-- Error Alerts -->
            @if ($errors->any())
                <div class="p-4 bg-red-50 border border-red-200 rounded-2xl space-y-1">
                    <div class="flex items-center gap-2 text-xs font-bold text-red-700">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Registration Failed</span>
                    </div>
                    @foreach ($errors->all() as $error)
                        <p class="text-xs text-red-600 pl-6">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Register Form -->
            <form action="{{ url('/register') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Name Input -->
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                        Full Name
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </span>
                        <input id="name" 
                               type="text" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               autocomplete="name" 
                               autofocus
                               placeholder="e.g. Alex Henderson" 
                               class="w-full h-11 pl-10 pr-4 text-xs text-slate-800 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-[#2874F0] focus:ring-1 focus:ring-[#2874F0] font-medium transition-all" />
                    </div>
                </div>

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                        Work Email Address
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </span>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autocomplete="email" 
                               placeholder="admin@yourcompany.com" 
                               class="w-full h-11 pl-10 pr-4 text-xs text-slate-800 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-[#2874F0] focus:ring-1 focus:ring-[#2874F0] font-medium transition-all" />
                    </div>
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                        Password (Min. 6 Characters)
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </span>
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required 
                               autocomplete="new-password"
                               placeholder="Create a strong password" 
                               class="w-full h-11 pl-10 pr-10 text-xs text-slate-800 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-[#2874F0] focus:ring-1 focus:ring-[#2874F0] font-medium transition-all" />
                        <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Password Confirmation Input -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                        Confirm Password
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </span>
                        <input id="password_confirmation" 
                               type="password" 
                               name="password_confirmation" 
                               required 
                               autocomplete="new-password"
                               placeholder="Repeat password" 
                               class="w-full h-11 pl-10 pr-10 text-xs text-slate-800 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-[#2874F0] focus:ring-1 focus:ring-[#2874F0] font-medium transition-all" />
                        <button type="button" onclick="togglePassword('password_confirmation')" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Terms notice -->
                <p class="text-[11px] text-slate-500 leading-relaxed">
                    By registering, you obtain Super Admin permissions for this StockFlow instance.
                </p>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3.5 bg-[#2874F0] hover:bg-[#2874F0]/90 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-blue-500/20 hover:shadow-xl transition-all cursor-pointer">
                    Complete Registration & Login
                </button>
            </form>

            <!-- Footer Link to Login -->
            <div class="pt-2 text-center text-xs text-slate-500">
                Already have an account? 
                <a href="{{ route('login') }}" class="font-bold text-[#2874F0] hover:underline">
                    Sign in to Console
                </a>
            </div>

        </div>

        <!-- Back to Website Link -->
        <div class="text-center mt-6">
            <a href="{{ url('/') }}" class="text-xs font-bold text-slate-500 hover:text-[#2874F0] inline-flex items-center gap-1.5 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Back to Portal Home</span>
            </a>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const pass = document.getElementById(fieldId);
            if (pass.type === 'password') {
                pass.type = 'text';
            } else {
                pass.type = 'password';
            }
        }
    </script>
</body>
</html>
