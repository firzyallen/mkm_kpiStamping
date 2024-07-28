<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DropdownController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RulesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PressController;

use App\Http\Controllers\MstPressController;
use App\Http\Controllers\MstDowntimeController;
use App\Http\Controllers\DowntimeFormController;



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

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/auth/login', [AuthController::class, 'postLogin']);
Route::get('/logout', [AuthController::class, 'logout']);

Route::middleware(['auth'])->group(function () {
    //Home Controller
    Route::get('/home', [HomeController::class, 'index'])->name('home');


    //Dropdown Controller
    Route::get('/dropdown', [DropdownController::class, 'index'])->middleware(['checkRole:IT']);
    Route::post('/dropdown/store', [DropdownController::class, 'store'])->middleware(['checkRole:IT']);
    Route::patch('/dropdown/update/{id}', [DropdownController::class, 'update'])->middleware(['checkRole:IT']);
    Route::delete('/dropdown/delete/{id}', [DropdownController::class, 'delete'])->middleware(['checkRole:IT']);

    //Rules Controller
    Route::get('/rule', [RulesController::class, 'index'])->middleware(['checkRole:IT']);
    Route::post('/rule/store', [RulesController::class, 'store'])->middleware(['checkRole:IT']);
    Route::patch('/rule/update/{id}', [RulesController::class, 'update'])->middleware(['checkRole:IT']);
    Route::delete('/rule/delete/{id}', [RulesController::class, 'delete'])->middleware(['checkRole:IT']);

    //User Controller
    Route::get('/user', [UserController::class, 'index'])->middleware(['checkRole:IT']);
    Route::post('/user/store', [UserController::class, 'store'])->middleware(['checkRole:IT']);
    Route::post('/user/store-partner', [UserController::class, 'storePartner'])->middleware(['checkRole:IT']);
    Route::patch('/user/update/{user}', [UserController::class, 'update'])->middleware(['checkRole:IT']);
    Route::get('/user/revoke/{user}', [UserController::class, 'revoke'])->middleware(['checkRole:IT']);
    Route::get('/user/access/{user}', [UserController::class, 'access'])->middleware(['checkRole:IT']);

    //Daily Report Press
    Route::get('daily-report/press', [PressController::class, 'index'])->middleware(['checkRole:IT']);
    Route::post('daily-report/press/store', [PressController::class, 'store'])->middleware(['checkRole:IT']);
    Route::patch('daily-report/press/update/{id}', [PressController::class, 'update'])->middleware(['checkRole:IT']);
    Route::delete('daily-report/press/delete/{id}', [PressController::class, 'delete'])->middleware(['checkRole:IT']);

    //Daily Report Press


    //Daily Report Factory B


    //Daily Report Welding

    //DowntimeFormController Controller
    // Downtime Form Controller
    Route::get('/downtime-report', [DowntimeFormController::class, 'index']);
    Route::post('/downtime-report/store-header', [DowntimeFormController::class, 'storeHeader']);
    Route::get('/downtime-report/form/{id}', [DowntimeFormController::class, 'formDowntime']);
    Route::post('/downtime-report/store-details', [DowntimeFormController::class, 'storeForm']);
    Route::get('/downtime-report/show/{id}', [DowntimeFormController::class, 'showDetail']);
    Route::get('/downtime-report/update/{id}', [DowntimeFormController::class, 'updateDetail']);
    Route::post('/downtime-report/update/{id}', [DowntimeFormController::class, 'updateForm']);

    // MstPressController Controller
    // Routes for PressMstShop
    Route::get('/masterpress/shop', [MstPressController::class, 'indexShop'])->middleware(['checkRole:IT']);
    Route::post('/masterpress/shop/store', [MstPressController::class, 'storeShop'])->middleware(['checkRole:IT']);
    Route::post('/masterpress/shop/update', [MstPressController::class, 'updateShop'])->middleware(['checkRole:IT']);
    // Routes for PressMstModel
    Route::get('/masterpress/model', [MstPressController::class, 'indexModel'])->middleware(['checkRole:IT']);
    Route::post('/masterpress/model/store', [MstPressController::class, 'storeModel'])->middleware(['checkRole:IT']);
    Route::put('/masterpress/model/update', [MstPressController::class, 'updateModel'])->middleware(['checkRole:IT']);

    //MstDowntimeController Controller
    Route::middleware(['checkRole:IT'])->group(function () {
        Route::get('/masterdowntime', [MstDowntimeController::class, 'index']);
        Route::post('/masterdowntime/store', [MstDowntimeController::class, 'storeMachine']);
        Route::patch('/masterdowntime/update', [MstDowntimeController::class, 'updateMachine'])->name('masterdowntime.update');
    });

    //Master Factory B 


    //Master Welding


});
