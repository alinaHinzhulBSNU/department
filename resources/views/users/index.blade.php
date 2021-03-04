@extends('layout')

@section('title')
    Адміністрування користувачів
@endsection

@section('content')
    <!--Title-->
    <h4 class="text-primary text-primary text-center pb-3 pt-3">Адміністрування користувачів</h4>

    <!--Search-->
    <div class="row justify-content-end pb-3">
        <div class="col-md-4">
            <form method="get" action="/users/search">
                <div class="row">
                    <div class="col-md-8">
                        <input type="text" name="name" id="name" placeholder="ПІБ користувача" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-block btn-success">Знайти</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!--Content-->
    <div class="row">
        <table class="table table-hover table-bordered">
            <caption class="text-center">Список користувачів</caption>
            <thead class="thead-light">
                <tr>
                    <th class="text-left align-middle" scope="col">ПІБ</th>
                    <th class="text-left align-middle" scope="col">Роль</th>
                    <th class="text-center align-middle" scope="col">Редагувати</th>
                    <th class="text-center align-middle" scope="col">Видалити</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td class="text-left align-middle">{{ $user->name }}</td>
                    <td class="text-left align-middle">{{ $user->role }}</td>
                    <td class="text-left align-middle">
                        <a href="/users/{{ $user->id }}/edit" class="btn btn-warning btn-block">Редагувати</button>
                    </td>
                    <td class="text-left align-middle">
                        <form method="post" action="/users/{{ $user->id }}">
                            @csrf
                            {{ method_field('delete') }}

                            <button type="submit" class="btn btn-danger btn-block">Видалити</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection