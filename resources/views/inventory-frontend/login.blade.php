@extends('inventory-frontend.layouts.app')

@section('title', 'Operator Sign In - StockFlow Systems Portal')

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-12rem)] px-4 py-12" x-data="loginApp()">
    <div class="w-full max-w-md bg-white border border-slate-100 rounded-3xl shadow-2xl overflow-hidden text-left">
        <!-- Card Header -->
        <div class="p-8 text-center bg-[#2874F0] text-white">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white/10 text-white mb-3 shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <h2 class="text-2xl font-black tracking-tight">StockFlow Portal</h2>
            <p class="text-xs text-white/80 mt-1 font-medium">Corporate Sourcing & Request Access Console</p>
        </div>

        <!-- Form Body -->
        <form @submit.prevent="handleLogin()" class="p-8 space-y-5">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Corporate Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </span>
                    <input type="email" required x-model="email" class="w-full h-11 pl-10 pr-4 text-xs text-slate-800 bg-[#F1F3F6] border border-transparent rounded-xl focus:bg-white focus:outline-none focus:border-[#2874F0]/40 font-medium transition-all" />
                </div>
            </div>

            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Access Password</label>
                    <a href="#" class="text-xs font-bold text-[#2874F0] hover:underline" @click.prevent="$store.cart.notify('Password reset email sent to corporate admin.')">Forgot?</a>
                </div>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </span>
                    <input :type="showPass ? 'text' : 'password'" required x-model="password" class="w-full h-11 pl-10 pr-10 text-xs text-slate-800 bg-[#F1F3F6] border border-transparent rounded-xl focus:bg-white focus:outline-none focus:border-[#2874F0]/40 font-medium transition-all" />
                    <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600">
                        <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg x-show="showPass" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                    </button>
                </div>
            </div>

            <div class="space-y-1">
                <p class="text-xs text-slate-500">Sign in with your enterprise credentials to access your operator dashboard.</p>
            </div>

            <button type="submit" :disabled="submitting" class="w-full py-3.5 bg-[#2874F0] hover:bg-[#2874F0]/90 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-blue-500/20 hover:shadow-xl transition-all cursor-pointer">
                <span x-show="!submitting">Authenticate Session</span>
                <span x-show="submitting" x-cloak>Logging In...</span>
            </button>

            <p class="text-center text-xs text-slate-500 pt-2">
                New enterprise organization? 
                <a href="{{ url('/signup') }}" class="font-bold text-[#2874F0] hover:underline">Register Profile</a>
            </p>
        </form>
    </div>
</div>

<script>
function loginApp() {
    return {
        email: '',
        password: '',
        showPass: false,
        submitting: false,
        handleLogin() {
            if (!this.email || !this.password) return;
            this.submitting = true;
            setTimeout(() => {
                Alpine.store('cart').login(this.email, 'Sarah Connor');
                this.submitting = false;
                window.location.href = '{{ url('/dashboard') }}';
            }, 700);
        }
    };
}
</script>
@endsection
