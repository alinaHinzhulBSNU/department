@extends('layout')

@section('title')
    Редагувати дані про студента
@endsection

@section('content')
<h4 class="text-danger text-center p-3">Редагувати дані про студента</h4>
<div class="container p-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form method="post" action="/students/{{ $student->id }}">
                @csrf
                {{ method_field('patch') }}

                <!--Обліковий запис-->
                <input type="hidden" id="user_id" name="user_id" value="{{ $student->user->id }}">

                <!-- ПІБ --> 
                <h3 class="m-2">{{ $student->user->name }}</h3>

                <!--Відповідна група -->
                <div class="form-group row">
                    <div class="label col-md-4 text-right">
                        <label for="group_id">Група:</label>
                    </div>
                    <div class="col-md-8">
                        <select id="group_id" name="group_id" class="form-control {{ $errors->has('group_id') ? 'invalid':'' }}">
                            <option selected disabled value="0">Оберіть відповідну групу</option>
                            @foreach($groups as $group)
                                <option @isset($student) @if($student->group_id == $group->id) selected @endif @endisset
                                value="{{ $group->id }}">{{ $group->number }}</option>
                            @endforeach
                        </select>
                    </div>
                    @include("includes/validationErrors", ['errFieldName' => 'group_id'])
                </div>

                 <!-- Староста -->
                 <div>
                    <label for="is_class_leader">Староста</label>
                    <input type='hidden' value='0' name="is_class_leader">
                    <input type="checkbox"  name="is_class_leader" value="1"
                        @if($student->is_class_leader == 1) 
                            checked 
                    @endif>
                </div>
                <!-- Академ. Стипендія -->
                <div>
                    <label for="has_grant">Академічна стипендія</label>
                    <input type='hidden' value='0' name="has_grant">
                    <input type="checkbox" id="has_grant" name="has_grant" value="1"
                        @if($student->has_grant == 1) 
                            checked 
                        @endif>
                </div>
                <!-- Соц. стипендія  -->
                <div>
                    <label for="has_social_grant">Соціальна стипендія</label>
                    <input type='hidden' value='0' name="has_social_grant">
                    <input type="checkbox" id="has_social_grant" name="has_social_grant" value="1"
                        @if($student->has_social_grant == 1) 
                            checked 
                        @endif>
                </div>

                <!--Кнопки-->
                @include("includes/button", ['text' => 'Зберегти'])
            </form>
        </div>
    </div>
</div>
@endsection