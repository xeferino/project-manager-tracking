<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Redirect;

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
    return redirect('/login');
});
Route::get('/login', function () {
    return view('auth.login');
})->name('auth.login');
Route::post('login', 'LoginController@login')->name('login');
Route::post('logout', 'LoginController@logout')->name('logout');
Route::get('dashboard', 'DashboardController@index')->middleware('auth')->name('dashboard');
Route::resource('users', 'UserController')->middleware('auth');
Route::resource('departments', 'DepartmentController')->middleware('auth');
Route::resource('processes', 'ProcessController')->middleware('auth');
Route::post('processes/delete', 'ProcessController@delete')->middleware('auth')->name('processes.delete');
Route::resource('projects', 'ProjectController')->middleware('auth');
Route::post('projects/annexes', 'ProjectController@showAnnexed')->middleware('auth')->name('projects.annexed');
Route::get('projects/binnacle/{project}', 'ProjectController@Binnacle')->middleware('auth')->name('projects.binnacle');
Route::get('projects/annexes/{project}', 'ProjectController@Annexed')->middleware('auth')->name('projects.show.annexed');
Route::get('projects/download/{project}', 'ProjectController@downloadAnnexed')->middleware('auth')->name('projects.download.annexed');
Route::resource('wordflows', 'WordflowController')->middleware('auth');
Route::post('wordflows/steps', 'WordflowController@step')->middleware('auth')->name('wordflow.steps');
