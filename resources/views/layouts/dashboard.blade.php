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
            @if(Session::has('flash_message'))
                <div class="container">      
                    <div class="alert alert-success"><em> {!! session('flash_message') !!}</em>
                    </div>
                </div>
            @endif 

             @if(Session::has('error_message'))
                <div class="container">      
                    <div class="alert alert-danger"><em> {!! session('error_message') !!}</em>
                    </div>
                </div>
            @endif 

            <div class="row justify-content-center">
                <div class="col-md-8 col-md-offset-2">              
                    @include ('errors.list') {{-- Including error file --}}
                </div>
            </div>
            @yield('content')
        </div>
        <div id="snackbar"></div>
        <div class="overlay"></div>
    </div>
@stop

