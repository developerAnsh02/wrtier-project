<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Writer Admin</title>
    @include('layouts.css')
    @livewireStyles
  </head>

<body class="skin-default-dark fixed-layout">
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">Elite admin</p>
        </div>
    </div>
    <div id="main-wrapper">
      @include('layouts.header')
      
      @include('layouts.aside')
     
        
       
        <div class="page-wrapper">
            @yield('content')
        </div>
        <footer class="footer">
            © 2024  
            <a href="https://www.warrgyizmorsch.com/">warrgyizmorsch</a>
        </footer>
    </div>
    @include('layouts.js')
    @livewireScripts
</body>

</html>