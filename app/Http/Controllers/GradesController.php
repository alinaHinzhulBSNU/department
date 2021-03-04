<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GradesController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    //Route::get('/grades', [\App\Http\Controllers\GradesController::class, 'index']);
    public function index(){

    }

    //Route::get('/grades/create', [\App\Http\Controllers\GradesController::class, 'create']);
    public function create(){

    }

    //Route::post('/grades', [\App\Http\Controllers\GradesController::class, 'store']);
    public function store(Request $request){

    }

    //Route::get('/grades/{id}/edit', [\App\Http\Controllers\GradesController::class, 'edit']);
    public function edit($id){

    }

    //Route::patch('/grades/{id}', [\App\Http\Controllers\GradesController::class, 'update']);
    public function update($id){

    }

    //Route::delete('/grades/{id}', [\App\Http\Controllers\GradesController::class, 'destroy']);
    public function destroy($id){

    }
    
    //VALIDATE
    private function validateData($data){
        
    }
}
