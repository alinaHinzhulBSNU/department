@extends('layout')

@section('title')
    Редагувати дані про користувача
@endsection

@section('content')
<h4 class="text-danger text-center p-3">Редагувати дані про користувача</h4>
<div class="container p-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form method="post" action="/users/{{ $user->id }}">
                @csrf
                {{ method_field('patch') }}

                <!--ПІБ-->
                @include("includes/input", ['object' => $user, 'id' => 'name', 
                'label' => 'Введіть ПІБ:', 'name' => 'ПІБ користувача'])

                <!--Кнопки-->
                @include("includes/button", ['text' => 'Зберегти'])
            </form>
        </div>
    </div>
</div>
@endsection