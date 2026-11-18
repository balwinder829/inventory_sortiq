@extends('inventory-frontend.layouts.app')

@section('title', 'Register Sourcing Profile - StockFlow Systems')

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-12rem)] px-4 py-12" x-data="signupApp()">
    <div class="w-full max-w-md bg-white border border-slate-100 rounded-3xl shadow-2xl overflow-hidden text-left">
        <!-- Card Header -->
        <div class="p-8 text-center bg-[#2874F0] text-white">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white/10 text-white mb-3 shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            </div>
            <h2 class="text-2xl font-black tracking-tight">Create Profile</h2>
            <p class="text-xs text-white/80 mt-1 font-medium">Institutional Sourcing Access Console</p>
        </div>

        <!-- Form Body -->
        <form @submit.prevent="handleSignup()" class="p-8 space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Full Name</label>
                <input type="text" required x-model="name" class="w-full h-11 px-4 text-xs text-slate-800 bg-[#F1F3F6] border border-transparent rounded-xl focus:bg-white focus:outline-none focus:border-[#2874F0]/40 font-medium transition-all" />
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Official Corporate Email</label>
                <input type="email" required x-model="email" class="w-full h-11 px-4 text-xs text-slate-800 bg-[#F1F3F6] border border-transparent rounded-xl focus:bg-white focus:outline-none focus:border-[#2874F0]/40 font-medium transition-all" />
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Institution / Organization Name</label>
                <input type="text" required x-model="org" class="w-full h-11 px-4 text-xs text-slate-800 bg-[#F1F3F6] border border-transparent rounded-xl focus:bg-white focus:outline-none focus:border-[#2874F0]/40 font-medium transition-all" />
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Master Password</label>
                <div class="relative">
                    <input :type="showPass ? 'text' : 'password'" required x-model="password" class="w-full h-11 pl-4 pr-10 text-xs text-slate-800 bg-[#F1F3F6] border border-transparent rounded-xl focus:bg-white focus:outline-none focus:border-[#2874F0]/40 font-medium transition-all" />
                    <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600">
                        <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg x-show="showPass" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" required id="agree" checked class="w-4 h-4 text-[#2874F0] rounded border-slate-300 focus:ring-0">
                <label for="agree" class="text-xs text-slate-500">I agree to corporate terms & SLA privacy guidelines</label>
            </div>

            <button type="submit" :disabled="submitting" class="w-full py-3.5 bg-[#2874F0] hover:bg-[#2874F0]/90 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-blue-500/20 hover:shadow-xl transition-all cursor-pointer mt-2">
                <span x-show="!submitting">Create Sourcing Account</span>
                <span x-show="submitting" x-cloak>Registering Account...</span>
            </button>

            <p class="text-center text-xs text-slate-500 pt-2">
                Already registered? 
                <a href="{{ url('/login') }}" class="font-bold text-[#2874F0] hover:underline">Sign In</a>
            </p>
        </form>
    </div>
</div>

<script>
function signupApp() {
    return {
        name: 'Sarah Connor',
        email: 'buyer@stockflow.com',
        org: 'Apex Enterprise Logistics',
        password: 'corporate123',
        showPass: false,
        submitting: false,
        handleSignup() {
            if (!this.name || !this.email || !this.password) return;
            this.submitting = true;
            setTimeout(() => {
                Alpine.store('cart').login(this.email, this.name);
                this.submitting = false;
                window.location.href = '{{ url('/dashboard') }}';
            }, 700);
        }
    };
}
</script>
@endsection
