@extends('layout')

@section('title')
    Студенти: 
@endsection

@section('content')
    <!--Title-->
    <h4 class="text-primary text-center pb-3 pt-3">Студенти</h4>
    <a href="/teachers/create" class="btn btn-success mb-3">Додати студента</a>

    <!--Content-->
    <div class="row">
        <table class="table table-hover table-bordered">
            <caption class="text-center">Список студентів</caption>
            <thead class="thead-light">
                <tr class="text-center">
                    <th class="text-left" scope="col">ПІБ</th>
                    <th class="text-left" scope="col">Група</th>
                    <th class="text-left" scope="col">Спеціальність</th>
                    
                    <th class="text-left" scope="col">Староста</th>
                    <th class="text-left" scope="col">Стипендія</th>
                    <th class="text-left" scope="col">Соціальна стипендія</th>

                    <th class="text-center" scope="col">Редагувати</th>
                    <th class="text-center" scope="col">Видалити</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                <tr>
                    <td class="text-left">{{ $student->user->name }}</td>
                    <td class="text-left">{{ $student->group->number }}</td>
                    <td class="text-left">{{ $student->group->major }}</td>

                    
                    <td class="text-left"><input type="checkbox" onclick="return false;" 
                        @if( $student->is_class_leader) checked @endif >
                    </td>

                    <td class="text-left"><input type="checkbox" onclick="return false;" 
                        @if( $student->has_grant) checked @endif >
                    </td>

                    <td class="text-left"><input type="checkbox" onclick="return false;" 
                        @if( $student->has_social_grant) checked @endif >
                    </td>
                    

                    <td></td>
                    <td></td>
                   
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection