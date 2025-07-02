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
            <!-- <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg collapse-btn"> 
              <i class="fas fa-align-justify"></i></a></li>
            <li><a href="#" class="nav-link nav-link-lg fullscreen-btn">
                <i class="fas fa-expand"></i>
              </a></li> -->
          </ul>
        </div>
      </nav>
      
      <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand text-center py-3">
            <a href="{{ route('admin.dashboard') }}">
              <img src="/assets/logo/logo1.jpg" alt="Logo" class="header-logo" style="width:120px; height: auto;" />
              <div class="logo-name mt-2" style="font-weight: bold; font-size: 18px; color: #333;">CE-Repair Admin</div>
            </a>
          </div>
          
          <ul class="sidebar-menu mt-4">
            <li class="menu-header">Navigations</li>

            <li class="dropdown {{ request()->is('admin/dashboard') ? 'active' : '' }}">
              <a href="{{ route('admin.dashboard') }}" class="nav-link">
                <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
              </a>
            </li>

            <li class="dropdown {{ request()->is('admin/laptop-repair*') ? 'active' : '' }}">
              <a href="{{ route('admin.laptop-repair.index') }}" class="nav-link">
                <i class="fas fa-laptop-medical"></i><span>Laptop Repair</span>
              </a>
            </li>

            <li class="dropdown {{ request()->is('admin/complete-repair*') ? 'active' : '' }}">
              <a href="{{ route('admin.complete-repair.index') }}" class="nav-link">
                <i class="fas fa-check-circle"></i><span>Completed Repair</span>
              </a>
            </li>

            <li class="dropdown {{ request()->is('admin/stock*') ? 'active' : '' }}">
              <a href="{{ route('admin.stock.index') }}" class="nav-link">
                <i class="fas fa-boxes"></i><span>Stock</span>
              </a>
            </li>

            <li class="dropdown {{ request()->is('admin/invoices*') ? 'active' : '' }}">
              <a href="{{ route('admin.invoices.index') }}" class="nav-link">
                <i class="fas fa-file-invoice-dollar"></i><span>Invoice</span>
              </a>
            </li>

            <li class="dropdown {{ request()->is('admin/shop*') ? 'active' : '' }}">
              <a href="{{ route('admin.shop.index') }}" class="nav-link">
                <i class="fas fa-store"></i><span>Shop Details</span>
              </a>
            </li>

            <li class="dropdown {{ request()->is('repair-tracking*') ? 'active' : '' }}">
              <a href="{{ route('web.repair-tracking.index') }}" class="nav-link">
                <i class="fas fa-search-location"></i><span>Laptop Tracking</span>
              </a>
            </li>

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