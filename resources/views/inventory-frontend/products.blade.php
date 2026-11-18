@extends('inventory-frontend.layouts.app')

@section('title', 'Live Stock Catalog - StockFlow Systems')

@section('content')
<div class="w-full max-w-[1920px] mx-auto px-4 md:px-8 lg:px-12 xl:px-16 py-10 space-y-8 text-left pb-20" x-data="catalogApp()">

    <!-- Page Header -->
    <div class="space-y-2">
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-800">Operational Stock Catalog</h1>
        <p class="text-xs sm:text-sm text-slate-500 font-medium">
            Search items, check real-time stock levels, and coordinate bulk quote requests directly into your procurement basket.
        </p>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-center">
        <!-- Search Input -->
        <div class="relative sm:col-span-2">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </span>
            <input type="text" x-model="search" placeholder="Search by item name, SKU, vendor..." class="w-full h-11 pl-10 pr-4 text-xs text-slate-800 bg-[#F1F3F6] border border-transparent rounded-xl focus:bg-white focus:outline-none focus:border-[#2874F0]/40 font-medium transition-all">
        </div>

        <!-- Category Dropdown -->
        <div class="flex items-center gap-2 bg-slate-50 px-3.5 py-2.5 rounded-xl border border-slate-200/80">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Group</span>
            <select x-model="category" class="w-full text-xs font-bold text-slate-700 bg-transparent focus:outline-none cursor-pointer">
                <option value="All">All Categories</option>
                <option value="Electronics">Electronics</option>
                <option value="Apparel">Apparel</option>
                <option value="Home & Kitchen">Home & Kitchen</option>
                <option value="Office">Office</option>
            </select>
        </div>

        <!-- Stock Level Dropdown -->
        <div class="flex items-center gap-2 bg-slate-50 px-3.5 py-2.5 rounded-xl border border-slate-200/80">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status</span>
            <select x-model="status" class="w-full text-xs font-bold text-slate-700 bg-transparent focus:outline-none cursor-pointer">
                <option value="All">All Stock Levels</option>
                <option value="In Stock">In Stock</option>
                <option value="Low Stock">Low Stock</option>
                <option value="Out of Stock">Out of Stock</option>
            </select>
        </div>
    </div>

    <!-- Active Count & Results Bar -->
    <div class="flex items-center justify-between text-xs text-slate-500 font-medium px-1">
        <span>Showing <strong class="text-slate-800" x-text="filtered.length"></strong> catalog items</span>
        <div class="flex items-center gap-2">
            <span>Sort By:</span>
            <button @click="sortBy = 'name'" :class="sortBy === 'name' ? 'text-[#2874F0] font-bold' : 'text-slate-500 hover:text-slate-700'">Name</button>
            <span>|</span>
            <button @click="sortBy = 'price'" :class="sortBy === 'price' ? 'text-[#2874F0] font-bold' : 'text-slate-500 hover:text-slate-700'">Price</button>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <template x-for="product in filtered" :key="product.id">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between overflow-hidden group">
                <!-- Image Container with Status Badges -->
                <div class="relative h-52 bg-slate-50 overflow-hidden">
                    <img :src="product.image" :alt="product.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    
                    <!-- Stock Badge -->
                    <span class="absolute top-3 left-3 px-2.5 py-1 text-white font-bold text-[9px] uppercase tracking-wider rounded-md backdrop-blur-xs shadow-xs"
                          :class="product.stock === 0 ? 'bg-rose-500' : (product.stock <= 4 ? 'bg-amber-500' : 'bg-emerald-500')"
                          x-text="product.stock === 0 ? 'Out of Stock' : (product.stock <= 4 ? 'Low Stock (' + product.stock + ')' : 'In Stock (' + product.stock + ')')">
                    </span>

                    <!-- Wishlist Toggle Button -->
                    <button @click="$store.cart.toggleWishlist(product.id)" 
                            class="absolute top-3 right-3 p-2 rounded-full bg-white/90 hover:bg-white text-slate-400 hover:text-red-500 transition-colors shadow-md cursor-pointer"
                            title="Save to Wishlist">
                        <svg class="w-4 h-4" :class="$store.cart.wishlist.includes(product.id) ? 'fill-red-500 text-red-500' : 'fill-none'" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </button>
                </div>

                <!-- Product Content -->
                <div class="p-5 flex flex-col justify-between flex-1 space-y-4">
                    <div>
                        <div class="flex items-center justify-between text-[10px] text-slate-400 font-mono">
                            <span x-text="product.sku"></span>
                            <span class="font-sans font-bold text-slate-500" x-text="product.category"></span>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800 mt-1 truncate" x-text="product.name"></h3>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed" x-text="product.description"></p>
                        <p class="text-[10px] text-slate-400 font-semibold mt-2">Vendor: <span class="text-slate-600" x-text="product.supplier"></span></p>
                    </div>

                    <!-- Footer Action -->
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                        <div>
                            <span class="text-[10px] text-slate-400 font-bold uppercase block leading-none">Est. Unit Price</span>
                            <span class="text-lg font-black text-[#2874F0]" x-text="'$' + product.price.toLocaleString()"></span>
                        </div>
                        
                        <template x-if="product.stock > 0">
                            <button @click="$store.cart.addItem(product, 1)" class="px-4 py-2 bg-[#2874F0] hover:bg-[#2874F0]/90 text-white rounded-xl text-xs font-bold flex items-center gap-1.5 shadow-md shadow-blue-500/10 cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                Add Quote
                            </button>
                        </template>

                        <template x-if="product.stock === 0">
                            <button @click="$store.cart.notify('Item is backordered. Sourcing notification registered!', 'error')" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold cursor-pointer">
                                Backorder
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </template>
    </div>

