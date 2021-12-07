<!DOCTYPE html>
<html lang="jp">
    <head>
        <meta charset="utf-8">
        <meta name ="viewport" content = "width=device-width, minimum-scale=1.0, maximum-scale = 1.0, user-scalable = no">
        <link rel="shortcut icon" href="{{ asset('images/common/favicon.ico') }}" type="image/x-icon">     
        <title>BORDERLESS WORKING ボーダレスワーキング</title>
        <!-- Fonts -->      
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/font.css') }}" rel="stylesheet">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">    
    </head>
    <body>
    <div id="app">
        <App />
    </div>        
        <!-- <script src="{{ asset('js/sweetalert2.all.js') }}"></script> -->
        <script src="{{ asset('js/manifest.js') }}" defer></script> 
        <script src="{{ asset('js/vendor.js') }}" defer></script>
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    </body>


<script>
$(document).ready(function(){
    //console.log(localStorage.getItem('user'))
})
</script>
</html>
