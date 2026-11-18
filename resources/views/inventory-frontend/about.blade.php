@extends('inventory-frontend.layouts.app')

@section('title', 'About Us - StockFlow Systems Corporate Logistics')

@section('content')
<div class="text-left w-full pb-16">
    <!-- Hero Row -->
    <section class="w-full bg-white py-12 md:py-20 border-b border-slate-100">
        <div class="w-full max-w-[1920px] mx-auto px-4 md:px-8 lg:px-12 xl:px-16 grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
            
            <!-- Left Brand Card -->
            <div class="lg:col-span-7 bg-slate-50 p-8 sm:p-12 rounded-3xl border border-slate-100 shadow-xl flex flex-col justify-between hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
                <div class="absolute -top-10 -right-10 w-44 h-44 bg-blue-500/5 rounded-full blur-2xl group-hover:scale-125 transition-transform"></div>
                <div class="space-y-6">
                    <span class="text-xs font-bold text-[#2874F0] uppercase tracking-widest block">ABOUT STOCKFLOW</span>
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-slate-800 leading-tight">
                        Building reliable digital systems for ambitious inventory teams.
                        <div class="h-1.5 w-36 bg-[#2874F0] rounded-full mt-3.5"></div>
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed font-medium">
                        StockFlow provides practical, high-performance software that helps institutions coordinate warehouse pipelines, streamline dispatches, and optimize procurement routines with maximum tracking confidence.
                    </p>
                </div>
                <div class="flex flex-wrap gap-4 pt-8">
                    <a href="{{ url('/contact') }}" class="px-7 py-3.5 bg-[#2874F0] hover:bg-[#2874F0]/90 text-white text-xs font-bold rounded-xl shadow-md transition-all duration-300">
                        Request Sourcing Demo
                    </a>
                    <a href="{{ url('/products') }}" class="px-7 py-3.5 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl border border-slate-200 shadow-sm transition-all duration-300">
                        View Our Catalog
                    </a>
                </div>
            </div>

            <!-- Right Dark Contrast Card -->
            <div class="lg:col-span-5 bg-gradient-to-br from-[#0F172A] to-[#1E293B] p-8 sm:p-12 rounded-3xl text-white flex flex-col justify-between hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
                <div class="absolute top-10 right-10 w-28 h-28 bg-teal-500/10 rounded-full blur-xl animate-pulse"></div>
                <div class="space-y-5">
                    <span class="text-[10px] font-bold text-teal-400 uppercase tracking-widest block">COMPANY OVERVIEW</span>
                    <h2 class="text-2xl sm:text-3xl font-bold leading-tight">
                        Technology with ethics, transparency, and measurable value.
                        <div class="h-1.5 w-24 bg-[#FF6B00] rounded-full mt-3.5"></div>
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed font-medium">
                        We work closely with every client to understand warehouse workflows, simplify database records, and deliver solutions that are secure, reliable, and scaling.
                    </p>
                </div>
                <div class="grid grid-cols-3 gap-4 pt-8 border-t border-slate-800/80">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">ERP</span>
                        <span class="text-xs font-semibold text-slate-200 mt-1 block">Premium inventory modules</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">IT</span>
                        <span class="text-xs font-semibold text-slate-200 mt-1 block">Custom software integration</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Long-term</span>
                        <span class="text-xs font-semibold text-slate-200 mt-1 block">Partnership-first delivery</span>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Vision & Mission Showcases -->
    <section class="w-full bg-slate-50 py-20 border-b border-slate-100">
        <div class="w-full max-w-[1920px] mx-auto px-4 md:px-8 lg:px-12 xl:px-16 space-y-8">
            
            <!-- Showcase 1 -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 grid grid-cols-1 md:grid-cols-12 gap-8 items-center group">
                <div class="md:col-span-5 rounded-2xl overflow-hidden h-56 bg-slate-100 relative">
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=600&auto=format&fit=crop&q=80" alt="Who We Are" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                    <div class="absolute inset-0 bg-slate-900/15 flex items-center justify-center">
                        <span class="text-white text-5xl font-light">?</span>
                    </div>
                </div>
                <div class="md:col-span-7 space-y-3">
                    <span class="text-[10px] font-bold text-[#2874F0] uppercase tracking-widest block">WHO WE ARE</span>
                    <h3 class="text-xl font-bold text-slate-800">A software company focused on practical transformation, not vanity tech.</h3>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed font-medium">
                        At StockFlow, we build systems that solve real operational friction. We design tools that increase item visibility, reduce errors, and support scalable growth, so procurement teams can trust their digital environment.
                    </p>
                </div>
            </div>

            <!-- Showcase 2 -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 grid grid-cols-1 md:grid-cols-12 gap-8 items-center group">
                <div class="md:col-span-5 rounded-2xl overflow-hidden h-56 bg-slate-100">
                    <img src="https://images.unsplash.com/photo-1507537297725-24a1c029d3ca?w=600&auto=format&fit=crop&q=80" alt="Our Vision" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                </div>
                <div class="md:col-span-7 space-y-3">
                    <span class="text-[10px] font-bold text-[#2874F0] uppercase tracking-widest block">OUR VISION</span>
                    <h3 class="text-xl font-bold text-slate-800">Making supply chains intelligent, responsive, and effortlessly auditable.</h3>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed font-medium">
                        We envision an institutional ecosystem where warehouses run with precision, stockouts are anticipated days in advance, and multi-tier vendors are matched to quote requests within seconds.
                    </p>
                </div>
            </div>

        </div>
    </section>

    <!-- Core Pillars Grid -->
    <section class="w-full bg-white py-20">
        <div class="w-full max-w-[1920px] mx-auto px-4 md:px-8 lg:px-12 xl:px-16 space-y-12">
            <div class="text-center space-y-3 max-w-2xl mx-auto">
                <span class="text-xs font-bold text-[#2874F0] uppercase tracking-widest block">OPERATIONAL PILLARS</span>
                <h2 class="text-3xl font-extrabold text-slate-800">Engineered for High-Consequence Operations</h2>
                <p class="text-xs sm:text-sm text-slate-500 font-medium">Four core commitments that define our platform engineering and client relationship philosophy.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-slate-50 p-7 rounded-2xl border border-slate-100 hover:border-blue-200 hover:shadow-xl transition-all duration-300 space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 text-[#2874F0] flex items-center justify-center font-black text-lg">01</div>
                    <h3 class="font-bold text-base text-slate-800">Precision First</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Dynamic allocation locks guarantee zero double-booked items across multi-warehouse clusters.</p>
                </div>

                <div class="bg-slate-50 p-7 rounded-2xl border border-slate-100 hover:border-emerald-200 hover:shadow-xl transition-all duration-300 space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-black text-lg">02</div>
                    <h3 class="font-bold text-base text-slate-800">Immutable Audit</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Every SKU increment, decrement, and quote submission generates verifiable cryptographic hashes.</p>
                </div>

                <div class="bg-slate-50 p-7 rounded-2xl border border-slate-100 hover:border-amber-200 hover:shadow-xl transition-all duration-300 space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center font-black text-lg">03</div>
                    <h3 class="font-bold text-base text-slate-800">Pre-Vetted Suppliers</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Over 1,200 verified logistics vendors integrated directly to streamline commercial acquisition.</p>
                </div>

                <div class="bg-slate-50 p-7 rounded-2xl border border-slate-100 hover:border-purple-200 hover:shadow-xl transition-all duration-300 space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center font-black text-lg">04</div>
                    <h3 class="font-bold text-base text-slate-800">High-Availability SLA</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">99.98% uptime guarantee supported by dedicated logistics architects on standby.</p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