</div>

<script>
function catalogApp() {
    return {
        search: '',
        category: 'All',
        status: 'All',
        sortBy: 'name',
        products: [
            { id: 1, name: 'iPhone 15 Pro Max', sku: 'IPH15PM-256-BLU', category: 'Electronics', price: 1199, stock: 15, supplier: 'Apple Logistics', description: 'Titanium design, A17 Pro chip, custom Action button, and a powerful 3x or 5x Telephoto camera.', image: 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=600&auto=format&fit=crop&q=80' },
            { id: 2, name: 'MacBook Air M3', sku: 'MACAIRM3-512-SLV', category: 'Electronics', price: 1299, stock: 8, supplier: 'Apple Logistics', description: 'Supercharged by the next-generation M3 chip, with up to 18 hours of battery life and a stunning Liquid Retina display.', image: 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=600&auto=format&fit=crop&q=80' },
            { id: 3, name: 'Sony WH-1000XM5', sku: 'SONY-XM5-BLK', category: 'Electronics', price: 349, stock: 3, supplier: 'Sony Authorized Dist.', description: 'Industry-leading noise cancellation, exceptional sound quality, and crystal-clear hands-free calling.', image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&auto=format&fit=crop&q=80' },
            { id: 4, name: "Men's Slim Fit Jeans", sku: 'GAP-SFJ-32', category: 'Apparel', price: 49, stock: 50, supplier: 'Global Apparel Group', description: 'Classic slim-fit style with comfortable stretch denim, perfect for everyday casual wear.', image: 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=600&auto=format&fit=crop&q=80' },
            { id: 5, name: 'Non-Stick Cookware Set', sku: 'TEF-NSC-10P', category: 'Home & Kitchen', price: 120, stock: 14, supplier: 'Tefal Home Products', description: '10-piece dishwasher safe set with durable non-stick coating and Thermo-Spot heat indicators.', image: 'https://images.unsplash.com/photo-1584269600464-37b1b58a9fe7?w=600&auto=format&fit=crop&q=80' },
            { id: 6, name: 'Ergonomic Office Chair', sku: 'STE-EOC-GRY', category: 'Office', price: 299, stock: 0, supplier: 'Steelcase Distributor', description: 'Fully adjustable lumbar support, 3D armrests, and high-density mesh back for complete comfort.', image: 'https://images.unsplash.com/photo-1580481072645-022f9a6dbf27?w=600&auto=format&fit=crop&q=80' },
            { id: 7, name: 'Wireless Charging Pad', sku: 'BEL-WCP-15W', category: 'Electronics', price: 39, stock: 35, supplier: 'Belkin Sourcing', description: '15W fast wireless charging for Qi-enabled smartphones, complete with dual-color LED indicators.', image: 'https://images.unsplash.com/photo-1591290619618-904f6dd935e3?q=80&w=870&auto=format&fit=crop' },
            { id: 8, name: 'Running Sneakers Alpha', sku: 'NKE-RSA-10', category: 'Apparel', price: 110, stock: 4, supplier: 'Nike Logistics Hub', description: 'Breathable mesh upper with high-rebound cushioning foam to maximize comfort during runs.', image: 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&auto=format&fit=crop&q=80' },
            { id: 9, name: 'Dell UltraSharp 32" Monitor', sku: 'DELL-U32-4K', category: 'Electronics', price: 899, stock: 12, supplier: 'Dell Commercial Dist.', description: '4K USB-C hub monitor featuring outstanding color coverage, built-in dual speakers, and height adjustability.', image: 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=600&auto=format&fit=crop&q=80' },
            { id: 10, name: 'Logistics Label Printer', sku: 'ZEB-LP-420D', category: 'Office', price: 249, stock: 20, supplier: 'Zebra Sourcing', description: 'Professional thermal label printer with rapid print speeds, robust desktop shell, and network compatibility.', image: 'https://images.unsplash.com/photo-1616469829581-73993eb86b02?w=600&auto=format&fit=crop&q=80' },
            { id: 11, name: 'Steel Utility Storage Rack', sku: 'STL-USR-5T', category: 'Office', price: 159, stock: 6, supplier: 'Global Industrial Corp', description: 'Heavy-duty 5-tier storage unit with boltless assembly and wire shelving holds up to 4000 lbs total capacity.', image: 'https://images.unsplash.com/photo-1595225476474-87563907a212?w=600&auto=format&fit=crop&q=80' },
            { id: 12, name: 'Premium Leather Boardroom Chair', sku: 'STE-PLB-BLK', category: 'Office', price: 549, stock: 2, supplier: 'Steelcase Distributor', description: 'Premium executive leather chair featuring high-backed support, polished aluminum base, and intuitive tilt controls.', image: 'https://images.unsplash.com/photo-1505797149-43b0069ec26b?w=600&auto=format&fit=crop&q=80' },
            { id: 13, name: 'RFID Handheld Scanner Elite', sku: 'HON-RSE-90', category: 'Electronics', price: 799, stock: 18, supplier: 'Honeywell Logistics', description: 'Enterprise-grade smart handheld scanner featuring rapid RFID decoding, custom trigger controls, and drop-proof chassis.', image: 'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=600&auto=format&fit=crop&q=80' },
            { id: 14, name: 'Automated Barcode Terminal', sku: 'ZEB-ABT-77', category: 'Office', price: 1150, stock: 5, supplier: 'Zebra Sourcing', description: 'Fixed industrial barcode scanning terminal with multi-camera alignment and automated inventory database dispatch.', image: 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=600&auto=format&fit=crop&q=80' },
            { id: 15, name: 'Samsung Galaxy S25 Ultra', sku: 'SMS25U-512-BLK', category: 'Electronics', price: 1399, stock: 10, supplier: 'Samsung Distribution', description: 'Flagship Android smartphone with AI-powered camera system, Snapdragon chipset, and ultra-bright AMOLED display.', image: 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=600&auto=format&fit=crop&q=80' },
            { id: 16, name: 'Gaming Mechanical Keyboard', sku: 'LOG-GMK-RGB', category: 'Electronics', price: 129, stock: 22, supplier: 'Logitech Gaming', description: 'RGB mechanical gaming keyboard with low-latency switches and programmable macro controls.', image: 'https://images.unsplash.com/photo-1511467687858-23d96c32e4ae?w=600&auto=format&fit=crop&q=80' },
            { id: 17, name: '4K Action Camera Pro', sku: 'GPR-4K-AC', category: 'Electronics', price: 449, stock: 9, supplier: 'GoPro Supply Chain', description: 'Waterproof 4K action camera with stabilization, voice control, and ultra-wide capture lens.', image: 'https://images.unsplash.com/photo-1772650713936-c0a588419086?q=80&w=1169&auto=format&fit=crop' },
            { id: 18, name: 'Smart Fitness Watch X', sku: 'FIT-WTX-BLK', category: 'Electronics', price: 199, stock: 27, supplier: 'FitTech Industries', description: 'Advanced health tracking smartwatch with AMOLED screen, heart-rate monitoring, and GPS support.', image: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&auto=format&fit=crop&q=80' },
            { id: 19, name: 'Portable Bluetooth Speaker', sku: 'JBL-PBS-RED', category: 'Electronics', price: 89, stock: 40, supplier: 'JBL Audio Hub', description: 'Compact waterproof Bluetooth speaker delivering deep bass and 12 hours of battery life.', image: 'https://images.unsplash.com/photo-1589003077984-894e133dabab?w=600&auto=format&fit=crop&q=80' },
            { id: 20, name: 'Minimalist Wooden Desk', sku: 'IKEA-MWD-OAK', category: 'Office', price: 279, stock: 11, supplier: 'IKEA Workspace', description: 'Modern oak-finish office desk with cable management and spacious workstation layout.', image: 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=600&auto=format&fit=crop&q=80' },
            { id: 21, name: 'Adjustable Laptop Stand', sku: 'NEX-ALS-SLV', category: 'Office', price: 59, stock: 33, supplier: 'NextGen Office Gear', description: 'Foldable aluminum laptop stand with ergonomic viewing angles and anti-slip padding.', image: 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&auto=format&fit=crop&q=80' },
            { id: 22, name: 'Ceramic Dinner Set', sku: 'HNK-CDS-16', category: 'Home & Kitchen', price: 149, stock: 19, supplier: 'HomeNest Kitchenware', description: '16-piece premium ceramic dinnerware set featuring scratch-resistant glossy finish.', image: 'https://images.unsplash.com/photo-1603190287605-e6ade32fa852?w=600&auto=format&fit=crop&q=80' },
            { id: 23, name: 'Air Fryer XL', sku: 'PHL-AFXL-7L', category: 'Home & Kitchen', price: 199, stock: 16, supplier: 'Philips Appliances', description: '7-liter digital air fryer with rapid air technology and preset cooking programs.', image: 'https://images.unsplash.com/photo-1585238342024-78d387f4a707?w=600&auto=format&fit=crop&q=80' },
            { id: 24, name: 'Luxury Bedding Set', sku: 'LUX-BDS-KNG', category: 'Home & Kitchen', price: 179, stock: 13, supplier: 'Luxury Linen Co.', description: 'Soft breathable king-size bedding set with premium microfiber fabric and elegant stitching.', image: 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=600&auto=format&fit=crop&q=80' },
            { id: 25, name: "Men's Casual Hoodie", sku: 'HNM-MCH-GRY', category: 'Apparel', price: 65, stock: 44, supplier: 'H&M Distribution', description: 'Comfortable fleece-lined hoodie with relaxed fit and adjustable drawstring hood.', image: 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=600&auto=format&fit=crop&q=80' },
            { id: 26, name: "Women's Running Jacket", sku: 'ADS-WRJ-BLU', category: 'Apparel', price: 95, stock: 21, supplier: 'Adidas Global', description: 'Lightweight weather-resistant running jacket with breathable mesh ventilation panels.', image: 'https://images.unsplash.com/photo-1529139574466-a303027c1d8b?w=600&auto=format&fit=crop&q=80' },
            { id: 27, name: 'Travel Backpack Explorer', sku: 'TRV-BPE-45L', category: 'Apparel', price: 139, stock: 17, supplier: 'Urban Travel Gear', description: '45L water-resistant backpack with laptop sleeve, USB charging port, and anti-theft pocket.', image: 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?w=600&auto=format&fit=crop&q=80' },
            { id: 28, name: 'Professional DSLR Camera', sku: 'CAN-DSLR-90D', category: 'Electronics', price: 1599, stock: 7, supplier: 'Canon Imaging', description: 'High-resolution DSLR camera with fast autofocus, 4K video recording, and interchangeable lenses.', image: 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=600&auto=format&fit=crop&q=80' },
            { id: 29, name: 'Noise Cancelling Earbuds', sku: 'BOSE-NCE-WHT', category: 'Electronics', price: 279, stock: 26, supplier: 'BOSE Distribution', description: 'Wireless earbuds featuring immersive sound, adaptive noise cancellation, and long battery life.', image: 'https://images.unsplash.com/photo-1606229365485-93a3b8ee0385?w=600&auto=format&fit=crop&q=80' },
            { id: 30, name: 'Industrial Storage Cabinet', sku: 'IND-ISC-MTL', category: 'Office', price: 699, stock: 8, supplier: 'Industrial Space Co.', description: 'Heavy-duty lockable steel storage cabinet with reinforced shelving for warehouse organization.', image: 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=600&auto=format&fit=crop&q=80' },
            { id: 31, name: 'Electric Kettle Pro', sku: 'PHI-EKP-17', category: 'Home & Kitchen', price: 129, stock: 1, supplier: 'Philips Appliances', description: '1.7L quick-boiling electric kettle with automatic shut-off and overheat protection.', image: 'https://images.unsplash.com/photo-1738520420652-0c47cea3922b?q=80&w=1171&auto=format&fit=crop' }
        ],
        get filtered() {
            return this.products
                .filter(p => {
                    const searchLower = this.search.toLowerCase();
                    const matchSearch = p.name.toLowerCase().includes(searchLower) || 
                                        p.sku.toLowerCase().includes(searchLower) ||
                                        p.supplier.toLowerCase().includes(searchLower);
                    const matchCategory = this.category === 'All' || p.category === this.category;
                    const statusVal = p.stock === 0 ? 'Out of Stock' : (p.stock <= 4 ? 'Low Stock' : 'In Stock');
                    const matchStatus = this.status === 'All' || statusVal === this.status;
                    return matchSearch && matchCategory && matchStatus;
                })
                .sort((a, b) => {
                    if (this.sortBy === 'price') return a.price - b.price;
                    return a.name.localeCompare(b.name);
                });
        }
    };
}
</script>
@endsection
