<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AuthorController;
use App\Http\Controllers\API\BookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', [UserController::class, 'createUser']); 
Route::post('login', [UserController::class, 'login']);
Route::post('logout',[UserController::class, 'logout']);
Route::get('book/list', [BookController::class, 'show']);

Route::middleware('auth:api')->group(function(){
    // Admin
    Route::post('user', [UserController::class, 'userInfo']);
    Route::put('user/update/{userId}', [UserController::class, 'updateUser']);
    Route::get('user/list', [UserController::class, 'AllUsers']); 
    Route::get('user/status/{userId}/{currentstatus}', [UserController::class, 'changeUserStatus']);    
    

    // Author
    Route::get('author/list', [AuthorController::class, 'authorList']);
    Route::post('author/create', [AuthorController::class, 'store']);
    Route::post('author/update/{userId}', [AuthorController::class, 'update']);
    Route::delete('author/delete/{userId}', [AuthorController::class, 'destroy']);
    Route::get('author/search/{userId}', [AuthorController::class, 'search']);

    // Book
    Route::post('book/create', [BookController::class, 'store']);
    Route::post('book/update/{bookId}', [BookController::class, 'update']);
    Route::delete('book/delete/{bookId}', [BookController::class, 'destroy']);
    Route::get('book/list/author/{userId}/{isAdmin}', [BookController::class, 'getBooksByAuthor']);
    Route::get('book/search/{bookId}', [BookController::class, 'search']);


});
