<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController; // {{ edit_1 }}
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\NotifikasiController;
use App\Events\MessageCreated;
use App\Http\Controllers\AuthLoginController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\NetworkController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\SettingsController;

Route::get('/', [AuthLoginController::class, 'index'])->name('auth.login');
Route::post('/login', [AuthLoginController::class, 'proses_login'])->name('auth.proses_login');
Route::get('/logout', [AuthLoginController::class, 'logout'])->name('auth.logout');

Route::post('/savename', [DashboardController::class, 'updateInterfaceName'])->name('dashboard.save_name')->middleware('check.login');
// Dashboard hanya bisa diakses jika login
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.dashboard')->middleware('check.login');
Route::get('/fetch-all-data', [DashboardController::class, 'fetchAllData'])->middleware('check.login');
Route::get('/fetch-interfaces', [DashboardController::class, 'fetchInterfaces'])->middleware('check.login');

// QoS hanya bisa diakses jika login
Route::get('/simple_queue', [ServicesController::class, 'simple_queue'])->name('qos.simple_queue')->middleware('check.login');
Route::post('/delete/simple_queue', [ServicesController::class, 'deleteQueueConfiguration'])->name('qos.delete_simple_queue')->middleware('check.login');
Route::post('/save_simple_queue', [ServicesController::class, 'saveQueueConfiguration'])->name('qos.save_simple_queue')->middleware('check.login');


Route::get('/queue_type', [ServicesController::class, 'queue_type'])->name('qos.queue_type')->middleware('check.login');
Route::post('/save_queue_type', [ServicesController::class, 'saveQueueType'])->name('qos.save_queue_type')->middleware('check.login');
Route::post('/delete_queue_type', [ServicesController::class, 'deleteQueueType'])->name('qos.delete_queue_type')->middleware('check.login');

Route::get('/address', [NetworkController::class, 'ipAddress'])->name('network.address')->middleware('check.login');
Route::post('/save_address', [NetworkController::class, 'saveAddress'])->name('network.save_address')->middleware('check.login');
Route::post('/delete_address', [NetworkController::class, 'deleteAddress'])->name('network.delete_address')->middleware('check.login');


Route::get('/clientList', [NetworkController::class, 'clientList'])->name('network.clientList')->middleware('check.login');
Route::get('/fetch-client-data', [NetworkController::class, 'fetchClientData'])->name('network.fetch_client_data')->middleware('check.login');
Route::post('/delete_clientList', [NetworkController::class, 'deleteClient'])->name('network.delete_clientList')->middleware('check.login');



Route::get('/json/{interfaceId}', [DataController::class, 'data'])->name('qos.json');
