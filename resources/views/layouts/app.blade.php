<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>CE Laptop Repair</title>
  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">
  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
  <!-- Custom style CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
  <!-- Font Awesome 6 CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar sticky">
        <div class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg
									collapse-btn"> <i data-feather="align-justify"></i></a></li>
            <li><a href="#" class="nav-link nav-link-lg fullscreen-btn">
                <i data-feather="maximize"></i>
              </a></li>
            <li>
              
            </li>
          </ul>
        </div>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown" class="nav-link nav-link-lg message-toggle d-flex align-items-center">
              <i class="fas fa-user-circle" style="font-size: 35px; color:rgb(0, 0, 0);"></i>
              <span class="ml-2 d-none d-md-inline text-dark font-weight-bold" style="font-size: 16px;">
                {{ auth()->user()->name ?? 'User' }}
              </span>
            </a>

            <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
              <div class="dropdown-header">
               Profile
                <div class="float-right">
            
              </div>
              <div class="dropdown-list-content ">
                 <div class="dropdown-item text-center">
                    <!-- Profile Image -->
                     @php
                      $user = auth()->user();
                      $shopDetail = $user ? App\Models\MyShopDetail::where('user_id', $user->id)->first() : null;
                  @endphp
                            @if($shopDetail && $shopDetail->logo_image)
                      <img src="{{ Storage::url($shopDetail->logo_image) }}" alt="User Image" class="rounded-circle mt-2" width="60" height="60">
                    @else
                      <img src="{{ asset('/assets/img/user.png') }}" alt="Default User" class="rounded-circle mt-2" width="60" height="60">
                    @endif

                    <!-- User Greeting -->
                    <span class="message-user d-block font-weight-bold mt-2">
                      Hello, {{ auth()->user()->name ?? 'User' }}
                    </span>

                    <!-- Logout Button -->
                    <form action="{{ route('logout') }}" method="POST" class="mt-2 mb-2">
                      @csrf
                      <button type="submit" class="btn btn-danger btn-sm w-100">
                        <i class="fas fa-sign-out-alt mr-1"></i> Logout
                      </button>
                    </form>
                  </div>

              </div>
   
          </li>
          
          
        </ul>
      </nav>

    <div class="main-sidebar sidebar-style-2">
      <aside id="sidebar-wrapper">
        <div class="sidebar-brand text-center py-3">
          @php
              $user = auth()->user();
              $shopDetail = $user ? App\Models\MyShopDetail::where('user_id', $user->id)->first() : null;
          @endphp

          <a href="{{ route('user.dashboard') }}">
              @if($shopDetail && $shopDetail->logo_image)
                  <img src="{{ Storage::url($shopDetail->logo_image) }}" alt="Logo" class="header-logo" style="width:65px; height: auto;">
              @else
                  <img src="{{ asset('/assets/logo/logo.png') }}" alt="Logo" class="header-logo" style="width:120px; height: auto;">
              @endif
              
              <div class="logo-name mt-2" style="font-weight: bold; font-size: 12px; color: #333;">
                  {{ $shopDetail->shop_name ?? 'User ID: ' . ($user->id ?? 'CeylonGIT') }}
              </div>
          
          </a>
        </div>

          
          <ul class="sidebar-menu mt-4">
            <li class="menu-header">Navigations</li>

            <li class="dropdown {{ request()->is('user/dashboard') ? 'active' : '' }}">
              <a href="{{ route('user.dashboard') }}" class="nav-link">
                <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
              </a>
            </li>
            <li class="dropdown {{ request()->is('user/myshop*') ? 'active' : '' }}">
              <a href="{{ route('user.myshop.index') }}" class="nav-link">
                <i class="fas fa-home"></i><span>My Shop Details</span>
              </a>
            </li>

            <li class="dropdown {{ request()->is('user/cashier*') ? 'active' : '' }}">
              <a href="{{ route('user.cashier.index') }}" class="nav-link">
              <i class="fas fa-cash-register"></i><span>Cashier</span>
              </a>
            </li>

           <li class="dropdown {{ request()->is('user/invoices') || request()->is('user/invoices/*') ? 'active' : '' }}">
              <a href="{{ route('user.invoices.index') }}" class="nav-link">
                  <i class="fas fa-file-invoice-dollar"></i><span>Invoice</span>
              </a>
          </li>

          <li class="dropdown {{ request()->is('user/invoices-with-stock') || request()->is('user/invoices-with-stock/*') ? 'active' : '' }}">
              <a href="{{ route('user.invoices_with_stock.index') }}" class="nav-link">
                  <i class="fas fa-clipboard-list"></i><span>Invoice With Stock</span>
              </a>
          </li>

          <li class="dropdown {{ request()->is('user/total-amount*') ? 'active' : '' }}">
              <a href="{{ route('user.total_amount.index') }}" class="nav-link">
                <i class="fas fa-chart-line"></i><span>Invoice Report</span>
              </a>
            </li>


            <li class="dropdown {{ request()->is('user/laptop-repair*') ? 'active' : '' }}">
              <a href="{{ route('user.laptop-repair.index') }}" class="nav-link">
                <i class="fas fa-laptop-medical"></i><span>Laptop Repair</span>
              </a>
            </li>

            <li class="dropdown {{ request()->is('user/complete-repair*') ? 'active' : '' }}">
              <a href="{{ route('user.complete-repair.index') }}" class="nav-link">
                <i class="fas fa-check-circle"></i><span>Completed Repair</span>
              </a>
            </li>

            <li class="dropdown {{ request()->is('user/stock*') ? 'active' : '' }}">
              <a href="{{ route('user.stock.index') }}" class="nav-link">
                <i class="fas fa-boxes"></i><span>Stock</span>
              </a>
            </li>

            <hr class="my-1" style="height: 2px; background-color: black; border: none;">


            <li class="dropdown {{ request()->is('user/shop_names*') ? 'active' : '' }}">
              <a href="{{ route('user.shop_names.index') }}" class="nav-link">
                <i class="fas fa-store"></i><span>Shop Repair Details</span>
              </a>
            </li>

            <li class="dropdown {{ request()->is('user/shop_completed_repair*') ? 'active' : '' }}">
              <a href="{{ route('user.shop_completed_repair.index') }}" class="nav-link">
                <i class="fas fa-check-double"></i><span>Completed Shop Repairs</span>
              </a>

            

            <li class="dropdown {{ request()->is('user/user/email-settings*') ? 'active' : '' }}">
              <a href="{{ route('user.email-settings.index') }}" class="nav-link">
                <i class="fas fa-envelope"></i></i><span>Email Setting</span>
              </a>
            </li>

            <!-- <li class="dropdown {{ request()->is('repair-tracking*') ? 'active' : '' }}">
              <a href="{{ route('web.repair-tracking.index') }}" class="nav-link">
                <i class="fas fa-search-location"></i><span>Laptop Tracking</span>
              </a>
            </li> -->

            <li class="dropdown">
              <a href="{{ route('logout') }}" class="nav-link mt-5"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i><span>Logout</span>
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
              </form>
            </li>
          </ul>
        </aside>
      </div>
      
      <!-- Main Content -->
      <div class="main-content">
        @yield('content')
      </div>
      
      <footer class="main-footer">
        <div class="footer-left">
          <a href="https://ceylongit.online/" target="_blank">Powered by CeylonGIT</a>
        </div>
        <div class="footer-right">
          <!-- You can add extra footer content here -->
        </div>
      </footer>
    </div>
  </div>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
  <!-- Bootstrap 4 JS -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

  <!-- General JS Scripts -->
  <script src="{{ asset('assets/js/app.min.js') }}"></script>
  <!-- JS Libraies -->
  <script src="{{ asset('assets/bundles/apexcharts/apexcharts.min.js') }}"></script>
  <!-- Page Specific JS File -->
  <script src="{{ asset('assets/js/page/index.js') }}"></script>
  <!-- Template JS File -->
  <script src="{{ asset('assets/js/scripts.js') }}"></script>
  <!-- Custom JS File -->
  <script src="{{ asset('assets/js/custom.js') }}"></script>

  
  
  @stack('scripts')
</body>
</html>