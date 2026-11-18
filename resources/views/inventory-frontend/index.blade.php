@extends('inventory-frontend.layouts.app')

@section('title', 'StockFlow Systems - Smart Enterprise Inventory Sourcing Portal')

@section('content')
<div class="pb-12 text-left" x-data="homeApp()">

    <!-- Hero Section -->
    <section class="w-full relative bg-white pt-12 pb-16 overflow-hidden border-b border-slate-100">
        <div class="w-full max-w-[1920px] mx-auto px-4 md:px-8 lg:px-12 xl:px-16 grid grid-cols-1 lg:grid-cols-12 gap-8 md:gap-12 items-center">
            
            <div class="lg:col-span-7 space-y-6">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-100 text-[11px] font-bold text-[#2874F0] tracking-wider uppercase">
                    <span class="w-2 h-2 rounded-full bg-[#2874F0]"></span>
                    Smart Enterprise Inventory System
                </span>

                <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold tracking-tight leading-tight text-slate-800 font-sans">
                    All-in-One Enterprise <br />
                    <span class="text-[#2874F0]">Inventory Sourcing</span>
                </h1>

                <p class="text-sm md:text-base text-slate-600 leading-relaxed font-medium max-w-2xl">
                    Coordinate warehouse stocks, dispatch quote inquiries, track safety limits, and connect departments in one unified platform built for organizations that want cleaner procurement operations.
                </p>

                <div class="flex flex-wrap gap-4 pt-2">
                    <a href="#demo-form" class="px-8 py-3.5 bg-[#2874F0] hover:bg-[#2874F0]/90 text-white font-bold text-sm rounded-full shadow-lg shadow-blue-500/20 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                        Book Demo
                    </a>
                    <a href="{{ url('/signup') }}" class="px-8 py-3.5 bg-white hover:bg-slate-50 text-slate-700 font-bold text-sm rounded-full border border-slate-200 shadow-sm hover:border-slate-300 transition-all duration-300">
                        Free Trial
                    </a>
                </div>

                <!-- Stats Badges -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-6 max-w-xl">
                    <div class="bg-white px-5 py-3.5 rounded-2xl border border-slate-100 shadow-xs hover:shadow-md hover:-translate-y-1 hover:border-blue-400/50 transition-all duration-300">
                        <span class="text-2xl font-black text-[#2874F0] block">50+</span>
                        <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wider block mt-0.5">Warehouses</span>
                    </div>
                    <div class="bg-white px-5 py-3.5 rounded-2xl border border-slate-100 shadow-xs hover:shadow-md hover:-translate-y-1 hover:border-emerald-400/50 transition-all duration-300">
                        <span class="text-2xl font-black text-emerald-600 block">1,200+</span>
                        <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wider block mt-0.5">Active Suppliers</span>
                    </div>
                    <div class="bg-white px-5 py-3.5 rounded-2xl border border-slate-100 shadow-xs hover:shadow-md hover:-translate-y-1 hover:border-purple-400/50 transition-all duration-300">
                        <span class="text-2xl font-black text-purple-600 block">99.98%</span>
                        <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wider block mt-0.5">Inventory Precision</span>
                    </div>
                </div>
            </div>

            <!-- Hero Graphic Preview -->
            <div class="lg:col-span-5 flex justify-center">
                <div class="relative group w-full max-w-lg">
                    <div class="absolute -inset-2 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-3xl blur-2xl opacity-20 group-hover:opacity-30 transition-opacity"></div>
                    <div class="relative rounded-3xl overflow-hidden border border-slate-200/80 shadow-2xl bg-white">
                        <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&auto=format&fit=crop&q=80" 
                             alt="Inventory Sourcing Preview" 
                             class="w-full h-auto object-cover group-hover:scale-105 transition-transform duration-700" />
                        <div class="p-5 bg-white/95 backdrop-blur-md border-t border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="w-3 h-3 rounded-full bg-emerald-500 animate-ping"></span>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-800">Telemetry Engine Online</h4>
                                    <p class="text-[10px] text-slate-400 font-medium">Synced with 12 Storage Vaults</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold rounded-lg border border-emerald-100">
                                0.2s latency
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Modules Interactive Section -->
    <section class="w-full bg-[#F8FAFC] py-20 border-b border-slate-100">
        <div class="w-full max-w-[1920px] mx-auto px-4 md:px-8 lg:px-12 xl:px-16 space-y-10">
            <div class="space-y-2">
                <span class="text-xs font-bold text-[#2874F0] uppercase tracking-widest block">INVENTORY MODULES</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800">
                    Everything a procurement team needs to run daily work.
                </h2>
                <p class="text-xs text-slate-500 font-medium">Select any module below to inspect real-time specs and operational protocols.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                <!-- Module Switchers -->
                <div class="lg:col-span-5 grid grid-cols-2 sm:grid-cols-3 gap-3.5 w-full">
                    <button @click="activeModule = 'balances'; showTechSpecs = false" 
                            :class="activeModule === 'balances' ? 'border-[#2874F0] bg-blue-50/70 text-[#2874F0] shadow-md' : 'bg-white border-slate-200 text-slate-600 hover:border-blue-300 hover:bg-slate-50'"
                            class="flex flex-col items-center justify-center p-4 rounded-2xl border text-center transition-all duration-300 cursor-pointer hover:-translate-y-1">
                        <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        <span class="text-xs font-bold mt-2">Stock Balances</span>
                    </button>

                    <button @click="activeModule = 'registry'; showTechSpecs = false" 
                            :class="activeModule === 'registry' ? 'border-emerald-500 bg-emerald-50/70 text-emerald-600 shadow-md' : 'bg-white border-slate-200 text-slate-600 hover:border-emerald-300 hover:bg-slate-50'"
                            class="flex flex-col items-center justify-center p-4 rounded-2xl border text-center transition-all duration-300 cursor-pointer hover:-translate-y-1">
                        <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <span class="text-xs font-bold mt-2">Asset Registry</span>
                    </button>

                    <button @click="activeModule = 'margins'; showTechSpecs = false" 
                            :class="activeModule === 'margins' ? 'border-amber-500 bg-amber-50/70 text-amber-600 shadow-md' : 'bg-white border-slate-200 text-slate-600 hover:border-amber-300 hover:bg-slate-50'"
                            class="flex flex-col items-center justify-center p-4 rounded-2xl border text-center transition-all duration-300 cursor-pointer hover:-translate-y-1">
                        <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span class="text-xs font-bold mt-2">Safety Margins</span>
                    </button>

                    <button @click="activeModule = 'cart'; showTechSpecs = false" 
                            :class="activeModule === 'cart' ? 'border-rose-500 bg-rose-50/70 text-rose-600 shadow-md' : 'bg-white border-slate-200 text-slate-600 hover:border-rose-300 hover:bg-slate-50'"
                            class="flex flex-col items-center justify-center p-4 rounded-2xl border text-center transition-all duration-300 cursor-pointer hover:-translate-y-1">
                        <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <span class="text-xs font-bold mt-2">Sourcing Cart</span>
                    </button>

                    <button @click="activeModule = 'suppliers'; showTechSpecs = false" 
                            :class="activeModule === 'suppliers' ? 'border-indigo-500 bg-indigo-50/70 text-indigo-600 shadow-md' : 'bg-white border-slate-200 text-slate-600 hover:border-indigo-300 hover:bg-slate-50'"
                            class="flex flex-col items-center justify-center p-4 rounded-2xl border text-center transition-all duration-300 cursor-pointer hover:-translate-y-1">
                        <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span class="text-xs font-bold mt-2">Suppliers Hub</span>
                    </button>

                    <button @click="activeModule = 'audits'; showTechSpecs = false" 
                            :class="activeModule === 'audits' ? 'border-teal-500 bg-teal-50/70 text-teal-600 shadow-md' : 'bg-white border-slate-200 text-slate-600 hover:border-teal-300 hover:bg-slate-50'"
                            class="flex flex-col items-center justify-center p-4 rounded-2xl border text-center transition-all duration-300 cursor-pointer hover:-translate-y-1">
                        <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span class="text-xs font-bold mt-2">Compliance Log</span>
                    </button>
                </div>

                <!-- Active Module Display Panel -->
                <div class="lg:col-span-7 bg-white p-8 rounded-3xl border border-slate-100 shadow-xl flex flex-col justify-between hover:shadow-2xl transition-all duration-300">
                    <div class="space-y-6">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <div>
                                <h3 class="font-extrabold text-slate-800 text-lg" x-text="modules[activeModule].title"></h3>
                                <p class="text-xs text-slate-400 font-medium mt-0.5">Automated telemetry & sourcing protocol</p>
                            </div>
                            <div class="text-right">
                                <span class="text-xl font-black text-[#2874F0]" x-text="modules[activeModule].metric"></span>
                                <span class="block text-[10px] text-slate-400 uppercase font-bold" x-text="modules[activeModule].metricLabel"></span>
                            </div>
                        </div>

                        <p class="text-xs text-slate-600 leading-relaxed font-medium" x-text="modules[activeModule].desc"></p>

                        <!-- Key Details Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                            <template x-for="(item, idx) in modules[activeModule].details" :key="idx">
                                <div class="p-3.5 bg-slate-50 border border-slate-100 rounded-xl hover:border-slate-200 transition-all">
                                    <div class="flex items-center gap-2 mb-1">
                                        <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <h4 class="text-xs font-bold text-slate-800" x-text="item.name"></h4>
                                    </div>
                                    <p class="text-[11px] text-slate-500 pl-6" x-text="item.desc"></p>
                                </div>
                            </template>
                        </div>

                        <!-- Technical Specs Accordion -->
                        <div class="pt-2">
                            <button @click="showTechSpecs = !showTechSpecs" class="flex items-center justify-between w-full p-3 bg-blue-50/50 hover:bg-blue-50 border border-blue-100/60 rounded-xl text-xs font-bold text-[#2874F0] transition-colors">
                                <span>Inspect Technical Specifications</span>
                                <svg class="w-4 h-4 transform transition-transform" :class="showTechSpecs ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="showTechSpecs" x-cloak class="mt-3 p-4 bg-slate-50 rounded-xl border border-slate-100 text-xs text-slate-600 space-y-2">
                                <template x-for="(spec, sidx) in modules[activeModule].specs" :key="sidx">
                                    <div class="flex items-start gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#2874F0] mt-1.5 shrink-0"></span>
                                        <span x-text="spec"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-6 border-t border-slate-100 mt-6">
                        <a href="{{ url('/modules') }}" class="text-xs font-bold text-[#2874F0] hover:underline flex items-center gap-1">
                            Explore full pipeline layout
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        <a href="#demo-form" class="px-5 py-2 bg-[#2874F0] text-white text-xs font-bold rounded-xl shadow-md">
                            Schedule Demo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Catalog Quick Peek Section -->
    <section class="w-full bg-white py-20 border-b border-slate-100">
        <div class="w-full max-w-[1920px] mx-auto px-4 md:px-8 lg:px-12 xl:px-16 space-y-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <span class="text-xs font-bold text-[#2874F0] uppercase tracking-widest block">SOURCING REPOSITORY</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800 mt-1">Live Stock Catalog Overview</h2>
                    <p class="text-xs text-slate-500 font-medium mt-1">Real-time status indicators with direct quote compile capabilities.</p>
                </div>
                <a href="{{ url('/products') }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#2874F0] hover:text-blue-700 bg-blue-50 px-4 py-2.5 rounded-xl border border-blue-100 transition-colors">
                    <span>View All 31 Items in Catalog</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <!-- Featured Products Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Product 1 -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between overflow-hidden group">
                    <div class="relative h-48 bg-slate-50 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=600&auto=format&fit=crop&q=80" alt="iPhone 15 Pro Max" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 left-3 px-2.5 py-1 bg-emerald-500/90 text-white font-bold text-[9px] uppercase tracking-wider rounded-md backdrop-blur-xs">In Stock</span>
                    </div>
                    <div class="p-5 flex flex-col justify-between flex-1">
                        <div>
                            <span class="text-[10px] text-slate-400 font-mono">IPH15PM-256-BLU</span>
                            <h3 class="text-sm font-bold text-slate-800 mt-0.5 truncate">iPhone 15 Pro Max</h3>
                            <p class="text-xs text-slate-500 mt-1 line-clamp-2">Titanium design, A17 Pro chip, custom Action button, and Telephoto camera.</p>
                        </div>
                        <div class="flex items-center justify-between pt-4 border-t border-slate-100 mt-4">
                            <span class="text-base font-black text-[#2874F0]">$1,199</span>
                            <button @click="$store.cart.addItem({ id: 1, name: 'iPhone 15 Pro Max', sku: 'IPH15PM-256-BLU', price: 1199, stock: 15, image: 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=600&auto=format&fit=crop&q=80' })" class="px-3 py-1.5 bg-[#2874F0] hover:bg-[#2874F0]/90 text-white rounded-lg text-xs font-bold flex items-center gap-1.5 shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                Quote
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product 2 -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between overflow-hidden group">
                    <div class="relative h-48 bg-slate-50 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=600&auto=format&fit=crop&q=80" alt="MacBook Air M3" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 left-3 px-2.5 py-1 bg-emerald-500/90 text-white font-bold text-[9px] uppercase tracking-wider rounded-md backdrop-blur-xs">In Stock</span>
                    </div>
                    <div class="p-5 flex flex-col justify-between flex-1">
                        <div>
                            <span class="text-[10px] text-slate-400 font-mono">MACAIRM3-512-SLV</span>
                            <h3 class="text-sm font-bold text-slate-800 mt-0.5 truncate">MacBook Air M3</h3>
                            <p class="text-xs text-slate-500 mt-1 line-clamp-2">Supercharged by next-generation M3 chip with up to 18 hours battery life.</p>
                        </div>
                        <div class="flex items-center justify-between pt-4 border-t border-slate-100 mt-4">
                            <span class="text-base font-black text-[#2874F0]">$1,299</span>
                            <button @click="$store.cart.addItem({ id: 2, name: 'MacBook Air M3', sku: 'MACAIRM3-512-SLV', price: 1299, stock: 8, image: 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=600&auto=format&fit=crop&q=80' })" class="px-3 py-1.5 bg-[#2874F0] hover:bg-[#2874F0]/90 text-white rounded-lg text-xs font-bold flex items-center gap-1.5 shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                Quote
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product 3 -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between overflow-hidden group">
                    <div class="relative h-48 bg-slate-50 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&auto=format&fit=crop&q=80" alt="Sony WH-1000XM5" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 left-3 px-2.5 py-1 bg-amber-500/90 text-white font-bold text-[9px] uppercase tracking-wider rounded-md backdrop-blur-xs">Low Stock</span>
                    </div>
                    <div class="p-5 flex flex-col justify-between flex-1">
                        <div>
                            <span class="text-[10px] text-slate-400 font-mono">SONY-XM5-BLK</span>
                            <h3 class="text-sm font-bold text-slate-800 mt-0.5 truncate">Sony WH-1000XM5</h3>
                            <p class="text-xs text-slate-500 mt-1 line-clamp-2">Industry-leading noise cancellation and crystal-clear hands-free calling.</p>
                        </div>
                        <div class="flex items-center justify-between pt-4 border-t border-slate-100 mt-4">
                            <span class="text-base font-black text-[#2874F0]">$349</span>
                            <button @click="$store.cart.addItem({ id: 3, name: 'Sony WH-1000XM5', sku: 'SONY-XM5-BLK', price: 349, stock: 3, image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&auto=format&fit=crop&q=80' })" class="px-3 py-1.5 bg-[#2874F0] hover:bg-[#2874F0]/90 text-white rounded-lg text-xs font-bold flex items-center gap-1.5 shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                Quote
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product 4 -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between overflow-hidden group">
                    <div class="relative h-48 bg-slate-50 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1580481072645-022f9a6dbf27?w=600&auto=format&fit=crop&q=80" alt="Ergonomic Office Chair" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 left-3 px-2.5 py-1 bg-rose-500/90 text-white font-bold text-[9px] uppercase tracking-wider rounded-md backdrop-blur-xs">Out of Stock</span>
                    </div>
                    <div class="p-5 flex flex-col justify-between flex-1">
                        <div>
                            <span class="text-[10px] text-slate-400 font-mono">STE-EOC-GRY</span>
                            <h3 class="text-sm font-bold text-slate-800 mt-0.5 truncate">Ergonomic Office Chair</h3>
                            <p class="text-xs text-slate-500 mt-1 line-clamp-2">Fully adjustable lumbar support, 3D armrests, and high-density mesh.</p>
                        </div>
                        <div class="flex items-center justify-between pt-4 border-t border-slate-100 mt-4">
                            <span class="text-base font-black text-[#2874F0]">$299</span>
                            <button @click="$store.cart.notify('Item is currently out of stock. Added backorder request!', 'error')" class="px-3 py-1.5 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg text-xs font-bold flex items-center gap-1.5 shadow-sm">
                                Backorder
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Book Demo Form Section -->
    <section id="demo-form" class="w-full bg-[#F8FAFC] py-20">
        <div class="w-full max-w-[1920px] mx-auto px-4 md:px-8 lg:px-12 xl:px-16 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <div class="lg:col-span-5 space-y-5">
                <span class="text-xs font-bold text-[#2874F0] uppercase tracking-widest block">OPERATIONAL ONBOARDING</span>
                <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight leading-tight">
                    Schedule an Institutional Sourcing Walkthrough
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-medium">
                    Connect directly with our logistics architects. We will demonstrate how StockFlow models your warehouse partitions, maps supplier agreements, and eliminates replenishment friction.
                </p>
                <div class="space-y-3 pt-2">
                    <div class="flex items-center gap-3 text-xs font-bold text-slate-700">
                        <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-black text-xs">✓</span>
                        <span>Tailored 30-minute system architecture demo</span>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-bold text-slate-700">
                        <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-black text-xs">✓</span>
                        <span>Full sandbox test environment credential access</span>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-bold text-slate-700">
                        <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-black text-xs">✓</span>
                        <span>Direct SLA agreement consultation for enterprise fleets</span>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-7">
                <div class="bg-white p-8 sm:p-10 rounded-3xl border border-slate-100 shadow-2xl space-y-6">
                    <h3 class="text-xl font-extrabold text-slate-800">Request Live Demo</h3>
                    
                    <form @submit.prevent="submitDemo()" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Full Name</label>
                                <input type="text" x-model="demoForm.name" required placeholder="Alex Mercer" class="w-full h-11 px-4 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-[#2874F0] font-medium text-slate-800" />
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Direct Phone</label>
                                <input type="tel" x-model="demoForm.phone" placeholder="+1 (555) 019-2834" class="w-full h-11 px-4 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-[#2874F0] font-medium text-slate-800" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Official Corporate Email</label>
                                <input type="email" x-model="demoForm.email" required placeholder="alex@organization.com" class="w-full h-11 px-4 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-[#2874F0] font-medium text-slate-800" />
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Company / Institution Name</label>
                                <input type="text" x-model="demoForm.org" required placeholder="Apex Logistics Corp" class="w-full h-11 px-4 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-[#2874F0] font-medium text-slate-800" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Primary Procurement Priority</label>
                            <select x-model="demoForm.priority" class="w-full h-11 px-4 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-[#2874F0] font-bold text-slate-700">
                                <option value="Complete Sourcing System">Complete Sourcing System</option>
                                <option value="Depot & Warehouse Controls">Depot & Warehouse Controls</option>
                                <option value="Safety Stock & Low Stock Alarms">Safety Stock & Low Stock Alarms</option>
                                <option value="Multi-Vendor Quote Dispatch Hub">Multi-Vendor Quote Dispatch Hub</option>
                                <option value="Audit & Compliance Ledger">Audit & Compliance Ledger</option>
                            </select>
                        </div>

                        <button type="submit" :disabled="submittingDemo" class="w-full py-4 bg-[#2874F0] hover:bg-[#2874F0]/90 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-blue-500/20 hover:shadow-xl transition-all cursor-pointer">
                            <span x-show="!submittingDemo">Confirm & Schedule Demonstration</span>
                            <span x-show="submittingDemo" x-cloak>Registering Session...</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </section>

</div>

<script>
function homeApp() {
    return {
        activeModule: 'balances',
        showTechSpecs: false,
        demoForm: { name: '', phone: '', email: '', org: '', priority: 'Complete Sourcing System' },
        submittingDemo: false,
        modules: {
            balances: {
                title: 'Stock Balance Tickers',
                desc: 'Every product detail, stock balance, and category distribution is refreshed automatically. Sourcing assistants see safety flags without manual re-querying, maximizing procurement allocation velocity across active catalog levels.',
                metric: '99.98%',
                metricLabel: 'Inventory Precision',
                details: [
                    { name: 'Live Stock Counts', desc: 'Real-time updates on active catalog levels' },
                    { name: 'SKU Identifier Lookup', desc: 'Instantly fetch specific serial codes and suppliers' },
                    { name: 'Category Sorting', desc: 'Group items into Electronics, Apparel, or Furniture' },
                    { name: 'Batch Expirations', desc: 'Track laboratory reagent and supply dates' }
                ],
                specs: [
                    'Low latency database updates in under 200ms',
                    'Dynamic allocation algorithm to avoid double-booking',
                    'Automated stock level caching with local storage backup',
                    'Supports barcode reader integration directly'
                ]
            },
            registry: {
                title: 'Enterprise Asset Registry',
                desc: 'Fully indexed database of every corporate asset, computer router, dining seat, laboratory appliance, and warehouse rack. Features real-time tracking, barcode logging, and dynamic status updates.',
                metric: '12,450 Units',
                metricLabel: 'Indexed Assets',
                details: [
                    { name: 'Electronics Tracking', desc: 'Manage corporate laptops, screens, and routers' },
                    { name: 'Laboratory Apparatus', desc: 'Coordinate bio-reagents and delicate equipment' },
                    { name: 'Classroom & Desks', desc: 'Log department desks, chairs, and lecterns' },
                    { name: 'Library Assets', desc: 'Track barcodes and volume coordinate logs' }
                ],
                specs: [
                    'Categorized classification under corporate asset codes',
                    'Automatic amortization tracking based on usage time',
                    'Dynamic lifecycle tags from registration to disposal',
                    'Multi-tier access permissions for editing records'
                ]
            },
            margins: {
                title: 'Safety Threshold Margins',
                desc: 'Ensure enterprise departments remain fully stocked. Dynamic warnings trigger automatically when quantities fall to safety stock thresholds, signaling restock actions immediately.',
                metric: '4 Units',
                metricLabel: 'Warning Threshold',
                details: [
                    { name: 'Low-Stock Alarms', desc: 'Automatic warnings when counts drop below threshold' },
                    { name: 'Threshold Limits', desc: 'Configure custom safety stock limits per item' },
                    { name: 'Reorder Triggers', desc: 'Dynamic alert indicators sent to procurement hubs' },
                    { name: 'Emergency Allocations', desc: 'Locate and pull emergency supplies instantly' }
                ],
                specs: [
                    'Custom safety thresholds configurable per SKU',
                    'Visual indicators representing warning priorities',
                    'Automatic buffer calculations derived from weekly demand',
                    'Instant email and alert triggers sent to sourcing operators'
                ]
            },
            cart: {
                title: 'Sourcing Basket Compilation',
                desc: 'Stage bulk quantities inside a centralized user basket. Perform safety stock safety tests locally, compile remarks, and dispatch quote inquiries to logistics hubs in one unified click.',
                metric: '< 1.5s',
                metricLabel: 'Dispatch Velocity',
                details: [
                    { name: 'Consolidated Requests', desc: 'Stage multiple items inside one basket' },
                    { name: 'Operator Remarks', desc: 'Append target delivery dates and custom specs' },
                    { name: 'Sourcing Dispatches', desc: 'Send consolidated quotes requests in one click' },
                    { name: 'Fulfillment Keys', desc: 'Generates unique tracking numbers for security' }
                ],
                specs: [
                    'Draft carts saved dynamically to operator sessions',
                    'Estimated spend compiled and visualized in real time',
                    'Custom spec parser supporting multi-line operator remarks',
                    'Dispatched requests recorded instantly to transaction logs'
                ]
            },
            suppliers: {
                title: 'Supplier Relations Hub',
                desc: 'Connect with verified manufacturing networks, contract partners, and supply chains. Monitor supplier response latencies, pricing benchmarks, and order fulfillment accuracy from a unified portal.',
                metric: '1,200+',
                metricLabel: 'Active Partners',
                details: [
                    { name: 'Certified Merchants', desc: 'Browse pre-vetted corporate supply vendors' },
                    { name: 'Quote Comparisons', desc: 'Evaluate price points and delivery latency' },
                    { name: 'Supplier Performance', desc: 'Track dispatch accuracy and fulfillment speed' }
                ],
                specs: [
                    'Direct integration of pre-vetted merchant catalogs',
                    'Historical metrics tracking merchant response latencies',
                    'Fulfillment speed scoring displayed to logged-in buyers',
                    'Alternate supplier suggestions for high-scarcity supplies'
                ]
            },
            audits: {
                title: 'Fulfillment & Compliance Audits',
                desc: 'Every single dispatched quote request, status shift, and supplier action is recorded permanently. Safeguard corporate accountability with detailed operational reports.',
                metric: '100%',
                metricLabel: 'Audit Trailing',
                details: [
                    { name: 'Timestamped Verification', desc: 'Rigorous logs of every stock count adjustment' },
                    { name: 'Fulfillment Statuses', desc: 'Follow requests from pending to delivered' },
                    { name: 'Operator Activity Logs', desc: 'Audit trails showing which buyer submitted requests' }
                ],
                specs: [
                    '100% immutable operational transaction records',
                    'Unique identification tracking keys mapped to requests',
                    'Exportable CSV templates for compliance reviews',
                    'Activity timelines illustrating buyer and reviewer steps'
                ]
            }
        },
        submitDemo() {
            if (!this.demoForm.name || !this.demoForm.email || !this.demoForm.org) {
                Alpine.store('cart').notify('Please fill out your name, email, and organization.', 'error');
                return;
            }
            this.submittingDemo = true;
            setTimeout(() => {
                Alpine.store('cart').notify('Live Demo Scheduled for ' + this.demoForm.org + '! Sourcing officers will reach out.');
                this.demoForm = { name: '', phone: '', email: '', org: '', priority: 'Complete Sourcing System' };
                this.submittingDemo = false;
            }, 800);
        }
    };
}
</script>
@endsection
