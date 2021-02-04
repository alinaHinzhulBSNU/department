<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <!--Google Fonts-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Forum&display=swap" rel="stylesheet">

    <!--Font Awesome Icons-->
    <script src="https://kit.fontawesome.com/66af6c845b.js" crossorigin="anonymous"></script>

    <!--Bootstrap-->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css')}}">

    <!--Власні стилі-->
    <link rel="stylesheet" href="{{ asset('css/style.css')}}">

    <!--JS-->
    <script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
</head>
<body>
    <div class="text-center p-3">
        <h4>Чорноморський національний університет імені Петра Могили</h4>
        <h2>Факультет комп'ютерних наук</h2>
    </div>
    @yield('content')
</body>
</html>