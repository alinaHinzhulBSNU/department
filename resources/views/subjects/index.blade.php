@extends('layout')

@section('title')
    Адміністрування дисциплін
@endsection

@section('content')
    <!--Title-->
    <h4 class="text-primary text-center pb-3 pt-3">Адміністрування дисциплін</h4>
    <a href="/subjects/create" class="btn btn-success mb-3">Додати дисципліну</a>

    <!--Content-->
    <div class="row">
        <table class="table table-hover table-bordered">
            <caption class="text-center">Список дисциплін</caption>
            <thead class="thead-light">
                <tr class="text-center">
                    <th class="text-left" scope="col">Назва</th>
                    <th class="text-left" scope="col">Викладач</th>
                    <th class="text-left" scope="col">Тип підсумкової атестації</th>
                    <th class="text-left" scope="col">Кредити</th>
                    <th class="text-left" scope="col">Опис</th>
                    <th class="text-center" scope="col">Редагувати</th>
                    <th class="text-center" scope="col">Видалити</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subjects as $subject)
                <tr>
                    <td class="text-left">{{ $subject->name }}</td>
                    <td class="text-left">{{ $subject->teacher->user->name }}</td>
                    <td class="text-left">{{ $subject->exam_type }}</td>
                    <td class="text-left">{{ $subject->credit }}</td>
                    <td class="text-left">{{ $subject->description }}</td>
                    <td>
                        <a href="/subjects/{{ $subject->id }}/edit" class="btn btn-warning btn-block">Редагувати</button>
                    </td>
                    <td>
                    <form method="post" action="/subjects/{{ $subject->id }}">
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