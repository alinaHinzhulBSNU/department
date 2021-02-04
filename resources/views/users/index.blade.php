@extends('layout')

@section('title')
    Адміністрування користувачів
@endsection

@section('content')
    <!--Title-->
    <h4 class="text-center">Адміністрування користувачів</h4>

    <!--Search-->
    <div class="row justify-content-end p-3">
        <div class="col-md-4">
            <form method="get" action="/search">
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
    <table class="table table-hover table-sm">
    <thead>
        <tr class="text-center">
            <th scope="col">ПІБ</th>
            <th scope="col">Роль</th>
            <th scope="col">Редагувати</th>
            <th scope="col">Видалити</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr class="text-center">
            <td>{{ $user->name }}</td>
            <td>{{ $user->role }}</td>
            <td>
                <a href="/users/{{ $user->id }}/edit" class="btn btn-warning">Редагувати</button>
            </td>
            <td>
            <form method="post" action="/users/{{ $user->id }}">
                @csrf
                {{ method_field('delete') }}

                <button type="submit" class="btn btn-danger">Видалити</button>
            </form>
            </td>
        </tr>
        @endforeach
    </tbody>
    </table>
@endsection