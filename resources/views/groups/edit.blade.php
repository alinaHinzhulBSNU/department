@extends('layout')

@section('title')
    Редагувати групу
@endsection

@section('content')
<h4 class="text-danger text-center p-3">Редагувати дані про групу</h4>
<div class="container p-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form method="post" action="/groups/{{ $group->id }}">
                @csrf
                {{ method_field('patch') }}

                <!--Номер групи-->
                @include("includes/input", ['object' => $group, 'id' => 'number', 
                'label' => 'Номер групи:', 'name' => 'Введіть номер групи'])

                <!--Курс-->
                @include("includes/input", ['object' => $group, 'id' => 'course', 
                'label' => 'Курс:', 'name' => 'Введіть номер курсу'])

                <!--Початок академічного року-->
                @include("includes/input", ['object' => $group, 'id' => 'start_year', 
                'label' => 'Початок академ. року:', 'name' => 'Введіть початок академічного року'])

                <!--Кінець академічного року-->
                @include("includes/input", ['object' => $group, 'id' => 'end_year', 
                'label' => 'Кінець академ. року:', 'name' => 'Введіть кінець академічного року'])

                <!--Спеціальність-->
                <div class="form-group row">
                    <div class="label col-md-4 text-right">
                        <label for="major">Спеціальність:</label>
                    </div>
                    <div class="col-md-8">
                        <select id="major" name="major" class="form-control {{ $errors->has('major') ? 'invalid':'' }}">
                            @foreach(config('enums.majors') as $major)
                                <option @if($group->major == $major) selected @endif>
                                    {{ $major }}
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