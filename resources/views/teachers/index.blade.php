@extends('layout')

@section('title')
    Адміністрування викладачів
@endsection

@section('content')
    <!--Title-->
    <h4 class="text-primary text-center pb-3 pt-3">Адміністрування викладачів</h4>
    <a href="/teachers/create" class="btn btn-success mb-3">Додати викладача</a>

    <!--Content-->
    <div class="row table-container">
        <table class="table table-hover table-bordered">
            <caption class="text-center">Список викладачів</caption>
            <thead class="thead-light">
                <tr>
                    <th class="text-left align-middle" scope="col">ПІБ</th>
                    <th class="text-left align-middle" scope="col">Вчене звання</th>
                    <th class="text-left align-middle" scope="col">Кафедра</th>
                    <th class="text-center align-middle" scope="col">Редагувати</th>
                    <th class="text-center align-middle" scope="col">Видалити</th>
                </tr>
            </thead>
            <tbody>
                @foreach($teachers as $teacher)
                <tr>
                    <td class="text-left align-middle">{{ $teacher->user->name }}</td>
                    <td class="text-left align-middle">{{ $teacher->degree }}</td>
                    <td class="text-left align-middle">{{ $teacher->department }}</td>
                    <td class="text-left align-middle">
                        <a href="/teachers/{{ $teacher->id }}/edit" class="btn btn-warning btn-block">Редагувати</button>
                    </td>
                    <td class="text-left align-middle">
                    <form method="post" action="/teachers/{{ $teacher->id }}">
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