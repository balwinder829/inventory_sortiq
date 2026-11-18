<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ asset('assets/') }} "
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Dashboard -</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets/js/config.js') }}"></script>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          
          <div class="app-brand demo">
    <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
      <img src="{{ asset('assets/img/sandal-logo.png') }}" alt="Logo" style="height: 40px;" />
      <!-- You can also add a text next to the logo if you want -->
      <!-- <span class="app-brand-text demo menu-text fw-bolder ms-2">MyApp</span> -->
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>

          <div class="menu-inner-shadow"></div>
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

                <!-- <li class="menu-item">
                  <a href="pages-account-settings-connections.html" class="menu-link">
                    <div data-i18n="Connections">Due</div>
                  </a>
                </li> -->
              </ul>
            </li>

             <li class="menu-item {{ request()->routeIs('stocks*') ? 'active' : '' }}">
              <a href="{{ route('stocks.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-box"></i>
                <div data-i18n="Layouts">Stock Management</div>
              </a>

            </li>

             <!-- Purchase -->
           <!--  <li class="menu-item  {{ request()->routeIs('purchases*') ? 'active open' : '' }}">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div data-i18n="Layouts">Purchases</div>
              </a>

              <ul class="menu-sub">
                <li class="menu-item  {{ request()->routeIs('purchases.index') ? 'active' : '' }}">
                  <a href="{{ route('purchases.index') }}" class="menu-link">
                    <div data-i18n="Account">All</div>
                  </a>
                </li>
                <li class="menu-item {{ request()->routeIs('purchases.approvedPurchases') ? 'active' : '' }}">
                  <a href="{{ route('purchases.approvedPurchases') }}" class="menu-link">
                    <div data-i18n="Notifications">Approval</div>
                  </a>
                </li>
              </ul>
            </li>
 -->

             <!-- Quotation -->
          <!--   <li class="menu-item {{ request()->routeIs('quotations*') ? 'active' : '' }}">
              <a href="{{ route('quotations.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div data-i18n="Layouts">Quotation</div>
              </a>

            </li> -->

             <!-- Suppliers -->
            <!-- <li class="menu-item {{ request()->routeIs('suppliers*') ? 'active' : '' }}">
              <a href="{{ route('suppliers.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div data-i18n="Layouts">Suppliers</div>
              </a>

            </li> -->

            <!-- Payments -->
            <li class="menu-item {{ request()->routeIs('wc.payments*') ? 'active' : '' }}">
              <a href="{{ route('wc.payments') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-credit-card"></i>
                <div data-i18n="Layouts">Payments</div>
              </a>

            </li>

            <!-- Payments -->
            <!-- <li class="menu-item {{ request()->routeIs('sales*') ? 'active' : '' }}">
              <a href="{{ route('sales.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div data-i18n="Layouts">Sales</div>
              </a>

            </li> -->

             <!-- Customers -->
            <li class="menu-item {{ request()->routeIs('wc.customer*') ? 'active' : '' }}">
              <a href="{{ route('wc.customers') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-group"></i>
                <div data-i18n="Layouts">Customers</div>
              </a>

            </li>

             <!-- Users -->
            <!-- <li class="menu-item">
              <a href="{{ route('quotations.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div data-i18n="Layouts">Users</div>
              </a>

            </li> -->


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

             <!-- Units -->
            <!-- <li class="menu-item {{ request()->routeIs('units*') ? 'active' : '' }}">
              <a href="{{ route('units.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div data-i18n="Layouts">Units</div>
              </a>

            </li> -->
            <li class="menu-item">
  <a href="javascript:void(0);" class="menu-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
    <i class="menu-icon tf-icons bx bx-power-off"></i>
    <div data-i18n="Logout">Logout</div>
  </a>

  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
  </form>
