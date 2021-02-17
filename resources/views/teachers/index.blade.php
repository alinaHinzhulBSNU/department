@extends('layout')

@section('title')
    Адміністрування викладачів
@endsection

@section('content')
    <!--Title-->
    <h4 class="text-primary text-center pb-3 pt-3">Адміністрування викладачів</h4>
    <a href="/teachers/create" class="btn btn-success mb-3">Додати викладача</a>

    <!--Content-->
    <div class="row">
        <table class="table table-hover table-bordered">
            <caption>Список викладачів</caption>
            <thead class="thead-light">
                <tr class="text-center">
                    <th class="text-left" scope="col">ПІБ</th>
                    <th class="text-left" scope="col">Вчене звання</th>
                    <th class="text-left" scope="col">Кафедра</th>
                    <th class="text-center" scope="col">Редагувати</th>
                    <th class="text-center" scope="col">Видалити</th>
                </tr>
            </thead>
            <tbody>
                @foreach($teachers as $teacher)
                <tr>
                    <td class="text-left">{{ $teacher->user->name }}</td>
                    <td class="text-left">{{ $teacher->degree }}</td>
                    <td class="text-left">{{ $teacher->department }}</td>
                    <td>
                        <a href="/teachers/{{ $teacher->id }}/edit" class="btn btn-warning btn-block">Редагувати</button>
                    </td>
                    <td>
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