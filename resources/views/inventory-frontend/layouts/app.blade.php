<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $seoMeta = \App\Helpers\SeoHelper::getMeta();
    @endphp

    <title>@hasSection('title')@yield('title'){{ !empty($seoMeta['title']) ? ' | ' : '' }}StockFlow Systems@else{{ $seoMeta['title'] }}@endif</title>
    <meta name="description" content="@hasSection('description')@yield('description')@else{{ $seoMeta['description'] }}@endif">
    @if(!empty($seoMeta['keywords']))
    <meta name="keywords" content="{{ $seoMeta['keywords'] }}">
    @endif
    <meta name="robots" content="{{ $seoMeta['robots'] }}">
    <link rel="canonical" href="{{ $seoMeta['canonical'] }}">

    <!-- Open Graph (Facebook, LinkedIn) -->
    <meta property="og:type" content="{{ $seoMeta['og_type'] }}">
    <meta property="og:url" content="{{ $seoMeta['canonical'] }}">
    <meta property="og:title" content="{{ $seoMeta['og_title'] }}">
    <meta property="og:description" content="{{ $seoMeta['og_description'] }}">
    @if(!empty($seoMeta['og_image']))
    <meta property="og:image" content="{{ $seoMeta['og_image'] }}">
    @endif

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="{{ $seoMeta['twitter_card'] }}">
    <meta name="twitter:title" content="{{ $seoMeta['og_title'] }}">
    <meta name="twitter:description" content="{{ $seoMeta['og_description'] }}">
    @if(!empty($seoMeta['og_image']))
    <meta name="twitter:image" content="{{ $seoMeta['og_image'] }}">
    @endif

    <!-- Dynamic Header Tracking Scripts (Google Analytics, GTM, Meta Pixel) -->
    {!! \App\Helpers\SeoHelper::renderHeaderScripts() !!}

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2874F0',
                        'primary-dark': '#1a5bc7',
                        'primary-light': '#5191f7',
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            500: '#2874F0',
                            600: '#1a5bc7',
                            700: '#15469b',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    boxShadow: {
                        'xs': '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
                        'glow': '0 0 25px -5px rgba(40, 116, 240, 0.3)',
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; }
    </style>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('cart', {
                items: JSON.parse(localStorage.getItem('sf_cart_items') || '[]'),
                wishlist: JSON.parse(localStorage.getItem('sf_wishlist') || '[]'),
                user: JSON.parse(localStorage.getItem('portal_user') || 'null'),
                drawerOpen: false,
                notes: '',
                quotes: JSON.parse(localStorage.getItem('sf_quotes') || JSON.stringify([
                    { id: "REQ-2026-8801", date: "2026-05-24", items: [{ name: "iPhone 15 Pro Max", qty: 2, price: 1199 }], total: 2398, status: "Approved" },
                    { id: "REQ-2026-8802", date: "2026-05-26", items: [{ name: "Sony WH-1000XM5", qty: 1, price: 349 }, { name: "Wireless Charging Pad", qty: 3, price: 39 }], total: 466, status: "Pending Review" }
                ])),
                toasts: [],

                get count() {
                    return this.items.reduce((acc, curr) => acc + curr.quantity, 0);
                },

                get wishlistCount() {
                    return this.wishlist.length;
                },

                get total() {
                    return this.items.reduce((acc, curr) => acc + (curr.price * curr.quantity), 0);
                },

                save() {
                    localStorage.setItem('sf_cart_items', JSON.stringify(this.items));
                    localStorage.setItem('sf_wishlist', JSON.stringify(this.wishlist));
                    localStorage.setItem('sf_quotes', JSON.stringify(this.quotes));
                },

                addItem(product, qty = 1) {
                    const existing = this.items.find(i => i.id === product.id);
                    if (existing) {
                        existing.quantity += qty;
                    } else {
                        this.items.push({
                            id: product.id,
                            name: product.name,
                            sku: product.sku,
                            price: product.price,
                            image: product.image,
                            quantity: qty,
                            stock: product.stock
                        });
                    }
                    this.save();
                    this.notify(`Added "${product.name}" to sourcing basket!`);
                },

                updateQty(id, delta) {
                    const item = this.items.find(i => i.id === id);
                    if (!item) return;
                    item.quantity += delta;
                    if (item.quantity <= 0) {
                        this.items = this.items.filter(i => i.id !== id);
                    }
                    this.save();
                },

                removeItem(id) {
                    this.items = this.items.filter(i => i.id !== id);
                    this.save();
                    this.notify('Item removed from basket');
                },

                toggleWishlist(productId) {
                    if (!this.user) {
                        this.notify('Please sign in to save items to your wishlist', 'error');
                        return;
                    }
                    if (this.wishlist.includes(productId)) {
                        this.wishlist = this.wishlist.filter(id => id !== productId);
                        this.notify('Item removed from saved items');
                    } else {
                        this.wishlist.push(productId);
                        this.notify('Added to saved items!');
                    }
                    this.save();
                },

                submitQuote() {
                    if (this.items.length === 0) return;
                    const newReq = {
                        id: 'REQ-2026-' + Math.floor(1000 + Math.random() * 9000),
                        date: new Date().toISOString().split('T')[0],
                        items: this.items.map(i => ({ name: i.name, qty: i.quantity, price: i.price })),
                        total: this.total,
                        status: 'Pending Review',
                        notes: this.notes
                    };
                    this.quotes.unshift(newReq);
                    this.items = [];
                    this.notes = '';
                    this.drawerOpen = false;
                    this.save();
                    this.notify(`Sourcing request dispatched! Tracking Key: ${newReq.id}`);
                },

                login(email, name = 'Sarah Connor') {
                    this.user = { name: name, email: email, role: 'Registered Sourcing Buyer' };
                    localStorage.setItem('portal_user', JSON.stringify(this.user));
                    this.notify(`Welcome back, ${this.user.name}!`);
                },

                logout() {
                    this.user = null;
                    localStorage.removeItem('portal_user');
                    this.notify('Signed out successfully');
                },

                notify(message, type = 'success') {
                    const id = Date.now();
                    this.toasts.push({ id, message, type });
                    setTimeout(() => {
                        this.toasts = this.toasts.filter(t => t.id !== id);
                    }, 4000);
                }
            });
        });
    </script>
