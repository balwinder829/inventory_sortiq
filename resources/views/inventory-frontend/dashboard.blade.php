@extends('inventory-frontend.layouts.app')

@section('title', 'Operator Dashboard - StockFlow Systems')

@section('content')
<div class="w-full max-w-[1920px] mx-auto px-4 md:px-8 lg:px-12 xl:px-16 py-10 space-y-8 text-left pb-20" x-data="dashboardApp()">

    <!-- Top Profile & Spend Bar -->

    <!-- Top Profile & Spend Bar -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-sm flex flex-col sm:flex-row items-center gap-6 justify-between">
        <div class="flex items-center gap-4 text-left">
            <div class="w-16 h-16 rounded-2xl bg-[#2874F0]/10 text-[#2874F0] font-black text-xl flex items-center justify-center border border-[#2874F0]/20">
                <span x-text="$store.cart.user ? $store.cart.user.name.charAt(0) : 'S'"></span>
            </div>
            <div>
                <h2 class="text-xl font-extrabold text-slate-800" x-text="$store.cart.user ? $store.cart.user.name : 'Sarah Connor'"></h2>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-0.5" x-text="$store.cart.user ? $store.cart.user.role : 'Registered Sourcing Buyer'"></p>
                <p class="text-[11px] text-slate-400" x-text="$store.cart.user ? $store.cart.user.email : 'buyer@stockflow.com'"></p>
            </div>
        </div>

        <div class="bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 text-center sm:text-right shrink-0">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Approved Sourcing Spend</span>
            <span class="text-2xl font-black text-emerald-600 mt-1 block" x-text="'$' + approvedTotal.toLocaleString()"></span>
        </div>
    </div>

    <!-- Stat Tiles -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="p-3.5 bg-blue-50 text-[#2874F0] rounded-2xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <div>
                <h3 class="text-2xl font-black text-slate-800" x-text="$store.cart.quotes.length"></h3>
                <p class="text-xs text-slate-400 font-bold uppercase mt-0.5">Total Submissions</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="p-3.5 bg-amber-50 text-amber-600 rounded-2xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h3 class="text-2xl font-black text-slate-800" x-text="pendingCount"></h3>
                <p class="text-xs text-slate-400 font-bold uppercase mt-0.5">Pending Review</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-xs flex items-center gap-4">
            <div class="p-3.5 bg-emerald-50 text-emerald-600 rounded-2xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h3 class="text-2xl font-black text-slate-800" x-text="approvedCount"></h3>
                <p class="text-xs text-slate-400 font-bold uppercase mt-0.5">Approved Allocations</p>
            </div>
        </div>
    </div>

    <!-- Main Grid: Quote Requests Ledger & Saved Items -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Dispatched Quotes Table -->
        <div class="lg:col-span-8 bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-sm space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-[#2874F0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <h3 class="font-bold text-slate-800 text-sm">Quote Dispatch Log & Sourcing History</h3>
                </div>
                <button @click="$store.cart.drawerOpen = true" class="text-xs font-bold text-[#2874F0] hover:underline">
                    + New Sourcing Basket
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-[10px] text-slate-400 uppercase font-bold tracking-wider">
                            <th class="pb-3">Reference ID</th>
                            <th class="pb-3">Dispatch Date</th>
                            <th class="pb-3">Items Included</th>
                            <th class="pb-3">Est. Value</th>
                            <th class="pb-3 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="quote in $store.cart.quotes" :key="quote.id">
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 font-bold font-mono text-[#2874F0]" x-text="quote.id"></td>
                                <td class="py-3.5 text-slate-500 font-medium" x-text="quote.date"></td>
                                <td class="py-3.5 text-slate-700">
                                    <template x-for="(item, idx) in quote.items" :key="idx">
                                        <span class="inline-block mr-1 text-[11px] bg-slate-100 px-2 py-0.5 rounded font-medium" x-text="item.name + ' (' + item.qty + ')'"></span>
                                    </template>
                                </td>
                                <td class="py-3.5 font-bold text-slate-800" x-text="'$' + quote.total.toLocaleString()"></td>
                                <td class="py-3.5 text-right">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider"
                                          :class="quote.status === 'Approved' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'"
                                          x-text="quote.status">
                                    </span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right: Saved Wishlist Items -->
        <div class="lg:col-span-4 bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-sm space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-500 fill-red-500" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    <h3 class="font-bold text-slate-800 text-sm">Saved Items (<span x-text="$store.cart.wishlistCount"></span>)</h3>
                </div>
                <a href="{{ url('/products') }}" class="text-[10px] font-bold text-[#2874F0] hover:underline">Explore More</a>
            </div>

            <!-- Empty Wishlist -->
            <template x-if="wishlisted.length === 0">
                <div class="py-10 text-center text-slate-400 space-y-2">
                    <p class="text-xs font-medium">No items currently saved in your wishlist.</p>
                    <a href="{{ url('/products') }}" class="inline-block text-xs font-bold text-[#2874F0] hover:underline">Browse Catalog</a>
                </div>
            </template>

            <!-- Wishlist Items List -->
            <template x-if="wishlisted.length > 0">
                <div class="space-y-3">
                    <template x-for="item in wishlisted" :key="item.id">
                        <div class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-100 rounded-xl hover:border-slate-200 transition-all">
                            <img :src="item.image" :alt="item.name" class="w-12 h-12 object-cover rounded-lg bg-white shrink-0">
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs font-bold text-slate-800 truncate" x-text="item.name"></h4>
                                <span class="text-xs font-black text-[#2874F0]" x-text="'$' + item.price.toLocaleString()"></span>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <button @click="$store.cart.addItem(item, 1)" class="p-1.5 bg-[#2874F0] text-white rounded-lg hover:bg-blue-600 transition-colors" title="Add to Basket">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                </button>
                                <button @click="$store.cart.toggleWishlist(item.id)" class="p-1.5 text-slate-400 hover:text-red-500 transition-colors" title="Remove">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </div>

    </div>

</div>

<script>
function dashboardApp() {
    return {
        products: [
            { id: 1, name: 'iPhone 15 Pro Max', sku: 'IPH15PM-256-BLU', price: 1199, stock: 15, image: 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=600&auto=format&fit=crop&q=80' },
            { id: 2, name: 'MacBook Air M3', sku: 'MACAIRM3-512-SLV', price: 1299, stock: 8, image: 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=600&auto=format&fit=crop&q=80' },
            { id: 3, name: 'Sony WH-1000XM5', sku: 'SONY-XM5-BLK', price: 349, stock: 3, image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&auto=format&fit=crop&q=80' },
            { id: 4, name: "Men's Slim Fit Jeans", sku: 'GAP-SFJ-32', price: 49, stock: 50, image: 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=600&auto=format&fit=crop&q=80' },
            { id: 5, name: 'Non-Stick Cookware Set', sku: 'TEF-NSC-10P', price: 120, stock: 14, image: 'https://images.unsplash.com/photo-1584269600464-37b1b58a9fe7?w=600&auto=format&fit=crop&q=80' },
            { id: 6, name: 'Ergonomic Office Chair', sku: 'STE-EOC-GRY', price: 299, stock: 0, image: 'https://images.unsplash.com/photo-1580481072645-022f9a6dbf27?w=600&auto=format&fit=crop&q=80' }
        ],
        get wishlisted() {
            return this.products.filter(p => Alpine.store('cart').wishlist.includes(p.id));
        },
        get approvedTotal() {
            return Alpine.store('cart').quotes
                .filter(q => q.status === 'Approved')
                .reduce((acc, curr) => acc + curr.total, 0);
        },
        get pendingCount() {
            return Alpine.store('cart').quotes.filter(q => q.status === 'Pending Review').length;
        },
        get approvedCount() {
            return Alpine.store('cart').quotes.filter(q => q.status === 'Approved').length;
        }
    };
}
</script>
@endsection
