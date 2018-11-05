<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href='https://fonts.googleapis.com/css?family=Raleway:300,400,500,700|Material+Icons' rel="stylesheet">
        <link rel="stylesheet" href="{{asset('css/index.css')}}">
        <link rel="icon" href="{{asset('favicon.png')}}">
        <title>JIMAC Test Suite</title>
    </head>
    <body>
        <div id="app"></div>
        <script src="{{asset('js/index.js')}}"></script>
    </body>
</html>
