<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title')</title>

        <!--Google Fonts-->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">

        <!--Font Awesome Icons-->
        <script src="https://kit.fontawesome.com/66af6c845b.js" crossorigin="anonymous"></script>

        <!-- main.css is used instead of bootstrap to allow style custimization (Contains bootstrap but with modified colors) --> 
        <link rel="stylesheet" href="{{ asset('css/main.css')}}" >
        <!-- this file is generated when sass code (from main.scss) is compiled to css -->

        <!--JS-->
        <script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
        <script src="{{ asset('js/popper.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    </head>

    <body>
        <!--Header-->
        <header>
            <nav class="navbar navbar-expand-lg navbar-light bg-light pb-3 pt-3">
                <a class="navbar-brand" href="/">
                    <i class="fas fa-desktop"></i>
                    <span>Факультет комп'ютерних наук</span>
                </a>

                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav">
                        @can('admin')
                            <li class="nav-item">
                                <a class="nav-link" href="/teachers">Викладачі</a>
                            </li>
                        @endcan

                        @can('admin')
                        <li class="nav-item">
                            <a class="nav-link" href="/subjects">Дисципліни</a>
                        </li>
                        @endcan

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
        </header>

        <!--Content-->
        <section class="main-content text-center">
            <div class="row justify-content-center p-0 m-0">
                <div class="col-md-10">
                    @yield('content')
                </div>
            </div>
	    </section>

        <!--Footer-->
        <footer class="bg-light text-center text-lg-start">
            <div class="text-center p-3">
                <p>© Факультет комп'ютерних наук ЧНУ ім. Петра Могили</p>
                <p>(0512) 76-55-74</p>
                <p>dekanatfkn@gmail.com</p>
            </div>
        </footer>
    </body>
</html>