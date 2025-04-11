<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="icons/logo/logo.ico" type="image/x-icon">
        <title>@yield('title')</title>

        <link href="{{asset('css/common.css?v=').time()}}" rel="stylesheet">
    </head>

    <body>
        @yield('content')
    </body>

</html>