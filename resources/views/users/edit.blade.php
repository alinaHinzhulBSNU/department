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

                <!--Роль-->
                <div class="form-group row">
                    <div class="label col-md-4 text-right">
                        <label for="author">Роль користувача:</label>
                    </div>
                    <div class="col-md-8">
                        <select id="role" name="role" class="form-control {{ $errors->has('role') ? 'invalid':'' }}">
                            @foreach(config('enums.roles') as $role)
                                <option @isset($user) @if($user->role == $role) selected @endif @endisset>
                                    {{ $role }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!--Кнопки-->
                @include("includes/button", ['text' => 'Зберегти'])
            </form>
        </div>
    </div>
</div>
@endsection