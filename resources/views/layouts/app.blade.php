<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>AMS - ERP</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/adminltev3.css" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet" />
    
    <style>
        #loader {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        background: rgba(0,0,0,0.75) url("{{ asset('img/spinner.gif') }}") no-repeat center center;
        z-index: 10000;
      }

      .center {
        display: block;
        margin-left: auto;
        margin-right: auto;
      }

      #capsWarning { color:red;}
    </style>
</head>

<body class="header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden login-page">
<div id="loader"></div>
    @yield('content')
</body>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<!--<script src="{{ asset('js/jquery.capslockstate.js') }}"></script>-->
<script>
$(document).ready(function(){
    $('#btnLogin').click(function(){
        var spinner = $('#loader');
        spinner.show();
    });
    
    /*$(window).capslockstate();

    $(window).bind("capsOn", function(event) {
    if ($("#password:focus").length > 0) {
        $("#capsWarning").show();
    }
    });

    $(window).bind("capsOff capsUnknown", function(event) {
    $("#capsWarning").hide();
    });

    $("#password").bind("focusout", function(event) {
    $("#capsWarning").hide();
    });

    $("#password").bind("focusin", function(event) {
    if ($(window).capslockstate("state") === true) {
        $("#capsWarning").show();
    }
    });*/
});
</script>
</html>
