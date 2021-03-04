@extends('layout')

@section('title')
    Головна сторінка
@endsection

@section('content')
<div class="row m-0 p-0 justify-content-center">
    <!--ГРУПИ-->
    <div class="jumbotron col-md-5 m-4 p-6" style="background-color:#E9F7EF">
        <h4 class="text-secondary">Знайти групу:</h4>

        <hr class="my-4">

        <!--Пошук студента за прізвищем-->
        <div class="justify-content-center pb-3">
            <form method="get" action="/groups/search">
                    <!--Спеціальність-->
                    <div class="form-group container-fluid">
                        <select id="major" name="major" class="form-control">
                            <option disabled selected>Оберіть спеціальність</option>
                            @foreach(config('enums.majors') as $major)
                                <option>{{ $major }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!--Курс-->
                    <div class="form-group container-fluid">
                        <select id="course" name="course" class="form-control">
                            <option disabled selected>Оберіть курс</option>
                            @foreach(config('enums.courses') as $course)
                                <option value="{{ $course }}">{{ $course }} курс</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">Знайти</button>
            </form>
        </div>
    </div>

    <!--СТУДЕНТИ-->
    <div class="jumbotron col-md-5 m-4 p-6" style="background-color:#E9F7EF">
        <h4 class="text-secondary">Знайти студента:</h4>
        <hr class="my-4">

        <!--Пошук студента за прізвищем-->
        <div class="justify-content-center pb-3">
            <form method="get" action="/students/search">
                <div class="form-group container-fluid">
                    <input type="text" name="name" id="name" placeholder="ПІБ студента" class="form-control">
                </div>

                <button type="submit" class="bottom btn btn-success align-bottom">Знайти</button>
            </form>
        </div>
    </div>
</div>
@endsection
