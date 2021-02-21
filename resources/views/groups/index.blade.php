@extends('layout')

@section('title')
    Адміністрування груп
@endsection

@section('content')
    <!--Title-->
    <h4 class="text-primary text-center pb-3 pt-3">Групи:</h4>
    @can('admin')
        <a href="/groups/create" class="btn btn-success mb-3">Додати групу</a>
    @endcan

    <!--Content-->
    <div class="row">
        <table class="table table-hover table-bordered">
            <caption class="text-center">Список груп</caption>
            <thead class="thead-light">
                <tr>
                    <th class="text-left align-middle" scope="col">Номер</th>
                    <th class="text-left align-middle" scope="col">Курс</th>
                    <th class="text-left align-middle" scope="col">Спеціальність</th>
                    <th class="text-center align-middle" scope="col">Академічний рік</th>
                    @can('admin')
                        <th class="text-center align-middle" scope="col">Редагувати</th>
                        <th class="text-center align-middle" scope="col">Видалити</th>
                    @endcan
                </tr>
            </thead>
            <tbody>
                @foreach($groups as $group)
                <tr>
                    <td class="text-left align-middle">{{ $group->number }}</td>
                    <td class="text-left align-middle">{{ $group->course }}</td>
                    <td class="text-left align-middle">{{ $group->major }}</td>
                    <td class="text-center align-middle">{{ $group->start_year }} - {{ $group->end_year }}</td>
                    @can('admin')
                        <td class="text-center align-middle">
                            <a href="/groups/{{ $group->id }}/edit" class="btn btn-warning btn-block">Редагувати</button>
                        </td>
                        <td class="text-center align-middle">
                            <form method="post" action="/groups/{{ $group->id }}">
                                @csrf
                                {{ method_field('delete') }}

                                <button type="submit" class="btn btn-danger btn-block">Видалити</button>
                            </form>
                        </td>
                    @endcan
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection