<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
//Route::post('/comments', [CommentController::class, 'store'])->name('add_comment')->middleware('auth');

Route::resource('posts', PostController::class);
Route::resource('tags', TagController::class);
Route::post('/posts/{post}/restore', [PostController::class, 'restore'])->name('posts.restore')->middleware('auth');
Route::delete('/posts/{post}/force_destroy', [PostController::class, 'forceDestroy'])->name('posts.force_destroy')->middleware('auth');
Route::post('/posts/{post}/add_comment', [PostController::class, 'addComment'])->name('posts.add_comment')->middleware('auth');

Route::get('/greeting/{locale}', function ($locale) {
    if (! in_array($locale, ['en', 'ar', 'fr'])) {
        abort(400);
    }
    //Cookie::queue('locale', $locale, 60*24*30);
    $cookie = cookie('locale', $locale, 60*24*30);
    return redirect()->back()->withCookie($cookie);
})->name('locale');