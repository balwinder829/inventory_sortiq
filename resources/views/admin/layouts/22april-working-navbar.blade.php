@if(auth()->check())
          
            <!-- Dashboard -->
            @if(auth()->check() && !auth()->user()->is_superadmin)
            <ul class="menu-inner py-1">
              <li class="menu-item {{ request()->routeIs('user.store') ? 'active' : '' }}">
            <!-- <li class="menu-item active"> -->
              
              <a href="{{ route('user.store') }}" class="menu-link ">
                <i class="menu-icon tf-icons bx bx-store"></i>
                <div data-i18n="Extended UI">My Store</div>
              </a>
            </li>

             <li class="menu-item {{ request()->routeIs('invoices*') ? 'active' : '' }}">
              <a href="{{ route('invoices.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div data-i18n="Layouts">Invoices</div>
              </a>

            </li>
            <li class="menu-item {{ request()->routeIs('stocks*') ? 'active' : '' }}">
              <a href="{{ route('stocks.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-box"></i>
                <div data-i18n="Layouts">Stock Management</div>
              </a>

            </li>
          </ul>
            @endif
           @if(auth()->check() && auth()->user()->is_superadmin)
           <ul class="menu-inner py-1">
             <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
              <a href="{{ route('admin.dashboard')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
              </a>
            </li>
            <li class="menu-item {{ request()->routeIs('stores*') ? 'active open' : '' }}">
              
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-store"></i>
                <div data-i18n="Extended UI">Store</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('stores.index') ? 'active' : '' }}">
                  <a href="{{ route('stores.index') }}" class="menu-link">
                    <div data-i18n="Perfect Scrollbar">All</div>
                  </a>
                </li>
                <li class="menu-item {{ request()->routeIs('stores.create') ? 'active' : '' }}">
                  <a href="{{ route('stores.create') }}" class="menu-link">
                    <div data-i18n="Text Divider">Create Store</div>
                  </a>
                </li>
              </ul>
            </li>
                        
              <li class="menu-item {{ request()->routeIs('user.list') ? 'active open' : '' }}">
              
              <a href="{{ route('user.list') }}" class="menu-link menu-toggle ">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Extended UI">Users</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('user.list') ? 'active' : '' }}">
                  <a href="{{ route('user.list') }}" class="menu-link">
                    <div data-i18n="Perfect Scrollbar">All</div>
                  </a>
                </li>
                <li class="menu-item {{ request()->routeIs('user.create') ? 'active' : '' }}">
                  <a href="{{ route('user.create') }}" class="menu-link">
                    <div data-i18n="Text Divider">Create User</div>
                  </a>
                </li>
              </ul>
            </li>
           

            <!-- Layouts -->
            <li class="menu-item {{ request()->routeIs('products*') ? 'active' : '' }}">
              <a href="{{ route('products.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-box"></i>
                <div data-i18n="Layouts"> Store Products</div>
              </a>

            </li>

            <li class="menu-item {{ request()->routeIs('wc.product*') ? 'active' : '' }}">
              <a href="{{ route('wc.products') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-box"></i>
                <div data-i18n="Layouts">WC Products</div>
              </a>

            </li>

           <!--  <li class="menu-item {{ request()->routeIs('wc.order*') ? 'active' : '' }}">
              <a href="{{ route('wc.orders') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-receipt"></i>
                <div data-i18n="Layouts"> WooCommerce Orders</div>
              </a>

            </li> -->

            <!-- Layouts -->
            <li class="menu-item {{ request()->routeIs('wc.order*') ? 'active open' : '' }}">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-receipt"></i>
                <div data-i18n="Layouts">Orders</div>
              </a>

              <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('wc.orders') ? 'active' : '' }}">
                  <a href="{{ route('wc.orders') }}" class="menu-link">
                    <div data-i18n="Account">All</div>
                  </a>
                </li>
                <li class="menu-item {{ request()->routeIs('wc.orders.complete') ? 'active' : '' }}">
                  <a href="{{ route('wc.orders.complete') }}" class="menu-link">
                    <div data-i18n="Notifications">Completed</div>
                  </a>
                </li>
                <li class="menu-item {{ request()->routeIs('wc.orders.pending') ? 'active' : '' }}">
                  <a href="{{ route('wc.orders.pending') }}" class="menu-link">
                    <div data-i18n="Connections">Pending</div>
                  </a>
                </li>
                <li class="menu-item {{ request()->routeIs('wc.orders.processing') ? 'active' : '' }}">
                  <a href="{{ route('wc.orders.processing') }}" class="menu-link">
                    <div data-i18n="Connections">Processing</div>
                  </a>
                </li>

               
              </ul>
            </li>

             <li class="menu-item {{ request()->routeIs('stocks*') ? 'active' : '' }}">
              <a href="{{ route('stocks.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-box"></i>
                <div data-i18n="Layouts">Stock Management</div>
              </a>

            </li>

            
            <!-- Payments -->
            <li class="menu-item {{ request()->routeIs('wc.payments*') ? 'active' : '' }}">
              <a href="{{ route('wc.payments') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-credit-card"></i>
                <div data-i18n="Layouts">Payments</div>
              </a>

            </li>
 
             <!-- Customers -->
            <li class="menu-item {{ request()->routeIs('wc.customer*') ? 'active' : '' }}">
              <a href="{{ route('wc.customers') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-group"></i>
                <div data-i18n="Layouts">Customers</div>
              </a>

            </li>

          


             <!-- Categories -->
            <li class="menu-item {{ request()->routeIs('wc.categories*') ? 'active' : '' }}">
              <a href="{{ route('wc.categories') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-category"></i>
                <div data-i18n="Layouts">Categories</div>
              </a>

            </li>

            <li class="menu-item {{ request()->routeIs('invoices*') ? 'active' : '' }}">
              <a href="{{ route('invoices.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div data-i18n="Layouts">Invoices</div>
              </a>

            </li>

            <li class="menu-item {{ request()->routeIs('analytics*') ? 'active' : '' }}">
              <a href="{{ route('analytics.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
                <div data-i18n="Layouts">Invoices Analytics</div>
              </a>

            </li>

            <li class="menu-item {{ request()->routeIs('profit.analysis*') ? 'active' : '' }}">
              <a href="{{ route('profit.analysis') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
                <div data-i18n="Layouts">Profit Analytics</div>
              </a>

            </li>

            <li class="menu-item {{ request()->routeIs('profile*') ? 'active' : '' }}">
              <a href="{{ route('profile.edit') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-circle"></i>
                <div data-i18n="Layouts">Profile</div>
              </a>

            </li>
            <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="menu-icon tf-icons bx bx-power-off"></i>
                <div data-i18n="Logout">Logout</div>
              </a>

              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
              </form>
            </li>
        
            @endif
              @endif