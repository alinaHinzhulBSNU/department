@extends('layout')

@section('title')
    Редагувати дані про викладача
@endsection

@section('content')
<h4 class="text-danger text-center p-3">Редагувати дані про викладача</h4>
<div class="container p-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form method="post" action="/teachers/{{ $teacher->id }}">
                @csrf
                {{ method_field('patch') }}

                <!--Обліковий запис-->
                <input type="hidden" id="user_id" name="user_id" value="{{ $teacher->user->id }}">

                <!--Вчене звання-->
                @include("includes/input", ['object' => $teacher, 'id' => 'degree', 
                'label' => 'Вчене звання:', 'name' => 'Введіть вчене званння'])

                <!--Кафедра-->
                <div class="form-group row">
                    <div class="label col-md-4 text-right">
                        <label for="department">Кафедра:</label>
                    </div>
                    <div class="col-md-8">
                        <select id="department" name="department" class="form-control {{ $errors->has('department') ? 'invalid':'' }}">
                            @foreach(config('enums.departments') as $department)
                                <option  @isset($teacher) @if($teacher->department == $department) selected @endif @endisset>
                                    {{ $department }}
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