<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\TechnicianController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/tasks-match', [TaskController::class, 'match']);

Route::get('{any}', function () {
    return view('welcome');  // The main Blade view where your Vue app will mount
})->where('any', '.*');