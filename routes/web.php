<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use PragmaRX\Google2FALaravel\Google2FA;
use PragmaRX\Google2FA\Support\QRCode;
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

Route::get('/qr',  [App\Http\Controllers\UserController::class, 'getQrCode']);


