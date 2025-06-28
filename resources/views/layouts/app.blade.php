<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Micro Channeling Center</title>
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
    <div ></div>
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
              <!-- <form class="form-inline mr-auto">
                <div class="search-element">
                  <input class="form-control" type="search" placeholder="Search" aria-label="Search" data-width="200">
                  <button class="btn" type="submit">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </form> -->
            </li>
          </ul>
        </div>
        
      </nav>
      <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href=''> <img alt="image" src="" class="header-logo" /> <span
                class="logo-name">Micro</span>
            </a>
          </div>
          <ul class="sidebar-menu">
              <li class="menu-header">Main</li>

              <li class="dropdown {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                  <a href="{{ route('admin.dashboard') }}" class="nav-link">
                      <i data-feather="monitor"></i><span>Dashboard</span>
                  </a>
              </li>

              <li class="dropdown {{ request()->is('admin/laptop-repair*') ? 'active' : '' }}">
                  <a href="{{ route('admin.laptop-repair.index') }}" class="nav-link">
                      <i data-feather="monitor"></i><span>Laptop Repair</span>
                  </a>
              </li>

              <li class="dropdown {{ request()->is('admin/complete-repair*') ? 'active' : '' }}">
                  <a href="{{ route('admin.complete-repair.index') }}" class="nav-link">
                      <i data-feather="monitor"></i><span>Completed Repair</span>
                  </a>
              </li>



              


              <li class="dropdown">
                <a href="{{ route('logout') }}" class="nav-link mt-5"
                  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i data-feather="log-out"></i><span>Logout</span>
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
      
      <!-- Your existing footer -->
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