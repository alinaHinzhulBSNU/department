@extends('auth')

@section('title')
    Авторизація
@endsection

@section('content')

<h4 class="text-center pb-3">Авторизація</h4>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 justify-content-center">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group row">
                    <div class="col-md-12">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="E-mail">

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Пароль">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row justify-content-center">
                    <div class="col-md-6 mx-auto">
                        <button type="submit" class="btn btn-primary btn-block">
                            Увійти
                        </button>
                        <a href="/register" class="btn btn-outline-secondary btn-block">
                            Зареєструватися
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
