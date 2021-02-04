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
        <!--Header-->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="/">Факультет комп'ютерних наук</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Викладачі</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">Дисципліни</a>
                    </li>

                    @can('admin')
                        <li class="nav-item">
                            <a class="nav-link" href="/users">Користувачі</a>
                        </li>
                    @endcan
                    
                    <li class="nav-item">
                        <!--Logout-->
                        <a class="nav-link" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                            Вихід
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </nav>

        <!--Content-->
        <div>
            @yield('content')
        </div>

        <!--Footer-->
        <footer class="bg-light text-center text-lg-start fixed-bottom">
            <div class="text-center p-3">
                <p>© Факультет комп'ютерних наук ЧНУ ім. Петра Могили</p>
                <p>(0512) 76-55-74</p>
                <p>dekanatfkn@gmail.com</p>
            </div>
        </footer>
    </body>
</html>