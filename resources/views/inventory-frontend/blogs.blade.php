@extends('inventory-frontend.layouts.app')

@section('title', 'Insights & Logistics Articles - StockFlow Systems')

@section('content')
<div class="w-full max-w-[1920px] mx-auto px-4 md:px-8 lg:px-12 xl:px-16 py-10 space-y-12 text-left pb-20" x-data="blogsApp()">

    <!-- Page Header -->

    <!-- Page Header -->
    <div class="text-center space-y-4 pt-4 max-w-2xl mx-auto">
        <span class="text-xs font-bold text-[#2874F0] uppercase tracking-widest block">Insights & Updates</span>
        <h1 class="text-4xl md:text-5xl font-extrabold text-slate-800 tracking-tight">
            Our Latest <span class="text-[#2874F0]">Logistics Articles</span>
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 font-medium">
            Explore industry insights, expert tips, and the latest news in inventory controls and corporate supply management.
        </p>
    </div>

    <!-- Category Filters Pills -->
    <div class="flex flex-wrap items-center justify-center gap-2 pt-2">
        <button @click="activeCategory = 'All'" :class="activeCategory === 'All' ? 'bg-[#2874F0] text-white shadow-md' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50'" class="px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer">
            All Topics
        </button>
        <template x-for="cat in ['Logistics', 'Management', 'Tutorials', 'Technology', 'Finance', 'Compliance']" :key="cat">
            <button @click="activeCategory = cat" :class="activeCategory === cat ? 'bg-[#2874F0] text-white shadow-md' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50'" class="px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer" x-text="cat"></button>
        </template>
    </div>

    <!-- Articles Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <template x-for="post in filtered" :key="post.id">
            <article class="bg-white rounded-3xl border border-slate-100 shadow-md hover:shadow-2xl hover:-translate-y-1.5 transition-all duration-300 overflow-hidden flex flex-col group">
                <div class="relative h-52 overflow-hidden bg-slate-100">
                    <img :src="post.image" :alt="post.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <span class="absolute top-3 left-3 px-3 py-1 bg-white/90 backdrop-blur-md rounded-full text-[10px] font-bold text-[#2874F0] uppercase tracking-wider shadow-sm" x-text="post.category"></span>
                </div>
                
                <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                    <div class="space-y-2">
                        <div class="flex items-center gap-3 text-[11px] font-bold text-slate-400">
                            <span x-text="post.date"></span>
                            <span>•</span>
                            <span x-text="post.readTime"></span>
                        </div>
                        <h3 class="text-base font-bold text-slate-800 group-hover:text-[#2874F0] transition-colors leading-snug" x-text="post.title"></h3>
                        <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed font-medium" x-text="post.excerpt"></p>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs font-bold text-[#2874F0] group-hover:underline flex items-center gap-1">
                            Read Full Guide
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </span>
                    </div>
                </div>
            </article>
        </template>
    </div>

</div>

<script>
function blogsApp() {
    return {
        activeCategory: 'All',
        blogs: [
            { id: 1, title: 'How to Optimize Your Warehouse Layout for Speed', excerpt: 'Discover the secrets of efficient warehouse design and reduce picking time.', category: 'Logistics', date: 'May 12, 2026', readTime: '5 min read', image: 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=600&auto=format&fit=crop&q=80' },
            { id: 2, title: 'Top 5 Inventory Management Strategies in 2026', excerpt: 'Stay ahead of the curve with these modern inventory strategies for lean teams.', category: 'Management', date: 'May 10, 2026', readTime: '7 min read', image: 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=600&auto=format&fit=crop&q=80' },
            { id: 3, title: 'Understanding Safety Stock and Reorder Points', excerpt: 'Learn how to calculate and maintain safety stock to prevent catastrophic stockouts.', category: 'Tutorials', date: 'May 08, 2026', readTime: '4 min read', image: 'https://images.unsplash.com/photo-1566576912321-d58ddd7a6088?w=600&auto=format&fit=crop&q=80' },
            { id: 4, title: 'The Future of Automated Fulfillment Centers', excerpt: 'Robotics and AI telemetry are transforming how enterprise organizations manage supply chains.', category: 'Technology', date: 'May 05, 2026', readTime: '6 min read', image: 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=600&auto=format&fit=crop&q=80' },
            { id: 5, title: 'Reducing Overhead Costs in Procurement', excerpt: 'Actionable tips to cut down unnecessary consignment and dispatch expenses.', category: 'Finance', date: 'May 02, 2026', readTime: '8 min read', image: 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=600&auto=format&fit=crop&q=80' },
            { id: 6, title: 'Best Practices for Supplier Audits', excerpt: 'Ensure quality, SLA compliance, and dispatch reliability from third-party vendor networks.', category: 'Compliance', date: 'April 28, 2026', readTime: '5 min read', image: 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=600&auto=format&fit=crop&q=80' }
        ],
        get filtered() {
            if (this.activeCategory === 'All') return this.blogs;
            return this.blogs.filter(b => b.category === this.activeCategory);
        }
    };
}
</script>
@endsection
