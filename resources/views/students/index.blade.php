@extends('layout')

@section('title')
    Студенти
@endsection

@section('content')
    <!--Title-->
    <h4 class="text-primary text-center pb-3 pt-3">Студенти:</h4>
    @can('admin')
        <a href="/students/create" class="btn btn-success mb-3">Додати студента</a>
    @endcan
    
    <!--Content-->
    <div class="row table-container">
        <table class="table table-hover table-bordered">
            <caption class="text-center">Список студентів</caption>
            <thead class="thead-light">
                <tr class="text-center">
                    <th class="text-left align-middle" scope="col">ПІБ</th>
                    <th class="text-left align-middle" scope="col">Група</th>
                    <th class="text-left align-middle" scope="col">Спеціальність</th>
                    
                    <th class="text-center align-middle" scope="col">Староста</th>
                    <th class="text-center align-middle" scope="col">Академічна стипендія</th>
                    <th class="text-center align-middle" scope="col">Соціальна стипендія</th>
                    @can('admin')
                        <th class="text-center align-middle" scope="col">Редагувати</th>
                        <th class="text-center align-middle" scope="col">Видалити</th>
                    @endcan 
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                <tr>
                    <td class="text-left align-middle">{{ $student->user->name }}</td>
                    <td class="text-left align-middle">{{ $student->group->number }}</td>
                    <td class="text-left align-middle">{{ $student->group->major }}</td>

                    
                    <td class="text-center align-middle">
                        @if($student->is_class_leader)
                        <h3 class="text-success">
                            <i class="far fa-check-circle"></i>
                        </h3>
                        @endif
                    </td>

                    <td class="text-center align-middle">
                        @if($student->has_grant) 
                        <h3 class="text-success">
                            <i class="far fa-check-circle"></i>
                        </h3>
                        @endif
                    </td>

                    <td class="text-center align-middle">
                        @if($student->has_social_grant) 
                        <h3 class="text-success">
                            <i class="far fa-check-circle"></i>
                        </h3>
                        @endif
                    </td>
                    
                    @can('admin')
                        <td class="text-center align-middle">
                            <a href="/students/{{ $student->id }}/edit" class="btn btn-warning btn-block">Редагувати</button>
                        </td>
                        <td class="text-center align-middle">
                        <form method="post" action="/students/{{ $student->id }}">
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