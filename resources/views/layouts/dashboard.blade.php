@extends('layouts.app')

@section('body')
 
<div class="wrapper">
        <!-- Sidebar  -->
        @include('layouts/sidebar')


            <nav class="navbar bg-dark navbar-fixed-top">
                <button type="button" id="sidebarCollapse" class="navbar-toggler ">
                    <i class="fas fa-bars font-color-white"></i>
                </button>
            </nav>
            
        <!-- Page Content  -->
        <div id="content">
            @yield('content')
        </div>
        <div id="snackbar"></div>
        <div class="overlay"></div>
    </div>
@stop

