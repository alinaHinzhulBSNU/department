@extends('layout')

@section('title')
    Виставити оцінку
@endsection

@section('content')
<h4 class="text-danger text-center p-3">Оцінювання:</h4>
<div class="container p-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form method="post" action="/grades/{{ $group->id }}">
                @csrf

                <!--Дисципліна-->
                @include("includes/select", ['collection' => $subjects, 
                'id' => 'subject_id', 'label' => 'Оберіть дисципліну:', 'placeholder' => 'Дисципліна'])

                <!--Семестр-->
                @include("includes/input", ['id' => 'semester', 
                'label' => 'Введіть семестр:', 'name' => 'Семестр'])

                <!--Студент-->
                <div class="form-group row">
                    <div class="label col-md-4 col-sm-4 text-right">
                        <label for="major">Оберіть студента:</label>
                    </div>
                    <div class="col-md-8 col-sm-8">
                        <select id="student_id" name="student_id" class="form-control {{ $errors->has('Student_id') ? 'invalid':'' }}">
                        <option selected disabled value="0">Студент</option>
                            @foreach($group->students as $student)
                                <option value="{{ $student->id }}">{{ $student->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!--Оцінка-->
                @include("includes/input", ['id' => 'grade', 
                'label' => 'Введіть оцінку:', 'name' => 'Оцінка'])

                <!--Кнопки-->
                @include("includes/button", ['text' => 'Зберегти'])
            </form>
        </div>
    </div>
</div>
@endsection