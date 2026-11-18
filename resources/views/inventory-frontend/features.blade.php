@extends('inventory-frontend.layouts.app')

@section('title', 'Features & Capabilities - StockFlow Systems')

@section('content')
<div class="w-full max-w-[1920px] mx-auto px-4 md:px-8 lg:px-12 xl:px-16 py-10 space-y-16 text-left pb-20" x-data="featuresApp()">

    <!-- Page Header -->

    <!-- Page Header -->
    <div class="space-y-3">
        <span class="text-[10px] font-bold text-[#2874F0] uppercase tracking-widest block">System Capabilities</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-800 tracking-tight">Advanced Sourcing Modules</h1>
        <p class="text-xs sm:text-sm text-slate-500 max-w-xl font-medium">
            Discover how StockFlow simplifies supply chain coordination, catalog distribution, and multi-department corporate allocations.
        </p>
    </div>

    <!-- Interactive Deck Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
        
        <!-- Left: Capability Tabs -->
        <div class="lg:col-span-5 flex flex-col gap-3.5 w-full">
            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest pl-2">
                Select Sourcing Capability
            </span>

            <template x-for="(feat, key) in features" :key="key">
                <button @click="activeTab = key"
                        :class="activeTab === key ? feat.theme + ' shadow-md' : 'bg-white border-slate-200/80 text-slate-700 hover:bg-slate-50'"
                        class="flex items-center justify-between p-4.5 rounded-2xl border text-left transition-all duration-300 cursor-pointer hover:-translate-y-0.5 group">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-white/80 shadow-xs flex items-center justify-center font-bold text-sm" :class="feat.iconColor">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-slate-800" x-text="feat.title"></h3>
                            <p class="text-[10px] text-slate-400 font-medium" x-text="feat.subtitle"></p>
                        </div>
                    </div>
                    <span class="text-xs font-extrabold" x-text="feat.metricValue"></span>
                </button>
            </template>
        </div>

        <!-- Right: Active Feature Visual Deck -->
        <div class="lg:col-span-7 bg-white p-8 sm:p-10 rounded-3xl border border-slate-100 shadow-2xl flex flex-col justify-between space-y-6">
            <div class="space-y-6">
                <!-- Header of active feature -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
                    <div>
                        <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider mb-2" :class="features[activeTab].theme" x-text="features[activeTab].badge"></span>
                        <h2 class="text-2xl font-extrabold text-slate-800" x-text="features[activeTab].title"></h2>
                    </div>
                    <div class="sm:text-right">
                        <span class="text-2xl font-black text-[#2874F0]" x-text="features[activeTab].metricValue"></span>
                        <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider" x-text="features[activeTab].metricLabel"></span>
                    </div>
                </div>

                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-medium" x-text="features[activeTab].desc"></p>

                <!-- Detailed Checklist Items -->
                <div class="space-y-3 pt-2">
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wide">Included Protocol Specifications</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <template x-for="(item, i) in features[activeTab].details" :key="i">
                            <div class="flex items-start gap-2.5 p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] font-black shrink-0 mt-0.5">✓</span>
                                <span class="text-xs text-slate-700 font-medium leading-snug" x-text="item"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ url('/products') }}" class="text-xs font-bold text-[#2874F0] hover:underline flex items-center gap-1.5">
                    <span>Inspect active items in Live Catalog</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                <a href="{{ url('/#demo-form') }}" class="px-5 py-2.5 bg-[#2874F0] hover:bg-[#2874F0]/90 text-white text-xs font-bold rounded-xl shadow-md transition-all">
                    Book Walkthrough
                </a>
            </div>
        </div>

    </div>

    <!-- Step-by-Step Lifecycle Workflow -->
    <div class="border-t border-slate-100 pt-16 space-y-8">
        <div>
            <span class="text-[10px] font-bold text-[#2874F0] uppercase tracking-widest block">STEP-BY-STEP WORKFLOW</span>
            <h2 class="text-2xl font-extrabold text-slate-800 mt-1">Operational Lifecycle Steps</h2>
            <p class="text-xs text-slate-400 font-medium max-w-lg mt-0.5">
                Review the exact pipeline through which this StockFlow intelligence module optimizes logistics.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <template x-for="(step, sidx) in features[activeTab].steps" :key="sidx">
                <div class="bg-white p-7 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden group hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="absolute top-0 right-0 p-4 text-4xl font-black text-slate-100/70 select-none group-hover:scale-110 group-hover:text-blue-500/10 transition-all" x-text="step.step"></div>
                    <div class="space-y-3 relative z-10">
                        <span class="w-9 h-9 rounded-xl bg-blue-50 text-[#2874F0] flex items-center justify-center font-black text-xs" x-text="step.step"></span>
                        <h3 class="text-base font-bold text-slate-800" x-text="step.title"></h3>
                        <p class="text-xs text-slate-500 font-medium leading-relaxed" x-text="step.desc"></p>
                    </div>
                </div>
            </template>
        </div>
    </div>

</div>

<script>
function featuresApp() {
    return {
        activeTab: 'balances',
        features: {
            balances: {
                id: 'balances',
                title: 'Stock Balances',
                subtitle: 'Stock Balance Tickers',
                theme: 'bg-blue-50 text-[#2874F0] border-blue-200',
                iconColor: 'text-[#2874F0]',
                desc: 'Every product detail, stock balance, and category distribution is refreshed automatically. Sourcing assistants see safety flags without manual re-querying, maximizing procurement allocation velocity across active catalog levels.',
                metricValue: '99.98%',
                metricLabel: 'Inventory Precision',
                badge: 'Live Stock Counts',
                details: [
                    'Real-time updates on active catalog levels',
                    'SKU Identifier Lookup: Fetch codes and suppliers',
                    'Category Sorting: Group Electronics, Apparel, Furniture',
                    'Batch Expirations: Track laboratory reagent and supply dates',
                    'Logistics Core integration with automated status syncs',
                    'Dynamic allocation tickers computed instantly'
                ],
                steps: [
                    { step: '01', title: 'Intake Scan', desc: 'Warehouse receivers scan RFID codes at the loading dock instantly.' },
                    { step: '02', title: 'Live Sync', desc: 'Database ledger immediately synchronizes counts to international logs.' },
                    { step: '03', title: 'Telemetry Update', desc: 'Real-time stock level flags calculate active safety thresholds.' }
                ]
            },
            registry: {
                id: 'registry',
                title: 'Asset Registry',
                subtitle: 'Enterprise Database',
                theme: 'bg-indigo-50 text-indigo-600 border-indigo-200',
                iconColor: 'text-indigo-600',
                desc: 'Fully indexed database of every corporate asset, computer router, dining seat, laboratory appliance, and warehouse rack. Features real-time tracking, barcode logging, and dynamic status updates.',
                metricValue: '12,450 Units',
                metricLabel: 'Indexed Assets',
                badge: 'Operational Live',
                details: [
                    'Electronics Tracking: Laptops, screens, and routers',
                    'Laboratory Apparatus: Reagents and delicate equipment',
                    'Office Furniture: Log desks, chairs, and lecterns',
                    'Fulfillment History Logs: Lifecycle traces for units',
                    'Barcode scanning integration ready',
                    'Warehouse partition assignment records'
                ],
                steps: [
                    { step: '01', title: 'Asset Intake', desc: 'Log and tag new corporate assets with detailed catalog specs.' },
                    { step: '02', title: 'Vault Allocation', desc: 'Assign items to specific warehouse compartments or rack grids.' },
                    { step: '03', title: 'Lifecycle Logs', desc: 'Chronologically record all dispatches, moves, and audit details.' }
                ]
            },
            margins: {
                id: 'margins',
                title: 'Safety Margins',
                subtitle: 'Low Stock Warning Alarm',
                theme: 'bg-amber-50 text-amber-600 border-amber-200',
                iconColor: 'text-amber-600',
                desc: 'Ensure enterprise departments remain fully stocked. Dynamic warnings trigger automatically when quantities fall to safety stock thresholds, signaling restock actions immediately.',
                metricValue: '4 Units',
                metricLabel: 'Warning Threshold',
                badge: 'Auto Warnings',
                details: [
                    'Live unread bell counts in system header',
                    'Reconciliation control panel adjustments',
                    'Safety thresholds configurable by stock item',
                    'Automatic restock procurement notifications',
                    'Buffer calculations based on historical demand',
                    'Visual indicators for rapid reconciliation actions'
                ],
                steps: [
                    { step: '01', title: 'Continuous Scan', desc: 'System constantly evaluates stock levels against active limits.' },
                    { step: '02', title: 'Alert Trigger', desc: 'Triggers amber and red flags when inventory drops below bounds.' },
                    { step: '03', title: 'Restock Orders', desc: 'Instantly routes automatic restock notifications to buyer agents.' }
                ]
            },
            cart: {
                id: 'cart',
                title: 'Sourcing Cart',
                subtitle: 'Centralized Quote Compile Cart',
                theme: 'bg-emerald-50 text-emerald-600 border-emerald-200',
                iconColor: 'text-emerald-600',
                desc: 'Stage bulk quantities inside a centralized user basket. Perform safety stock safety tests locally, compile remarks, and dispatch quote inquiries to logistics hubs in one unified click.',
                metricValue: '< 1.5s',
                metricLabel: 'Dispatch Velocity',
                badge: 'Smart Sourcing',
                details: [
                    'Custom operators consignment remarks',
                    'Real-time safety count indicators',
                    'Consolidated multi-SKU dispatches',
                    'Draft quote compiling for validation',
                    'Automatic supplier matching filters',
                    'Immediate tracking token generation'
                ],
                steps: [
                    { step: '01', title: 'Consolidate Demand', desc: 'Compile product allocations and group target quantities into one basket.' },
                    { step: '02', title: 'Append Remarks', desc: 'Buyers append custom delivery specifications and timelines.' },
                    { step: '03', title: 'Sourcing Request', desc: 'Consolidated quotes are transmitted directly to pre-vetted vendors.' }
                ]
            },
            suppliers: {
                id: 'suppliers',
                title: 'Suppliers Hub',
                subtitle: 'Vetted Merchant Network',
                theme: 'bg-rose-50 text-rose-600 border-rose-200',
                iconColor: 'text-rose-600',
                desc: 'Connect with verified manufacturing networks, contract partners, and supply chains. Monitor supplier response latencies, pricing benchmarks, and order fulfillment accuracy from a unified portal.',
                metricValue: '1,200+',
                metricLabel: 'Active Partners',
                badge: 'Fulfillment Keys',
                details: [
                    'Certified Merchants: Pre-vetted corporate vendors',
                    'Quote Comparisons: Evaluate price and latency',
                    'Supplier Performance: Track dispatch accuracy',
                    'Direct message integration for specifications',
                    'Service-Level-Agreement (SLA) status trackers',
                    'Alternate source suggestion for high-scarcity items'
                ],
                steps: [
                    { step: '01', title: 'Vendor Match', desc: 'System targets matching pre-vetted distributors in the region.' },
                    { step: '02', title: 'Dynamic Bidding', desc: 'Vetted suppliers submit competitive pricing and delivery windows.' },
                    { step: '03', title: 'SLA Evaluation', desc: 'Evaluate response time, dispatch accuracy, and quality metrics.' }
                ]
            },
            ledger: {
                id: 'ledger',
                title: 'Compliance Log',
                subtitle: 'Fulfillment & Audit Ledger',
                theme: 'bg-purple-50 text-purple-600 border-purple-200',
                iconColor: 'text-purple-600',
                desc: 'Every single dispatched quote request, status shift, and supplier action is recorded permanently. Safeguard corporate accountability with detailed operational reports.',
                metricValue: '100%',
                metricLabel: 'Audit Trailing',
                badge: 'Traceability Standard',
                details: [
                    'Unique identification tracking keys',
                    'Operator department logs',
                    'Dispatch history logs in Dashboard',
                    'Immutable timestamped transaction logs',
                    'Regulatory alignment status checks',
                    'Exportable CSV reports for compliance reviews'
                ],
                steps: [
                    { step: '01', title: 'Immutable Logging', desc: 'Every transaction, quote change, and action is logged instantly.' },
                    { step: '02', title: 'Security Hash Sign', desc: 'Transaction details are stamped with unique audit keys.' },
                    { step: '03', title: 'Compliance Review', desc: 'Auditors pull system logs to ensure strict operational compliance.' }
                ]
            }
        }
    };
}
</script>
@endsection