</li>

            <!--   <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div data-i18n="Layouts">Logout</div>
              </a>

            </li> -->

            
            
            
        
            @endif
              
              

              @endif
        
           
        </aside>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar"
          >
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              <!-- Search -->
              <div class="navbar-nav align-items-center">
                <div class="nav-item d-flex align-items-center">
                  <i class="bx bx-search fs-4 lh-0"></i>
                  <input
                    type="text"
                    class="form-control border-0 shadow-none"
                    placeholder="Search..."
                    aria-label="Search..."
                  />
                </div>
              </div>
              <!-- /Search -->

              <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- Place this tag where you want the button to render. -->
              <!--   <li class="nav-item lh-1 me-3">
                  <a
                    class="github-button"
                    href="https://github.com/themeselection/sneat-html-admin-template-free"
                    data-icon="octicon-star"
                    data-size="large"
                    data-show-count="true"
                    aria-label="Star themeselection/sneat-html-admin-template-free on GitHub"
                    >Star</a
                  >
                </li> -->

                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <!-- <div class="avatar avatar-online">
                      <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                    </div> -->
                    <div class="avatar avatar-online">
                      <img 
                        src="{{ Auth::check() && Auth::user()->profile_image ? asset(Auth::user()->profile_image) : asset('assets/img/default_avatar.png') }}" 
                        alt="Profile Picture" 
                        class="w-px-40 h-auto rounded-circle" 
                      />
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="#">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
                              <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <span class="fw-semibold d-block">{{ Auth::check() ?  Auth::user()->name : 'Guest'}}</span>
                            <small class="text-muted">{{ Auth::check() ? (Auth::user()->is_superadmin ? 'Super Admin' : 'User') : 'Guest' }}</small>
                          </div>
                        </div>
                      </a>
                    </li>
                    <!-- <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        <i class="bx bx-user me-2"></i>
                        <span class="align-middle">My Profile</span>
                      </a>
                    </li>
                    -->
                    <!-- <li>
                      <a class="dropdown-item" href="#">
                        <i class="bx bx-cog me-2"></i>
                        <span class="align-middle">Settings</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        <span class="d-flex align-items-center align-middle">
                          <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
                          <span class="flex-grow-1 align-middle">Billing</span>
                          <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
                        </span>
                      </a>
                    </li> -->
                   <!--  <li>
                      <div class="dropdown-divider"></div>
                    </li> -->
                   <!--  <li>
                      <a class="dropdown-item" href="javascript:void();" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bx bx-power-off me-2"></i>
                        <span class="align-middle">Log Out</span>
                      </a>

                      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                      </form>
                    </li> -->
                  </ul>
                </li>
                <!--/ User -->
              </ul>
            </div>
          </nav>
@if(session('low_stock_alert'))
<div id="low-stock-alert" 
     class="position-fixed top-0 end-0 m-4" 
     style="z-index: 2000; width: 360px;">
  <div class="card border-warning shadow-lg">
    <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
      <h5 class="mb-0"><i class="bx bx-error-circle me-2"></i>Low Stock Alert</h5>
      <button type="button" class="btn-close" aria-label="Close" onclick="dismissLowStock()"></button>
    </div>
    <div class="card-body bg-light text-dark">
      <p class="mb-2">
        {{ session('low_stock_alert')['count'] }} product(s) are running low on stock:
      </p>
      <ul class="ps-3 mb-3">
        @foreach(session('low_stock_alert')['products'] as $p)
          <li>{{ $p->name }} ({{ $p->quantity }} left)</li>
        @endforeach
      </ul>
      <div class="d-flex justify-content-end gap-2">
        <button class="btn btn-sm btn-secondary" onclick="dismissLowStock()">Dismiss</button>
        <a href="{{ route('admin.low_stock.index') }}" class="btn btn-sm btn-warning text-white">View</a>
      </div>
    </div>
  </div>
</div>

<script>
function dismissLowStock() {
  fetch('{{ route('admin.low_stock.dismiss') }}', {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': '{{ csrf_token() }}',
      'Accept': 'application/json'
    }
  }).then(() => {
    const alertBox = document.getElementById('low-stock-alert');
    alertBox.classList.add('animate__animated', 'animate__fadeOutRight');
    setTimeout(() => alertBox.remove(), 300);
  });
}
</script>
@endif

          <!-- / Navbar -->