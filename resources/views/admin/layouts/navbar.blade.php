@if(auth()->check())

<ul class="menu-inner py-1">

    {{-- DASHBOARD (permission controlled) --}}
    @can('dashboard.view')
    <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <a href="{{ route('admin.dashboard')}}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-home-circle"></i>
            <div data-i18n="Analytics">Dashboard</div>
        </a>
    </li>
    @endcan

    {{-- MY STORE (manager) --}}
    
    @canany(['my_store.view', 'store_management.view'])
    <li class="menu-item {{ request()->routeIs('user.store') ? 'active' : '' }}">
        <a href="{{ route('user.store') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-store"></i>
            <div data-i18n="Extended UI">My Store</div>
        </a>
    </li>
     @endcanany

    {{-- STORE (admin) --}}
    @canany(['all_store.view','all_store.create'])
    <li class="menu-item {{ request()->routeIs('stores*') ? 'active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-store"></i>
            <div data-i18n="Extended UI">Store</div>
        </a>
        <ul class="menu-sub">
          @can('all_store.view')
            <li class="menu-item {{ request()->routeIs('stores.index') ? 'active' : '' }}">
                <a href="{{ route('stores.index') }}" class="menu-link">
                    <div>All</div>
                </a>
            </li>
            @endcan
            @can('all_store.create')
            <li class="menu-item {{ request()->routeIs('stores.create') ? 'active' : '' }}">
                <a href="{{ route('stores.create') }}" class="menu-link">
                    <div>Create Store</div>
                </a>
            </li>
            @endcan
        </ul>
    </li>
    @endcanany

    {{-- USERS --}}
     @canany(['users.view','users.create'])
    <li class="menu-item {{ request()->routeIs(['user.list','user.create','admin.user.edit']) ? 'active open' : '' }}">
        <a href="{{ route('user.list') }}" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-user"></i>
            <div>Users</div>
        </a>
        <ul class="menu-sub">
           @can('users.view')
            <li class="menu-item {{ request()->routeIs(['user.list','admin.user.edit']) ? 'active' : '' }}">
                <a href="{{ route('user.list') }}" class="menu-link">
                    <div>All</div>
                </a>
            </li>
            @endcan
             @can('users.create')
            <li class="menu-item {{ request()->routeIs('user.create') ? 'active' : '' }}">
                <a href="{{ route('user.create') }}" class="menu-link">
                    <div>Create User</div>
                </a>
            </li>
            @endcan
        </ul>
    </li>
    @endcanany

    {{-- PRODUCTS --}}
    @canany(['products.view', 'store_management.view'])
    <li class="menu-item {{ request()->routeIs('products*') ? 'active' : '' }}">
        <a href="{{ route('products.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-box"></i>
            <div>Store Products</div>
        </a>
    </li>
    @endcanany

    {{-- WC PRODUCTS --}}
    @can('wc_products.view')
    <li class="menu-item {{ request()->routeIs('wc.product*') ? 'active' : '' }}">
        <a href="{{ route('wc.products') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-box"></i>
            <div>WC Products</div>
        </a>
    </li>
    @endcan

    {{-- ORDERS --}}
    @can('wc_orders.view')
    <li class="menu-item {{ request()->routeIs('wc.order*') ? 'active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-receipt"></i>
            <div>Orders</div>
        </a>
        <ul class="menu-sub">
            <li><a href="{{ route('wc.orders') }}" class="menu-link"><div>All</div></a></li>
            <li><a href="{{ route('wc.orders.complete') }}" class="menu-link"><div>Completed</div></a></li>
            <li><a href="{{ route('wc.orders.pending') }}" class="menu-link"><div>Pending</div></a></li>
            <li><a href="{{ route('wc.orders.processing') }}" class="menu-link"><div>Processing</div></a></li>
        </ul>
    </li>
    @endcan

    {{-- STOCK --}}
    @canany(['stock_management.view', 'store_management.view'])
    <li class="menu-item {{ request()->routeIs('stocks*') ? 'active' : '' }}">
        <a href="{{ route('stocks.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-box"></i>
            <div>Stock Management</div>
        </a>
    </li>
    @endcanany

    @canany(['suppliers.view', 'store_management.view'])
    <li class="menu-item {{ request()->routeIs('suppliers*') ? 'active' : '' }}">
      <a href="{{ route('suppliers.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-layout"></i>
        <div data-i18n="Layouts">Suppliers</div>
      </a>
    </li>
    @endcanany
    
    @canany(['purchases.view', 'store_management.view'])
    <li class="menu-item {{ request()->routeIs('purchases*') ? 'active' : '' }}">
      <a href="{{ route('purchases.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-layout"></i>
        <div data-i18n="Layouts">Purchases</div>
      </a>
      @endcanany
    </li>
    {{-- PAYMENTS --}}
    @can('wc_payments.view')
    <li class="menu-item {{ request()->routeIs('wc.payments*') ? 'active' : '' }}">
        <a href="{{ route('wc.payments') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-credit-card"></i>
            <div>Payments</div>
        </a>
    </li>
    @endcan

    {{-- CUSTOMERS --}}
    @can('wc_customers.view')
    <li class="menu-item {{ request()->routeIs('wc.customer*') ? 'active' : '' }}">
        <a href="{{ route('wc.customers') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-group"></i>
            <div>Customers</div>
        </a>
    </li>
    @endcan

    {{-- CATEGORIES --}}
    @can('wc_categories.view')
    <li class="menu-item {{ request()->routeIs('wc.categories*') ? 'active' : '' }}">
        <a href="{{ route('wc.categories') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-category"></i>
            <div>Categories</div>
        </a>
    </li>
    @endcan

    {{-- INVOICES --}}
   @canany(['invoices.view', 'store_management.view'])
    <li class="menu-item {{ request()->routeIs('invoices*') ? 'active' : '' }}">
        <a href="{{ route('invoices.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-file"></i>
            <div>Invoices</div>
        </a>
    </li>
    @endcanany

    {{-- ANALYTICS --}}
    @can('invoices_analytics.view')
    <li class="menu-item {{ request()->routeIs('analytics*') ? 'active' : '' }}">
        <a href="{{ route('analytics.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
            <div>Invoices Analytics</div>
        </a>
    </li>
    @endcan

    {{-- PROFIT --}}
    @can('profit_analytics.view')
    <li class="menu-item {{ request()->routeIs('profit.analysis*') ? 'active' : '' }}">
        <a href="{{ route('profit.analysis') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
            <div>Profit Analytics</div>
        </a>
    </li>
    @endcan

    {{-- REPORTS --}}
    @canany(['invoices.view', 'purchases.view', 'store_management.view'])
    <li class="menu-item {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
        <a href="{{ route('admin.reports.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
            <div>Report Management</div>
        </a>
    </li>
    @endcanany

    {{-- EXPENSES --}}
    @canany(['invoices.view', 'purchases.view', 'store_management.view'])
    <li class="menu-item {{ request()->routeIs('expenses*') ? 'active' : '' }}">
        <a href="{{ route('expenses.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-wallet"></i>
            <div>Expense Management</div>
        </a>
    </li>
    @endcanany

    {{-- RETURNS & REFUNDS --}}
    @canany(['invoices.view', 'purchases.view', 'store_management.view'])
    <li class="menu-item {{ request()->routeIs('returns*') ? 'active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-undo"></i>
            <div>Returns & Refunds</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item {{ request()->routeIs('returns.customer*') ? 'active' : '' }}">
                <a href="{{ route('returns.customer.index') }}" class="menu-link">
                    <div>Customer Returns</div>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('returns.supplier*') ? 'active' : '' }}">
                <a href="{{ route('returns.supplier.index') }}" class="menu-link">
                    <div>Supplier Returns</div>
                </a>
            </li>
        </ul>
    </li>
    @endcanany

    {{-- PROFILE --}}
    @can('profile.view')
    <li class="menu-item {{ request()->routeIs('profile*') ? 'active' : '' }}">
        <a href="{{ route('profile.edit') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user-circle"></i>
            <div>Profile</div>
        </a>
    </li>
    @endcan

    @auth
        @if(auth()->user()->is_superadmin)
        {{-- SEO & WEB PRESENCE --}}
        <li class="menu-item {{ request()->routeIs('admin.seo*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-search-alt"></i>
                <div>SEO & Sitemap</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.seo.index') || request()->routeIs('admin.seo.edit') ? 'active' : '' }}">
                    <a href="{{ route('admin.seo.index') }}" class="menu-link">
                        <div>Pages Meta Tags</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.seo.scripts*') ? 'active' : '' }}">
                    <a href="{{ route('admin.seo.scripts') }}" class="menu-link">
                        <div>Header & Footer Scripts</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.seo.robots*') ? 'active' : '' }}">
                    <a href="{{ route('admin.seo.robots') }}" class="menu-link">
                        <div>Robots.txt Editor</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.seo.sitemap*') ? 'active' : '' }}">
                    <a href="{{ route('admin.seo.sitemap') }}" class="menu-link">
                        <div>XML Sitemap</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ request()->routeIs('permissions*') ? 'active' : '' }}">
            <a href="{{ route('permissions.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-shield-quarter"></i>
                <div>Permissions</div>
            </a>
        </li>
        @endif
    @endauth
    {{-- LOGOUT --}}

    <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="menu-icon tf-icons bx bx-power-off"></i>
            <div>Logout</div>
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
            @csrf
        </form>
    </li>

</ul>

@endif