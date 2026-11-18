<footer class="bg-slate-900 text-slate-300 pt-16 pb-8 border-t border-slate-800">
    <div class="w-full max-w-[1920px] mx-auto px-4 md:px-8 lg:px-12 xl:px-16">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-12 items-start">
            <!-- Col 1: Brand & Bio -->
            <div class="flex flex-col h-full space-y-4">
                <div class="mb-2">
                    <img src="{{ asset('assets/inventory/footerlogo.png') }}" 
                         alt="StockFlow Systems" 
                         class="h-14 md:h-16 w-auto object-contain max-w-[240px] rounded-full border border-slate-700 bg-white/5 p-1" 
                         onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'flex items-center gap-2\'><span class=\'w-10 h-10 rounded-xl bg-[#2874F0] flex items-center justify-center text-white font-black text-xl shadow-md\'>S</span><span class=\'font-black text-xl tracking-tight text-white\'>Stock<span class=\'text-[#2874F0]\'>Flow</span></span></div>';" />
                </div>
                <p class="text-sm leading-7 text-slate-400 text-justify">
                    Real-time corporate stock coordination, supply tracking, and quick quote acquisition for enterprise corporations and commercial logistics operations for the inventory stocks and product purposes.
                </p>
            </div>

            <!-- Col 2: Quick Links -->
            <div class="flex flex-col h-full">
                <h4 class="text-sm font-bold uppercase tracking-wider text-white mb-5 leading-none">
                    Quick Links
                </h4>
                <ul class="flex flex-col gap-3 text-sm text-slate-400 leading-6">
                    <li><a href="{{ url('/') }}" class="hover:text-white transition-colors duration-200">Overview Home</a></li>
                    <li><a href="{{ url('/about') }}" class="hover:text-white transition-colors duration-200">About Us</a></li>
                    <li><a href="{{ url('/modules') }}" class="hover:text-white transition-colors duration-200">System Modules</a></li>
                    <li><a href="{{ url('/products') }}" class="hover:text-white transition-colors duration-200">Stock Catalog</a></li>
                    <li><a href="{{ url('/login') }}" class="hover:text-white transition-colors duration-200">Operator Access</a></li>
                    <li><a href="{{ url('/signup') }}" class="hover:text-white transition-colors duration-200">Register Account</a></li>
                </ul>
            </div>

            <!-- Col 3: Asset Categories -->
            <div class="flex flex-col h-full">
                <h4 class="text-sm font-bold uppercase tracking-wider text-white mb-5 leading-none">
                    Asset Categories
                </h4>
                <ul class="flex flex-col gap-3 text-sm text-slate-400 leading-6">
                    <li><a href="{{ url('/products') }}" class="hover:text-white transition-colors duration-200">Corporate Electronics</a></li>
                    <li><a href="{{ url('/products') }}" class="hover:text-white transition-colors duration-200">Corporate Apparel</a></li>
                    <li><a href="{{ url('/products') }}" class="hover:text-white transition-colors duration-200">Kitchen & Dining Supplies</a></li>
                    <li><a href="{{ url('/products') }}" class="hover:text-white transition-colors duration-200">Office Furniture & Chairs</a></li>
                    <li><a href="{{ url('/products') }}" class="hover:text-white transition-colors duration-200">Industrial Tools & Equipment</a></li>
                    <li><a href="{{ url('/products') }}" class="hover:text-white transition-colors duration-200">Corporate Vehicles & Machinery</a></li>
                </ul>
            </div>

            <!-- Col 4: Support & Help -->
            <div class="flex flex-col h-full">
                <h4 class="text-sm font-bold uppercase tracking-wider text-white mb-5 leading-none">
                    Support & Help
                </h4>
                <div class="space-y-4">
                    <a href="https://maps.google.com/?q=E-51,+Second+Floor,+Phase-8,+Industrial+Area,+S.A.S.+Nagar,+Punjab+160071" target="_blank" rel="noopener noreferrer" class="flex items-start gap-3 text-sm text-slate-400 leading-6 hover:text-[#2874F0] transition-colors">
                        <svg class="w-4 h-4 text-[#2874F0] shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>E-51, Second Floor, Phase-8, Industrial Area, S.A.S. Nagar, Punjab 160071</span>
                    </a>

                    <a href="tel:+919646522110" class="flex items-center gap-3 text-sm text-slate-400 hover:text-[#2874F0] transition-colors">
                        <svg class="w-4 h-4 text-[#2874F0] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span>+91 9646522110</span>
                    </a>

                    <a href="mailto:sortiqsolutions@gmail.com" class="flex items-center gap-3 text-sm text-slate-400 break-all hover:text-[#2874F0] transition-colors">
                        <svg class="w-4 h-4 text-[#2874F0] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span>sortiqsolutions@gmail.com</span>
                    </a>

                    <div class="flex items-center gap-3 pt-2">
                        <a href="https://x.com/SortiqSolutions" target="_blank" class="p-2.5 bg-slate-800 hover:bg-[#2874F0] hover:text-white text-slate-400 rounded-lg transition-all duration-300" title="Twitter / X">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="https://www.linkedin.com/company/sortiq-solutions/" target="_blank" class="p-2.5 bg-slate-800 hover:bg-[#2874F0] hover:text-white text-slate-400 rounded-lg transition-all duration-300" title="LinkedIn">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                        </a>
                        <a href="https://sortiqsolutions.com/" target="_blank" class="p-2.5 bg-slate-800 hover:bg-[#2874F0] hover:text-white text-slate-400 rounded-lg transition-all duration-300" title="Website">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-14 pt-6 border-t border-slate-800 text-center text-sm text-slate-500 leading-6">
            &copy; {{ date('Y') }} Sortiq Solutions Pvt. Ltd. All rights reserved.
        </div>
    </div>
</footer>
