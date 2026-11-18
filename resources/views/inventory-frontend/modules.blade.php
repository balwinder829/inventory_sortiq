@extends('inventory-frontend.layouts.app')

@section('title', 'System Modules - StockFlow Sourcing Architecture')

@section('content')
<div class="w-full max-w-[1920px] mx-auto px-4 md:px-8 lg:px-12 xl:px-16 py-10 space-y-16 text-left pb-20" x-data="modulesApp()">

    <!-- Page Header -->

    <!-- Page Header -->
    <div class="space-y-3">
        <span class="text-[10px] font-bold text-[#2874F0] uppercase tracking-widest block">System Modules</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-800 tracking-tight">Connected Pipeline Architecture</h1>
        <p class="text-xs sm:text-sm text-slate-500 max-w-2xl font-medium">
            Explore the interconnected subsystems that power end-to-end logistics coordination from intake scanners to institutional delivery fleets.
        </p>
    </div>

    <!-- Connected Pipeline Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <template x-for="(mod, idx) in modules" :key="mod.id">
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl hover:shadow-2xl hover:-translate-y-1.5 transition-all duration-300 p-8 sm:p-10 flex flex-col justify-between space-y-6 relative overflow-hidden group">
                <div class="space-y-5">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold tracking-wider uppercase" :class="mod.badgeBg" x-text="mod.tag"></span>
                        <span class="text-3xl font-black text-slate-200 group-hover:text-[#2874F0]/30 transition-colors" x-text="mod.number"></span>
                    </div>

                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-800" x-text="mod.title"></h2>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed font-medium" x-text="mod.desc"></p>

                    <!-- Telemetry Metrics Bar -->
                    <div class="grid grid-cols-2 gap-4 pt-2">
                        <template x-for="(m, midx) in mod.metrics" :key="midx">
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="text-lg font-black text-slate-800 block" x-text="m.value"></span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mt-0.5" x-text="m.label"></span>
                            </div>
                        </template>
                    </div>

                    <!-- Features Checklist -->
                    <div class="space-y-2 pt-2">
                        <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wide">Included Subsystems</h4>
                        <div class="space-y-2">
                            <template x-for="(feat, fidx) in mod.features" :key="fidx">
                                <div class="flex items-center gap-2.5 text-xs text-slate-600 font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#2874F0]"></span>
                                    <span x-text="feat"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ url('/products') }}" class="text-xs font-bold text-[#2874F0] hover:underline flex items-center gap-1">
                        <span>Browse Catalog</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    <a href="{{ url('/#demo-form') }}" class="px-4 py-2 bg-slate-100 hover:bg-[#2874F0] hover:text-white text-slate-700 text-xs font-bold rounded-xl transition-all">
                        Request Module Spec
                    </a>
                </div>
            </div>
        </template>
    </div>

    <!-- CTA Card -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-8 sm:p-12 text-white shadow-2xl flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-2 text-left">
            <h3 class="text-2xl font-bold">Need a custom warehouse partition integration?</h3>
            <p class="text-xs sm:text-sm text-blue-100 max-w-xl font-medium">
                Our engineering team builds custom ERP adapters and REST API endpoints for institutional legacy databases.
            </p>
        </div>
        <a href="{{ url('/contact') }}" class="px-8 py-3.5 bg-white text-blue-600 font-bold text-xs uppercase tracking-wider rounded-xl shadow-lg hover:bg-blue-50 transition-all shrink-0">
            Contact Engineering
        </a>
    </div>

</div>

<script>
function modulesApp() {
    return {
        activeModule: 'depot',
        modules: [
            {
                id: 'depot',
                number: '01',
                title: 'Warehouse & Depot Control',
                tag: 'RFID & STORAGE',
                theme: 'border-blue-500/30 bg-blue-50/20',
                badgeBg: 'bg-blue-100 text-[#2874F0]',
                desc: 'Coordinate absolute inventory shelf placement across multiple storage vaults. Built-in capabilities include wireless barcode scanner tracking and automated item sorting.',
                metrics: [
                    { label: 'Storage Efficiency', value: '98.4%' },
                    { label: 'RFID Scan Speed', value: '0.2s' }
                ],
                features: [
                    'Dynamic 3D shelf allocation',
                    'Wireless handheld scanner integration',
                    'Auto-generated SKU serial logs',
                    'Multi-depot sync protocols'
                ]
            },
            {
                id: 'logistics',
                number: '02',
                title: 'Dynamic Logistics & Dispatches',
                tag: 'GPS ROUTING & CARGO',
                theme: 'border-teal-500/30 bg-teal-50/20',
                badgeBg: 'bg-teal-100 text-teal-700',
                desc: 'Orchestrate real-time dispatches with exact telemetry tracking. Optimal route matching engines calculate cargo timelines, fuel expenses, and driver availability metrics.',
                metrics: [
                    { label: 'On-Time Dispatch', value: '99.1%' },
                    { label: 'Route Cost Savings', value: '-14%' }
                ],
                features: [
                    'Live GPS fleet mapping',
                    'Dynamic load distribution',
                    'Automated cargo manifest generation',
                    'Delivery delay risk alerts'
                ]
            },
            {
                id: 'procure',
                number: '03',
                title: 'Automated Procurement Hub',
                tag: 'AI SAFETY MARGINS',
                theme: 'border-amber-500/30 bg-amber-50/20',
                badgeBg: 'bg-amber-100 text-amber-700',
                desc: 'Prevent unexpected stock depletions through self-adjusting safety reserves. The analytical sourcing cart evaluates vendor reliability indices to propose low-cost replenishment orders.',
                metrics: [
                    { label: 'Safety Level Accuracy', value: '99.8%' },
                    { label: 'Average Restock Time', value: '1.2d' }
                ],
                features: [
                    'Predictive stock warnings',
                    'Supplier index scoring',
                    'Multi-quote comparison engine',
                    'Consolidated shipping recommendations'
                ]
            },
            {
                id: 'audit',
                number: '04',
                title: 'Operations & Audit Security',
                tag: 'IMMUTABLE LOGGING',
                theme: 'border-purple-500/30 bg-purple-50/20',
                badgeBg: 'bg-purple-100 text-purple-700',
                desc: 'Safeguard organizational accountability with cryptographic transaction tracking. Every stock movement, operator access, and quote dispatch is recorded into verifiable audit logs.',
                metrics: [
                    { label: 'Ledger Audit Trails', value: '100%' },
                    { label: 'Hash Verification', value: 'Instant' }
                ],
                features: [
                    'Cryptographic tracking tokens',
                    'Role-based granular permissions',
                    'Exportable compliance audit reports',
                    'Historical transaction playback'
                ]
            }
        ]
    };
}
</script>
@endsection
