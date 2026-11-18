@extends('inventory-frontend.layouts.app')

@section('title', 'Support & Helpdesk - StockFlow Systems')

@section('content')
<div class="w-full max-w-[1920px] mx-auto px-4 md:px-8 lg:px-12 xl:px-16 py-10 space-y-12 text-left pb-20" x-data="contactApp()">

    <!-- Header -->
    <div class="space-y-3">
        <span class="text-[10px] font-bold text-[#2874F0] uppercase tracking-widest block">Inventory Helpdesk</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-800 tracking-tight">Support & Sourcing Inquiries</h1>
        <p class="text-xs sm:text-sm text-slate-500 max-w-xl font-medium">
            Need operational assistance or custom procurement agreements? Dispatch a note directly to our logistics hub operators.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Form Column -->
        <form @submit.prevent="submitContact()" class="lg:col-span-7 bg-white p-8 sm:p-10 rounded-3xl border border-slate-100 shadow-xl space-y-5">
            <h3 class="text-lg font-bold text-slate-800">Dispatch Support Ticket</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1.5">Your Name</label>
                    <input type="text" x-model="form.name" required placeholder="Sarah Connor" class="w-full h-11 px-4 text-xs text-slate-800 bg-[#F1F3F6] border border-transparent rounded-xl focus:bg-white focus:outline-none focus:border-[#2874F0]/40 font-medium transition-all" />
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1.5">Official Corporate Email</label>
                    <input type="email" x-model="form.email" required placeholder="s.connor@enterprise.com" class="w-full h-11 px-4 text-xs text-slate-800 bg-[#F1F3F6] border border-transparent rounded-xl focus:bg-white focus:outline-none focus:border-[#2874F0]/40 font-medium transition-all" />
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1.5">Target Department</label>
                <select x-model="form.dept" class="w-full h-11 px-4 text-xs font-bold text-slate-700 bg-[#F1F3F6] border border-transparent rounded-xl focus:bg-white focus:outline-none focus:border-[#2874F0]/40 transition-all cursor-pointer">
                    <option value="Operations">Operations & Warehouse Routing</option>
                    <option value="Procurement">Procurement & Sourcing Contracts</option>
                    <option value="IT Integration">IT Integration & API Connectors</option>
                    <option value="Billing">Enterprise Billing & Invoicing</option>
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1.5">Inquiry Details</label>
                <textarea x-model="form.message" required rows="5" placeholder="Describe the inventory items, SLA questions, or technical specifications..." class="w-full p-4 text-xs text-slate-800 bg-[#F1F3F6] border border-transparent rounded-xl focus:bg-white focus:outline-none focus:border-[#2874F0]/40 font-medium transition-all resize-none"></textarea>
            </div>

            <button type="submit" :disabled="submitting" class="w-full py-4 bg-[#2874F0] hover:bg-[#2874F0]/90 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-blue-500/20 hover:shadow-xl transition-all cursor-pointer">
                <span x-show="!submitting">Submit Inquiry to Operations</span>
                <span x-show="submitting" x-cloak>Transmitting note...</span>
            </button>
        </form>

        <!-- Information Column -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-md space-y-6">
                <h3 class="text-base font-bold text-slate-800">Logistics Operations Hub</h3>

                <div class="space-y-4 text-xs text-slate-600">
                    <div class="flex items-start gap-3">
                        <span class="p-2 rounded-xl bg-blue-50 text-[#2874F0] shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </span>
                        <div>
                            <strong class="text-slate-800 block text-xs">Corporate Office</strong>
                            <p class="text-slate-500 mt-0.5 leading-relaxed">E-51, Second Floor, Phase-8, Industrial Area, S.A.S. Nagar, Punjab 160071</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <span class="p-2 rounded-xl bg-emerald-50 text-emerald-600 shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </span>
                        <div>
                            <strong class="text-slate-800 block text-xs">Direct Support Desk</strong>
                            <a href="tel:+919646522110" class="text-slate-500 hover:text-[#2874F0] mt-0.5 block">+91 9646522110</a>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <span class="p-2 rounded-xl bg-purple-50 text-purple-600 shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </span>
                        <div>
                            <strong class="text-slate-800 block text-xs">Email Correspondence</strong>
                            <a href="mailto:sortiqsolutions@gmail.com" class="text-slate-500 hover:text-[#2874F0] mt-0.5 block">sortiqsolutions@gmail.com</a>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Operating Hours</span>
                    <p class="text-xs text-slate-600 font-medium">Monday - Friday: 9:00 AM - 7:00 PM IST<br>Emergency 24/7 SLA standby for Enterprise tier fleets.</p>
                </div>
            </div>
        </div>

    </div>

</div>

<script>
function contactApp() {
    return {
        form: { name: '', email: '', dept: 'Operations', message: '' },
        submitting: false,
        submitContact() {
            if (!this.form.name || !this.form.email || !this.form.message) {
                Alpine.store('cart').notify('Please fill out all required fields.', 'error');
                return;
            }
            this.submitting = true;
            setTimeout(() => {
                Alpine.store('cart').notify('Helpdesk request submitted! A sourcing officer will contact you shortly.');
                this.form = { name: '', email: '', dept: 'Operations', message: '' };
                this.submitting = false;
            }, 800);
        }
    };
}
</script>
@endsection
