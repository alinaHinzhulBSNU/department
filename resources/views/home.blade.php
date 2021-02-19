@extends('layout')

@section('title')
    Головна сторінка
@endsection

@section('content')
<div class="row m-0 p-0 justify-content-center">
    <div class="jumbotron col-md-5 m-4" style="background-color:#E9F7EF">
        <h4 class="text-secondary">Знайти групу:</h4>
        <hr class="my-4">
        <p class="lead">//TO DO: форма для пошуку студентської групи</p>
        <p class="lead">
            <a href="/groups" class="btn btn-primary btn-lg" href="#">Знайти групу</a>
        </p>
    </div>
    <div class="jumbotron col-md-5 m-4" style="background-color:#E9F7EF">
        <h4 class="text-secondary">Знайти студента:</h4>
        <hr class="my-4">
        <p class="lead">//TO DO: форма для пошуку студента за прізвищем</p>
        <p class="lead">
            <a href="#" class="btn btn-primary btn-lg" href="#">Знайти студента</a>
        </p>
    </div>
</div>
@endsection
