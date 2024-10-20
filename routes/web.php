<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::post('/task' , function (){

});


Route::delete('/task/{id}',function($id){

});