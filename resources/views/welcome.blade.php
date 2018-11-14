<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href='https://fonts.googleapis.com/css?family=Raleway:300,400,500,700|Material+Icons' rel="stylesheet">
        <link rel="stylesheet" href="{{asset('css/index.css')}}">
        <link rel="stylesheet" href="{{asset('css/loader.css')}}">
        <link rel="stylesheet" href="{{asset('css/noscript.css')}}">
        <link rel="icon" href="{{asset('favicon.png')}}">
        <title>JIMAC Test Suite</title>
    </head>
    <body>
        <noscript>
        <div class="noscript-text"><div class="container fill-height bg grid-list-md fluid"><div class="layout row wrap align-center"><div class="flex text-md-center md1"><i aria-hidden="true" class="v-icon material-icons theme--light" style="font-size: 40px;">info</i></div> <div class="flex md11"><h1>Sorry JavaScript is not allowed. I need it to run. Please enable or use a browser that can run JavaScript.</h1></div></div></div></div>
        </noscript>

        <div class="lds-roller" id="loading" style="display: none">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
        
        <div id="app"></div>
        <script src="{{asset('js/index.js')}}"></script>
    </body>
</html>
