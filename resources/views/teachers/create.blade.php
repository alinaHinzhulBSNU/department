@extends('layout')

@section('title')
    Додати викладача
@endsection

@section('content')
<h4 class="text-danger text-center p-3">Додати дані про викладача</h4>
<div class="container p-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form method="post" action="/teachers">
                @csrf

                <!--Відповідний обліковий запис-->
                @include("includes/select", ['collection' => $users, 
                'id' => 'user_id', 'label' => 'Відповідний акаунт:', 'placeholder' => 'Оберіть відповідний акаунт'])

                <!--Вчене звання-->
                @include("includes/input", ['id' => 'degree', 
                'label' => 'Вчене звання:', 'name' => 'Введіть вчене званння'])

                <!--Кафедра-->
                <div class="form-group row">
                    <div class="label col-md-4 text-right">
                        <label for="department">Кафедра:</label>
                    </div>
                    <div class="col-md-8">
                        <select id="department" name="department" class="form-control {{ $errors->has('department') ? 'invalid':'' }}">
                            @foreach(config('enums.departments') as $department)
                                <option>{{ $department }}</option>
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