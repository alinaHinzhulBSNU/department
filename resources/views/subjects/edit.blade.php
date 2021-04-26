@extends('layout')

@section('title')
    Редагувати дисципліну
@endsection

@section('content')
<h4 class="text-danger text-center p-3">Редагувати дані про дисципліну</h4>
<div class="container p-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form method="post" action="/subjects/{{ $subject->id }}">
                @csrf
                {{ method_field('patch') }}

                <!--Назва дисципліни-->
                @include("includes/input", ['object' => $subject, 'id' => 'name', 
                'label' => 'Назва дисципліни:', 'name' => 'Введіть назву'])

                <!--Опис дисципліни-->
                @include("includes/input", ['object' => $subject,'id' => 'description', 
                'label' => 'Опис дисципліни:', 'name' => 'Введіть опис'])

                <!--Кредит дисципліни-->
                @include("includes/input", ['object' => $subject, 'id' => 'credit', 
                'label' => 'Кредит дисципліни:', 'name' => 'Введіть кредит'])

                <!--Викладач-->
                <div class="form-group row">
                    <div class="label col-md-4 col-sm-4 text-right">
                        <label for="teacher_id">Викладач:</label>
                    </div>
                    <div class="col-md-8 col-sm-8">
                        <select id="teacher_id" name="teacher_id" class="form-control {{ $errors->has('teacher_id') ? 'invalid':'' }}">
                            <option selected disabled value="0">Оберіть викладача</option>
                            @foreach($teachers as $teacher)
                                <option @isset($subject) @if($subject->teacher->id == $teacher->id) selected @endif @endisset value="{{ $teacher->id }}">
                                    {{ $teacher->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @include("includes/validationErrors", ['errFieldName' => 'teacher_id'])
                </div>

                <!--Тип підсумкової атестації-->
                <div class="form-group row">
                    <div class="label col-md-4 col-sm-4 text-right">
                        <label for="exam_type">Тип підсумкової атестації:</label>
                    </div>
                    <div class="col-md-8 col-sm-8">
                        <select id="exam_type" name="exam_type" class="form-control {{ $errors->has('exam_type') ? 'invalid':'' }}">
                            @foreach(config('enums.exams') as $exam_type)
                                <option @if($subject->exam_type == $exam_type) selected @endif>
                                    {{ $exam_type }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!--Кнопки-->
                @include("includes/button", ['text' => 'Зберегти'])
            </form>
        </div>
    </div>
</div>
@endsection