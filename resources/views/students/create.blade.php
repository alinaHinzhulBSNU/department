@extends('layout')

@section('title')
    Додати студента
@endsection

@section('content')
<h4 class="text-danger text-center p-3">Додати дані про студента</h4>
<div class="container p-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form method="post" action="/students">
                @csrf

                <!--Відповідний обліковий запис-->
                @include("includes/select", ['collection' => $users, 
                'id' => 'user_id', 'label' => 'Відповідний акаунт:', 'placeholder' => 'Оберіть відповідний акаунт'])

                <!--Відповідна група --> <!-- include doesn't work for group bc you need group->NUMBER and not ->NAME -->
               
                <div class="form-group row">
                    <div class="label col-md-4 text-right">
                        <label for="group_id">Група:</label>
                    </div>
                    <div class="col-md-8">
                        <select id="group_id" name="group_id" class="form-control {{ $errors->has('group_id') ? 'invalid':'' }}">
                            <option selected disabled value="0">Оберіть відповідну групу</option>
                            @foreach($groups as $group)
                                <option @isset($object) @if($object->group_id == $group->id) selected @endif @endisset
                                value="{{ $group->id }}">{{ $group->number }}</option>
                            @endforeach
                        </select>
                    </div>
                    @include("includes/validationErrors", ['errFieldName' => 'group_id'])
                </div>


                <!-- create an include for a checkbox! -->
                <div class="form-group row p-0 m-0">
                    <!-- Староста -->
                    <div class="col-md-4">
                        <input class="form-group" type='hidden' value='0' name="is_class_leader">
                        <input class="form-group" type="checkbox" value='1' id="is_class_leader" name="is_class_leader">
                        <label class="form-group" for="is_class_leader">Староста</label>
                    </div>
                    <!-- Академ. Стипендія -->
                    <div class="col-md-4">
                        <input type='hidden' value='0' name="has_grant">
                        <input type="checkbox" value='1' id="has_grant" name="has_grant">
                        <label for="has_grant">Академічна стипендія</label>
                    </div>
                    <!-- Соц. стипендія  -->
                    <div class="col-md-4">
                        <input type='hidden' value='0' name="has_social_grant">
                        <input type="checkbox" value='1' id="has_social_grant" name="has_social_grant">
                        <label for="has_social_grant">Соціальна стипендія</label>
                    </div>
                </div> 
               
                <!-- in the form of a table: --> 
                <!-- <div>
                <table>
                    
                    <tr>
                        <td> <label for="is_class_leader">Староста</label> </td>
                        <td> <input type="checkbox" id="is_class_leader" name="is_class_leader"> </td>
                    </tr>

                    <tr>
                        <td> <label for="has_grant">Академічна стипендія</label> </td>
                        <td> <input type="checkbox" id="has_grant" name="has_grant"> </td>
                    </tr>

                    <tr>
                        <td>  <label for="has_social_grant">Соціальна стипендія</label> </td>
                        <td> <input type="checkbox" id="has_social_grant" name="has_social_grant"></td>
                    </tr>


                </table>
                </div> -->

                <!--Кнопки-->
                @include("includes/button", ['text' => 'Зберегти'])
            </form>
        </div>
    </div>
</div>
@endsection