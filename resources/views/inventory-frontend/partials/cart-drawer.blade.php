<!-- Quote Cart Slide-Over Drawer -->
<div x-show="$store.cart.drawerOpen" 
     x-cloak 
     class="fixed inset-0 z-50 overflow-hidden text-left" 
     aria-labelledby="slide-over-title" 
     role="dialog" 
     aria-modal="true">
    
    <!-- Backdrop -->
    <div x-show="$store.cart.drawerOpen"
         x-transition:enter="ease-in-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in-out duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="$store.cart.drawerOpen = false"
         class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity"></div>

    <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
        <div x-show="$store.cart.drawerOpen"
             x-transition:enter="transform transition ease-in-out duration-300"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transform transition ease-in-out duration-300"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             class="w-screen max-w-md bg-white shadow-2xl border-l border-slate-100 flex flex-col h-full overflow-hidden">
            
            <!-- Drawer Header -->
            <div class="px-6 py-5 bg-[#2874F0] text-white flex items-center justify-between shadow-md">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <div>
                        <h3 class="font-bold text-sm tracking-wide">Quote Request Basket</h3>
                        <p class="text-[10px] text-white/80 font-medium">Consolidated Sourcing Compilation</p>
                    </div>
                </div>
                <button @click="$store.cart.drawerOpen = false" class="p-1.5 rounded-lg hover:bg-white/10 text-white transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Drawer Body -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6">
                <!-- Empty State -->
                <template x-if="$store.cart.items.length === 0">
                    <div class="py-20 text-center text-slate-400 space-y-3">
                        <div class="w-16 h-16 mx-auto rounded-full bg-slate-50 flex items-center justify-center text-slate-300 border border-slate-100">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-slate-700">Your basket is empty</p>
                        <p class="text-xs text-slate-400 max-w-xs mx-auto">Explore the Live Catalog and add items to compile institutional quote dispatches.</p>
                        <a href="{{ url('/products') }}" @click="$store.cart.drawerOpen = false" class="inline-block mt-2 px-5 py-2.5 bg-[#2874F0] text-white text-xs font-bold rounded-xl shadow-md">
                            Browse Live Catalog
                        </a>
                    </div>
                </template>

                <!-- Cart Items List -->
                <template x-if="$store.cart.items.length > 0">
                    <div class="space-y-4">
                        <template x-for="(item, index) in $store.cart.items" :key="item.id">
                            <div class="flex items-center gap-3.5 p-3.5 bg-slate-50 border border-slate-100/80 rounded-2xl hover:border-slate-200 transition-all">
                                <img :src="item.image" :alt="item.name" class="w-14 h-14 object-cover rounded-xl border border-slate-200/60 bg-white shrink-0">
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-xs font-bold text-slate-800 truncate" x-text="item.name"></h4>
                                    <p class="text-[10px] text-slate-400 font-mono tracking-wide" x-text="item.sku"></p>
                                    <p class="text-xs font-extrabold text-[#2874F0] mt-0.5" x-text="'$' + (item.price * item.quantity).toLocaleString()"></p>
                                </div>
                                <div class="flex flex-col items-end gap-2 shrink-0">
                                    <button @click="$store.cart.removeItem(item.id)" class="text-slate-400 hover:text-red-500 transition-colors p-1" title="Remove item">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                    <div class="flex items-center border border-slate-200 rounded-lg bg-white overflow-hidden shadow-xs">
                                        <button @click="$store.cart.updateQty(item.id, -1)" class="px-2 py-0.5 text-xs text-slate-500 hover:bg-slate-100 font-bold">-</button>
                                        <span class="px-2.5 py-0.5 text-xs font-bold text-slate-800" x-text="item.quantity"></span>
                                        <button @click="$store.cart.updateQty(item.id, 1)" class="px-2 py-0.5 text-xs text-slate-500 hover:bg-slate-100 font-bold">+</button>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- Sourcing Notes -->
                        <div class="pt-2">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                                Consignment Specifications / Delivery Notes
                            </label>
                            <textarea x-model="$store.cart.notes" rows="3" placeholder="Append target fulfillment dates, destination department, or custom requirements..." class="w-full text-xs p-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:border-[#2874F0] text-slate-700 resize-none font-medium"></textarea>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Drawer Footer Summary -->
            <template x-if="$store.cart.items.length > 0">
                <div class="p-6 border-t border-slate-100 bg-slate-50/70 space-y-4">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-semibold">Total Sourced Items</span>
                        <span class="font-bold text-slate-800" x-text="$store.cart.count + ' units'"></span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-bold text-slate-700">Estimated Sourcing Spend</span>
                        <span class="text-lg font-black text-[#2874F0]" x-text="'$' + $store.cart.total.toLocaleString()"></span>
                    </div>
                    <button @click="$store.cart.submitQuote()" class="w-full py-3.5 bg-[#2874F0] hover:bg-[#2874F0]/90 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-blue-500/20 hover:shadow-xl transition-all cursor-pointer">
                        Dispatch Sourcing Quote Request
                    </button>
                    <p class="text-[10px] text-center text-slate-400">
                        Dispatched allocations generate timestamped audit keys verified across suppliers.
                    </p>
                </div>
            </template>

        </div>
    </div>
</div>