</head>
<body class="bg-[#F8FAFC] text-slate-800 antialiased flex flex-col min-h-screen selection:bg-[#2874F0] selection:text-white" x-data>

    <!-- Canvas Floating Bubbles Background -->
    @include('inventory-frontend.partials.bubbles')

    <!-- Navigation Header -->
    @include('inventory-frontend.partials.navbar')

    <!-- Slide-over Quote Cart Drawer -->
    @include('inventory-frontend.partials.cart-drawer')

    <!-- Main Content Yield -->
    <main class="flex-grow w-full flex flex-col">
        @yield('content')
    </main>

    <!-- Global Footer -->
    @include('inventory-frontend.partials.footer')

    <!-- Toast Notifications Container -->
    <div class="fixed top-24 right-5 z-50 flex flex-col gap-2 pointer-events-none max-w-sm w-full">
        <template x-for="toast in $store.cart.toasts" :key="toast.id">
            <div class="pointer-events-auto flex items-center gap-3 px-4 py-3 bg-white border border-slate-100 rounded-xl shadow-xl transition-all animate-bounce"
                 :class="toast.type === 'error' ? 'border-red-200 text-red-600' : 'border-blue-100 text-slate-800'">
                <div class="w-6 h-6 rounded-full flex items-center justify-center shrink-0"
                     :class="toast.type === 'error' ? 'bg-red-50 text-red-500' : 'bg-blue-50 text-[#2874F0]'">
                    <template x-if="toast.type !== 'error'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </template>
                    <template x-if="toast.type === 'error'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </template>
                </div>
                <p class="text-xs font-bold leading-snug" x-text="toast.message"></p>
            </div>
        </template>
    </div>

    <!-- Dynamic Footer Tracking Scripts (Live Chat, GTM Noscript, Custom Analytics) -->
    {!! \App\Helpers\SeoHelper::renderFooterScripts() !!}

</body>
</html>
