<?php use Illuminate\Support\Facades\Route; ?>
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<!-- BEGIN HEAD -->
<head>
    <meta charset="UTF-8">
    <title>{{$AppName }}</title>

    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
   
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    @if(Route::getFacadeRoot()->current()->uri() == 'orders') 
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    @else <link rel="stylesheet" type="text/css" href="{{ asset('libs/bootstrap/css/bootstrap.min.css') }}">
    @endif
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/fonts/line-awesome/css/line-awesome.min.css') }}">
    <!--<link rel="stylesheet" type="text/css" href="assets/fonts/open-sans/styles.css">-->

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/fonts/montserrat/styles.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('libs/tether/css/tether.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('libs/jscrollpane/jquery.jscrollpane.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('libs/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/styles/common.min.css') }}">
     <link rel="stylesheet" type="text/css" href="{{ asset('assets/styles/widgets/payment.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/fonts/kosmo/styles.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/styles/widgets/panels.min.css') }}">
    <!-- END GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" type="text/css" href="{{ asset('libs/select2/css/select2.min.css') }}"> <!-- Original -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/styles/libs/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('libs/fancybox/jquery.fancybox.css') }}">
    <!-- Customization -->

    <!-- BEGIN THEME STYLES -->
    @guest
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/styles/pages/auth.min.css') }}">
    @else
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/styles/themes/primary.min.css') }}">

    @endguest

    <link class="ks-sidebar-dark-style" rel="stylesheet" type="text/css"
                  href="{{ asset('assets/styles/themes/sidebar-black.min.css') }}">
            <!-- END THEME STYLES -->
            @stack('customcss')
            <script>
                window.Laravel = { csrfToken: '{{ csrf_token() }}' };
            </script>
            @if(!auth()->guest())
                <script>
                    window.Laravel.userId = <?php echo auth()->user()->id; ?>
                </script>
            @endif
        <script>
            //request user permission for notification and messaging
            window.Notification.requestPermission();
        </script>

            <style>
                .required:after { content:" *"; color: #f31e1e;  font-weight: bold;  font-size: 16px;
                    position: absolute;}
            </style>
</head>

<!-- END HEAD -->
