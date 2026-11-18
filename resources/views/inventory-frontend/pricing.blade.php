@extends('inventory-frontend.layouts.app')

@section('title', 'Sourcing Procurement Tiers & Pricing - StockFlow Systems')

@section('content')
<div class="w-full max-w-[1920px] mx-auto px-4 md:px-8 lg:px-12 xl:px-16 py-10 space-y-16 text-left pb-20">
    
    <!-- Page Header -->
    <div class="text-center space-y-3">
        <span class="text-[10px] font-bold text-[#2874F0] uppercase tracking-widest block">Flexible Operations Pricing</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-800 tracking-tight">Sourcing Procurement Tiers</h1>
        <p class="text-xs sm:text-sm text-slate-500 max-w-xl mx-auto font-medium">
            Choose an institutional coordination tier scaled to your warehouse dimensions and procurement fleet parameters.
        </p>
    </div>

    <!-- Pricing Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch pt-6">
        
        <!-- Tier 1: Basic Sourcing -->
        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-md hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 flex flex-col justify-between">
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Basic Sourcing</h3>
                    <p class="text-xs text-slate-400 font-medium mt-1">For single department inventory hubs</p>
                </div>
                <div class="flex items-baseline gap-1">
                    <span class="text-4xl font-black text-slate-800">$49</span>
                    <span class="text-xs text-slate-400 font-bold">/ month</span>
                </div>
                <ul class="space-y-3.5 text-xs text-slate-600 border-t border-slate-100 pt-6">
                    <li class="flex items-center gap-2.5">
                        <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] font-black">✓</span>
                        <span>Up to 500 Sourcing Items</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] font-black">✓</span>
                        <span>1 Localized Storage Warehouse</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] font-black">✓</span>
                        <span>Standard Helpdesk Support</span>
                    </li>
                    <li class="flex items-center gap-2.5 text-slate-400">
                        <span class="w-4 h-4 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-[10px] font-black">-</span>
                        <span>Multi-Depot Partitioning</span>
                    </li>
                </ul>
            </div>
            <a href="{{ url('/signup') }}" class="w-full h-11 mt-8 border border-slate-200 hover:border-[#2874F0] hover:bg-blue-50/40 text-slate-700 hover:text-[#2874F0] text-xs font-bold rounded-xl transition-all flex items-center justify-center">
                Activate Basic Plan
            </a>
        </div>

        <!-- Tier 2: Enterprise Hub (Recommended) -->
        <div class="bg-white p-8 rounded-3xl border-2 border-[#2874F0] shadow-2xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 flex flex-col justify-between relative transform lg:-translate-y-3">
            <span class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-[#2874F0] text-white text-[9px] font-extrabold tracking-widest uppercase px-4 py-1.5 rounded-full shadow-lg">
                MOST POPULAR
            </span>
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Enterprise Hub</h3>
                    <p class="text-xs text-slate-400 font-medium mt-1">For growing multi-department logistics</p>
                </div>
                <div class="flex items-baseline gap-1">
                    <span class="text-4xl font-black text-[#2874F0]">$149</span>
                    <span class="text-xs text-slate-400 font-bold">/ month</span>
                </div>
                <ul class="space-y-3.5 text-xs text-slate-600 border-t border-slate-100 pt-6">
                    <li class="flex items-center gap-2.5">
                        <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] font-black">✓</span>
                        <span>Up to 5,000 Sourcing Items</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] font-black">✓</span>
                        <span>Up to 5 Multi-Warehouse Clusters</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] font-black">✓</span>
                        <span>Automated Safety Stock Warning Alarms</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] font-black">✓</span>
                        <span>Pre-vetted Merchant Quote Routing</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] font-black">✓</span>
                        <span>Dedicated SLA Operations Officer</span>
                    </li>
                </ul>
            </div>
            <a href="{{ url('/signup') }}" class="w-full h-11 mt-8 bg-[#2874F0] hover:bg-[#2874F0]/90 text-white text-xs font-bold rounded-xl shadow-lg shadow-blue-500/20 hover:shadow-xl transition-all flex items-center justify-center">
                Activate Enterprise Hub
            </a>
        </div>

        <!-- Tier 3: Multi-Warehouse Fleet -->
        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-md hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 flex flex-col justify-between">
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Multi-Warehouse Fleet</h3>
                    <p class="text-xs text-slate-400 font-medium mt-1">For national institutions & commercial fleets</p>
                </div>
                <div class="flex items-baseline gap-1">
                    <span class="text-4xl font-black text-slate-800">$299</span>
                    <span class="text-xs text-slate-400 font-bold">/ month</span>
                </div>
                <ul class="space-y-3.5 text-xs text-slate-600 border-t border-slate-100 pt-6">
                    <li class="flex items-center gap-2.5">
                        <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] font-black">✓</span>
                        <span>Unlimited Sourcing Items & SKUs</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] font-black">✓</span>
                        <span>Unlimited Vault & Partition sync</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] font-black">✓</span>
                        <span>Real-Time GPS Fleet Telemetry</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] font-black">✓</span>
                        <span>Custom ERP / REST API Connectors</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] font-black">✓</span>
                        <span>24/7 Guaranteed 15-Minute SLA</span>
                    </li>
                </ul>
            </div>
            <a href="{{ url('/signup') }}" class="w-full h-11 mt-8 border border-slate-200 hover:border-[#2874F0] hover:bg-blue-50/40 text-slate-700 hover:text-[#2874F0] text-xs font-bold rounded-xl transition-all flex items-center justify-center">
                Activate Multi-Warehouse
            </a>
        </div>

    </div>

    <!-- Pricing FAQs Section -->
    <div class="bg-slate-50 p-8 sm:p-12 rounded-3xl border border-slate-100 space-y-6">
        <h3 class="text-xl font-bold text-slate-800">Frequently Asked Sourcing Questions</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs text-slate-600">
            <div class="space-y-1">
                <h4 class="font-bold text-slate-800 text-sm">Can I switch plans mid-billing cycle?</h4>
                <p class="leading-relaxed">Yes. Prorated adjustments are applied automatically to your corporate balance when scaling warehouses.</p>
            </div>
            <div class="space-y-1">
                <h4 class="font-bold text-slate-800 text-sm">Do suppliers pay to be in the portal?</h4>
                <p class="leading-relaxed">Pre-vetted suppliers undergo institutional verification without listing markups, keeping prices transparent.</p>
            </div>
            <div class="space-y-1">
                <h4 class="font-bold text-slate-800 text-sm">How does the 14-day trial work?</h4>
                <p class="leading-relaxed">Enjoy unrestricted access to all Enterprise Hub features with test data before configuring your warehouse partitions.</p>
            </div>
            <div class="space-y-1">
                <h4 class="font-bold text-slate-800 text-sm">What payment gateways are supported?</h4>
                <p class="leading-relaxed">We support commercial invoicing, ACH bank transfers, corporate debit cards, and Stripe billing portals.</p>
            </div>
        </div>
    </div>

</div>
@endsection
