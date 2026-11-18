<header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-100 shadow-xs" x-data="{ mobileMenu: false }">
    <!-- Top notification banner -->
    <div class="bg-[#2874F0]/5 border-b border-slate-100 py-1.5 px-6 text-center flex items-center justify-center gap-2">
        <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
        <span class="text-[10px] font-bold text-slate-500 tracking-wide uppercase">
            Live Inventory Status: Fully Operational
        </span>
    </div>

    <div class="w-full max-w-[1920px] mx-auto px-4 md:px-8 lg:px-12 xl:px-16 h-20 flex items-center justify-between">
        <!-- Logo -->
        <a href="{{ url('/') }}" class="flex items-center gap-3">
            <img src="{{ asset('assets/inventory/navbarlogo.png') }}" 
                 alt="StockFlow Systems" 
                 class="h-14 md:h-16 w-auto object-contain max-w-[260px]" 
                 onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'flex items-center gap-2\'><span class=\'w-10 h-10 rounded-xl bg-[#2874F0] flex items-center justify-center text-white font-black text-xl shadow-md\'>S</span><span class=\'font-black text-xl tracking-tight text-slate-800\'>Stock<span class=\'text-[#2874F0]\'>Flow</span></span></div>';" />
        </a>

        <!-- Desktop Navigation -->
        <nav class="hidden xl:flex items-center gap-7">
            <a href="{{ url('/') }}" class="text-xs font-bold uppercase tracking-wider transition-colors {{ request()->is('/') ? 'text-[#2874F0]' : 'text-slate-600 hover:text-[#2874F0]' }}">
                Home
            </a>
            <a href="{{ url('/about') }}" class="text-xs font-bold uppercase tracking-wider transition-colors {{ request()->is('about*') ? 'text-[#2874F0]' : 'text-slate-600 hover:text-[#2874F0]' }}">
                About Us
            </a>
            <a href="{{ url('/features') }}" class="text-xs font-bold uppercase tracking-wider transition-colors {{ request()->is('features*') ? 'text-[#2874F0]' : 'text-slate-600 hover:text-[#2874F0]' }}">
                Features
            </a>
            <a href="{{ url('/modules') }}" class="text-xs font-bold uppercase tracking-wider transition-colors {{ request()->is('modules*') ? 'text-[#2874F0]' : 'text-slate-600 hover:text-[#2874F0]' }}">
                Modules
            </a>
            <a href="{{ url('/products') }}" class="text-xs font-bold uppercase tracking-wider transition-colors {{ request()->is('products*') ? 'text-[#2874F0]' : 'text-slate-600 hover:text-[#2874F0]' }}">
                Live Catalog
            </a>
            <a href="{{ url('/pricing') }}" class="text-xs font-bold uppercase tracking-wider transition-colors {{ request()->is('pricing*') ? 'text-[#2874F0]' : 'text-slate-600 hover:text-[#2874F0]' }}">
                Pricing
            </a>
            <a href="{{ url('/contact') }}" class="text-xs font-bold uppercase tracking-wider transition-colors {{ request()->is('contact*') ? 'text-[#2874F0]' : 'text-slate-600 hover:text-[#2874F0]' }}">
                Contact Us
            </a>
            <a href="{{ url('/blogs') }}" class="text-xs font-bold uppercase tracking-wider transition-colors {{ request()->is('blogs*') ? 'text-[#2874F0]' : 'text-slate-600 hover:text-[#2874F0]' }}">
                Blogs
            </a>
        </nav>

        <!-- Right action icons -->
        <div class="flex items-center gap-4">
            <!-- Saved / Wishlist Button -->
            <a href="{{ url('/dashboard') }}" class="relative p-2 rounded-full text-slate-500 hover:text-red-500 hover:bg-slate-50 transition-colors" title="Saved Items">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <template x-if="$store.cart.wishlistCount > 0">
                    <span class="absolute top-1.5 right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[9px] font-bold text-white ring-2 ring-white" x-text="$store.cart.wishlistCount"></span>
                </template>
            </a>

            <!-- Quote Request Basket Button -->
            <button @click="$store.cart.drawerOpen = true" class="relative p-2 rounded-full text-slate-500 hover:text-[#2874F0] hover:bg-slate-50 transition-colors cursor-pointer" title="Quote Request Basket">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <template x-if="$store.cart.count > 0">
                    <span class="absolute top-1.5 right-1.5 flex h-4.5 w-4.5 items-center justify-center rounded-full bg-[#2874F0] text-[9px] font-bold text-white ring-2 ring-white animate-pulse" x-text="$store.cart.count"></span>
                </template>
            </button>

            <!-- Authentication / User Area -->
            <template x-if="$store.cart.user">
                <div class="flex items-center gap-3 pl-2 border-l border-slate-100">
                    <a href="{{ url('/dashboard') }}" class="hidden sm:flex items-center gap-2 text-xs font-bold text-slate-700 hover:text-[#2874F0]">
                        <div class="w-7 h-7 rounded-full bg-[#2874F0]/10 text-[#2874F0] flex items-center justify-center text-xs font-bold border border-[#2874F0]/10" x-text="$store.cart.user.name.charAt(0)"></div>
                        <span class="max-w-[90px] truncate" x-text="$store.cart.user.name"></span>
                    </a>
                    <button @click="$store.cart.logout()" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Sign Out">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </div>
            </template>

            <template x-if="!$store.cart.user">
                <div class="flex items-center gap-3">
                    <a href="{{ url('/login') }}" class="hidden sm:inline-flex items-center justify-center text-xs font-bold text-slate-600 hover:text-[#2874F0] transition-colors">
                        Sign In
                    </a>
                    <a href="{{ url('/#demo-form') }}" class="px-5 py-2.5 bg-[#2874F0] hover:bg-[#2874F0]/90 text-white text-xs font-bold rounded-full shadow-md shadow-blue-500/10 hover:shadow-lg transition-all duration-300">
                        Book Demo
                    </a>
                </div>
            </template>

            <!-- Mobile Hamburger Button -->
            <button @click="mobileMenu = !mobileMenu" class="p-2 text-slate-500 hover:text-slate-700 xl:hidden focus:outline-none rounded-lg hover:bg-slate-50">
                <svg x-show="!mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="mobileMenu" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Drawer Menu -->
    <div x-show="mobileMenu" x-cloak @click.away="mobileMenu = false" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="xl:hidden border-t border-slate-100 bg-white px-6 py-6 space-y-4 shadow-2xl text-left">
        <a href="{{ url('/') }}" class="block text-xs font-bold uppercase tracking-wider text-slate-700 hover:text-[#2874F0]">Home</a>
        <a href="{{ url('/about') }}" class="block text-xs font-bold uppercase tracking-wider text-slate-700 hover:text-[#2874F0]">About Us</a>
        <a href="{{ url('/features') }}" class="block text-xs font-bold uppercase tracking-wider text-slate-700 hover:text-[#2874F0]">Features</a>
        <a href="{{ url('/modules') }}" class="block text-xs font-bold uppercase tracking-wider text-slate-700 hover:text-[#2874F0]">Modules</a>
        <a href="{{ url('/products') }}" class="block text-xs font-bold uppercase tracking-wider text-slate-700 hover:text-[#2874F0]">Live Catalog</a>
        <a href="{{ url('/pricing') }}" class="block text-xs font-bold uppercase tracking-wider text-slate-700 hover:text-[#2874F0]">Pricing</a>
        <a href="{{ url('/contact') }}" class="block text-xs font-bold uppercase tracking-wider text-slate-700 hover:text-[#2874F0]">Contact Us</a>
        <a href="{{ url('/blogs') }}" class="block text-xs font-bold uppercase tracking-wider text-slate-700 hover:text-[#2874F0]">Blogs</a>
        <div class="pt-4 border-t border-slate-100 flex gap-4">
            <a href="{{ url('/login') }}" class="flex-1 text-center py-2.5 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 hover:border-[#2874F0] hover:text-[#2874F0]">
                Sign In
            </a>
            <a href="{{ url('/#demo-form') }}" class="flex-1 text-center py-2.5 bg-[#2874F0] hover:bg-[#2874F0]/90 rounded-xl text-xs font-bold text-white shadow-md shadow-blue-500/10">
                Book Demo
            </a>
        </div>
    </div>
</header>
