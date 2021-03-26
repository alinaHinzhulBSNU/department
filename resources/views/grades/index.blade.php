@extends('layout')

@section('title')
    Журнал
@endsection

@section('content')
    <!--Title-->
    <h4 class="text-primary text-center pb-3 pt-3">Рейтинг / Журнал групи №{{ $group->number }}:</h4>

    <!--Add grades-->
    @can('teach')
        <a href="/grades/{{ $group->id }}/create" class="btn btn-success mb-3">Виставити бали</a>
        <br/>
        <a href="/grades/{{ $group->id }}/pdf" class="btn btn-warning mb-3">Завантажити відомість</a> <!-- pdf for all subjects --> 
        
    @endcan

    <!--Content-->
    <div class="row">
        <table id="gradebook" class="table table-hover table-bordered table-sortable">
            <caption class="text-center">Список груп</caption>
            <thead class="thead-light">
                <tr>
                    <th class="text-left align-middle" scope="col">Ім'я студента</th>
                    @foreach($subjects as $subject)
                        <th class="text-center align-middle" scope="col">{{ $subject->name }}</th>
                    @endforeach
                    <th>Рейтинговий бал</th>
                </tr>
            </thead>

            <tbody>
                @can('teach')
                    <tr> <!-- PDF for each subject separately --> 
                        <td class="text-left align-middle" scope="col"></td>
                        @foreach($subjects as $subject)
                            <td><a href="/grades/{{ $group->id }}/{{ $subject->id }}/pdf" class="btn btn-warning mb-3">Завантажити відомість</a> </td>
                        @endforeach
                        <td class="text-left align-middle" scope="col"></td>
                    </tr>
                @endcan
               
                @foreach($group->students as $student)
                <tr>
                    <!--Name of student-->
                    <td class="text-left align-middle">{{ $student->user->name }}</td>

                    <!--Grades for subjects-->
                    @foreach($subjects as $subject)
                        <td class="text-center align-middle" scope="col">
                            @foreach($student->grades as $grade)
                                @if($grade->subject->id === $subject->id)
                                    <div class="row">
                                        <div class="col-md-4">
                                            <p>{{ $grade->grade }}</p>
                                        </div>
                                        @if(Auth::user()->can('update', $grade, Grade::class))
                                        <div class="col-md-4">
                                            <a href="/grades/{{ $group->id }}/{{ $grade->id }}/edit">
                                                <button type="button" class="btn btn-success">
                                                    <i class="fas fa-pen"></i>
                                                </button>
                                            </a>
                                        </div>
                                        <div class="col-md-4">
                                            <form method="post" action="/grades/{{ $group->id }}/{{ $grade->id }}">
                                                @csrf
                                                {{ method_field('delete') }}

                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>   
                                        </div>
                                        @endif
                                    </div>  
                                @endif
                            @endforeach
                        </td>
                    @endforeach

                    <!--Grade for rating-->
                    <td class="text-center align-middle">{{ $student->rating() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>



    <a href="/groups" class="btn btn-warning mb-3">Повернутися до списку груп</a>
@endsection