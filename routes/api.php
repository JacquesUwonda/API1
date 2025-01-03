<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',[AuthController::class,'login']);

//get all post
Route::get('/all/post',[PostController::class,'getAllPosts']);

//get single post
Route::get('/single/post/{post_id}',[PostController::class,'getSinglePost']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    //blog api endpoints
    Route::post('/add/post',[PostController::class,'addNewPost']);

    //edit post
    Route::post('/edit/post',[PostController::class,'editPost']);
    Route::post('/edit/post/{post_id}',[PostController::class,'editPost2']);

    //delete post
    Route::post('/delete/post/{post_id}',[PostController::class,'deletePost']);

});
