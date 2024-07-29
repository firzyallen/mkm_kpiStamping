<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DropdownController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RulesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PressController;
use App\Http\Controllers\MstPressController;
use App\Http\Controllers\MstFactoryBController;
use App\Http\Controllers\MstWeldingController;
use App\Http\Controllers\FormFactoryBController;
use App\Http\Controllers\FormWeldingController;
use App\Http\Controllers\FactoryBKPIController;


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

     //Press
     Route::get('daily-report/press', [PressController::class, 'index'])->middleware(['checkRole:IT']);
     Route::post('daily-report/press/store', [PressController::class, 'store'])->middleware(['checkRole:IT']);
     Route::patch('daily-report/press/update/{id}', [PressController::class, 'update'])->middleware(['checkRole:IT']);
     Route::delete('daily-report/press/delete/{id}', [PressController::class, 'delete'])->middleware(['checkRole:IT']);

     //KPI Monitoring
     Route::get('kpi-monitoring/factoryb', [FactoryBKPIController::class, 'index'])->middleware(['checkRole:IT']);
     //Factory B Form
     Route::get('/daily-report/factoryb', [FormFactoryBController::class, 'index'])->middleware(['checkRole:IT']);
     Route::post('/daily-report/factoryb/store/main', [FormFactoryBController::class, 'storeMain'])->middleware(['checkRole:IT']);
     Route::get('/daily-report/factoryb/form/{id}', [FormFactoryBController::class, 'formChecksheet'])->middleware(['checkRole:IT'])->name('form.daily-report.factoryb');
     Route::post('/daily-report/factoryb/detail/store', [FormFactoryBController::class, 'storeForm'])->middleware(['checkRole:IT']);
     Route::get('/daily-report/factoryb/detail/{id}', [FormFactoryBController::class, 'showDetail'])->middleware(['checkRole:IT']);
     Route::get('/daily-report/factoryb/update/{id}', [FormFactoryBController::class, 'updateDetail'])->middleware(['checkRole:IT']);
     Route::post('/daily-report/factoryb/detail/update', [FormFactoryBController::class, 'updateForm'])->middleware(['checkRole:IT']);

    //Welding Form
    Route::get('/daily-report/welding', [FormWeldingController::class, 'index'])->middleware(['checkRole:IT']);
    Route::post('/daily-report/welding/store/main', [FormWeldingController::class, 'storeMain'])->middleware(['checkRole:IT']);
    Route::get('/daily-report/welding/form/{id}', [FormWeldingController::class, 'formChecksheet'])->middleware(['checkRole:IT'])->name('form.daily-report.welding');
    Route::post('/daily-report/welding/detail/store', [FormWeldingController::class, 'storeForm'])->middleware(['checkRole:IT']);
    Route::get('/daily-report/welding/detail/{id}', [FormWeldingController::class, 'showDetail'])->middleware(['checkRole:IT']);
    Route::get('/daily-report/welding/update/{id}', [FormWeldingController::class, 'updateDetail'])->middleware(['checkRole:IT']);
    Route::post('/daily-report/welding/detail/update', [FormWeldingController::class, 'updateForm'])->middleware(['checkRole:IT']);

     //Master Press
     Route::get('press/shop', [MstPressController::class, 'shopview'])->middleware(['checkRole:IT']);

     //Master Factory B
     Route::get('factoryb/shop', [MstFactoryBController::class, 'index'])->middleware(['checkRole:IT']);
     Route::post('factoryb/shop/store', [MstFactoryBController::class, 'store'])->middleware(['checkRole:IT']);
     Route::patch('factoryb/shop/update', [MstFactoryBController::class, 'update'])->middleware(['checkRole:IT']);
     Route::get('factoryb/model', [MstFactoryBController::class, 'indexModel'])->middleware(['checkRole:IT']);
     Route::post('factoryb/model/store', [MstFactoryBController::class, 'storeModel'])->middleware(['checkRole:IT']);
     Route::patch('factoryb/model/update', [MstFactoryBController::class, 'updateModel'])->middleware(['checkRole:IT']);

     //Master Welding
     Route::get('welding/shop', [MstWeldingController::class, 'indexShop'])->middleware(['checkRole:IT']);
     Route::post('welding/shop/store', [MstWeldingController::class, 'storeShop'])->middleware(['checkRole:IT']);
     Route::patch('welding/shop/update', [MstWeldingController::class, 'updateShop'])->middleware(['checkRole:IT']);
     Route::get('welding/station', [MstWeldingController::class, 'indexStation'])->middleware(['checkRole:IT']);
     Route::post('welding/station/store', [MstWeldingController::class, 'storeStation'])->middleware(['checkRole:IT']);
     Route::patch('welding/station/update', [MstWeldingController::class, 'updateStation'])->middleware(['checkRole:IT']);
     Route::get('welding/model', [MstWeldingController::class, 'indexModel'])->middleware(['checkRole:IT']);
     Route::post('welding/model/store', [MstWeldingController::class, 'storeModel'])->middleware(['checkRole:IT']);
     Route::patch('welding/model/update', [MstWeldingController::class, 'updateModel'])->middleware(['checkRole:IT']);

});
