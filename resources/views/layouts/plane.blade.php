<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
	<meta charset="utf-8"/>
	<title>SB Admin v2.0 in Laravel 5</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1" name="viewport"/>
	<meta content="" name="description"/>
	<meta content="" name="author"/>

	<!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link href="{{ asset('css/chosen.css') }}" rel="stylesheet">

    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    
    <!-- <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" >

    <link href="{{ asset('css/AdminLTE.css') }}" rel="stylesheet" >

    <link href="{{ asset('css/skins/skin-blue.min.css') }}" rel="stylesheet" > -->

    <link href="{{ asset('css/fontawesome.css') }}" rel="stylesheet" >
</head>
<body>
	@yield('body')
	<script src="{{ asset("js/frontend.js") }}" type="text/javascript"></script>
</body>
</html>