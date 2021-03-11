@extends('layout')

@section('title')
    Редагувати оцінку
@endsection

@section('content')
<h4 class="text-danger text-center p-3">Редагувати оцінку</h4>
<p>Студент {{ $grade->student->user->name }} має наступну оцінку з дисципліни '{{ $grade->subject->name }}':</p>
<div class="container p-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form method="post" action="/grades/{{ $group->id }}/{{ $grade->id }}">
                @csrf
                {{ method_field('patch') }}

                <!--Дисципліна-->
                <input type="hidden" id="subject_id" name="subject_id" value="{{ $grade->subject_id }}">

                <!--Семестр-->
                <input type="hidden" id="semester" name="semester" value="{{ $grade->semester }}">

                <!--Студент-->
                <input type="hidden" id="student_id" name="student_id" value="{{ $grade->student_id }}">

                <!--Оцінка-->
                @include("includes/input", ['object' => $grade, 'id' => 'grade', 
                'label' => 'Оцінка:', 'name' => ''])

                <!--Кнопки-->
                @include("includes/button", ['text' => 'Зберегти'])
            </form>
        </div>
    </div>
</div>
@endsection