
<header class="main-header">
    <!-- Logo -->
    <a href="{{ url('/') }}" class="logo">{{ config('app.name', 'Laravel') }}</a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      
     
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          
            @if (Auth::guest())
                <li><a href="{{ route('login') }}">Login</a></li>
                <!-- <li><a href="{{ route('register') }}">Register</a></li> -->
            @else
                <!-- Left Side Of Navbar -->
                @role('Admin')
                    @if (Request::is('adminPages/*'))
                        <li><a href="{{ url('adminPages/viewGroup') }}">Employee List</a></li>
                    @endif   
                @endrole
                <li class="dropdown user user-menu"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                <i class="fa fa-user fa-fw"></i> {{ Auth::user()->name }}  <i class="fa fa-caret-down"></i>
                </a>
            @endif
            <ul class="dropdown-menu">
                
                <!-- Menu Footer-->
                <li class='user-footer'>
                    @role('Admin') {{-- Laravel-permission blade helper --}}
                    <a href="{{ route('users.index') }}"><i class="fa fa-btn fa-unlock"></i> Admin</a>
                    <hr/>
                    @endrole
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa fa-sign-out fa-fw"></i> Logout </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>

 



  