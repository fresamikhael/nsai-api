<?php

use App\Http\Controllers\API\AttendanceController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CheckInController;
use App\Http\Controllers\API\DistributorController;
use App\Http\Controllers\API\DocumentController;
use App\Http\Controllers\API\OutletController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\RegionController;
use App\Http\Controllers\API\VisitingController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::get('region', [RegionController::class, 'getRegion']);
Route::get('region/{id}', [RegionController::class, 'showRegion']);
Route::post('region/add', [RegionController::class, 'addRegion']);
Route::put('region/{id}', [RegionController::class, 'updateRegion']);
Route::delete('region/{id}', [RegionController::class, 'deleteRegion']);

Route::middleware('auth:sanctum')->group(function(){
    Route::get('user/{id}', [AuthController::class, 'getUser']);

    Route::get('distributor', [DistributorController::class, 'getAll']);
    Route::post('distributor', [DistributorController::class, 'addDistributor']);
    Route::delete('distributor/{id}', [DistributorController::class, 'deleteDistributor']);

    Route::get('product/{id}', [ProductController::class, 'getProduct']);
    Route::post('product', [ProductController::class, 'addProduct']);
    Route::delete('product/{id}', [ProductController::class, 'deleteProduct']);

    Route::get('outlet', [OutletController::class, 'getAll']);
    Route::post('outlet', [OutletController::class, 'addOutlet']);
    Route::delete('outlet/{id}', [OutletController::class, 'deleteOutlet']);

    Route::get('document', [DocumentController::class, 'getAll']);
    Route::post('document', [DocumentController::class, 'addDocument']);
    Route::delete('document/{id}', [DocumentController::class, 'deleteDocument']);

    Route::get('check-in/{id}', [CheckInController::class, 'getHistory']);
    Route::get('check-in/user/{id}', [CheckInController::class, 'getHistoryId']);
    Route::post('check-in/clock-in', [CheckInController::class, 'checkIn']);
    Route::put('check-in/clock-out/{id}', [CheckInController::class, 'checkOut']);

    Route::get('attendance/{id}', [AttendanceController::class, 'getHistory']);
    Route::get('attendance/user/{id}', [AttendanceController::class, 'getHistoryId']);
    Route::post('attendance/clock-in', [AttendanceController::class, 'clockIn']);
    Route::post('attendance/post-photo/{id}', [AttendanceController::class, 'postPhoto']);
    Route::put('attendance/clock-out/{id}', [AttendanceController::class, 'clockOut']);

    Route::get('visiting/{id}', [VisitingController::class, 'getHistory']);
    Route::post('visiting/clock-in', [VisitingController::class, 'clockIn']);
    Route::post('visiting/post-photo/{id}', [VisitingController::class, 'postPhoto']);
    Route::put('visiting/clock-out/{id}', [VisitingController::class, 'clockOut']);

    Route::post('logout', [AuthController::class, 'logout']);
});
