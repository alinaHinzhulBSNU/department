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
                <tr class="text-center">
                    <th class="text-left" scope="col">Номер</th>
                    <th class="text-left" scope="col">Курс</th>
                    <th class="text-left" scope="col">Спеціальність</th>
                    <th class="text-center" scope="col">Академічний рік</th>
                    @can('admin')
                        <th class="text-center" scope="col">Редагувати</th>
                        <th class="text-center" scope="col">Видалити</th>
                    @endcan
                </tr>
            </thead>
            <tbody>
                @foreach($groups as $group)
                <tr>
                    <td class="text-left">{{ $group->number }}</td>
                    <td class="text-left">{{ $group->course }}</td>
                    <td class="text-left">{{ $group->major }}</td>
                    <td class="text-center">{{ $group->start_year }} - {{ $group->end_year }}</td>
                    @can('admin')
                        <td>
                            <a href="/groups/{{ $group->id }}/edit" class="btn btn-warning btn-block">Редагувати</button>
                        </td>
                    <td>
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