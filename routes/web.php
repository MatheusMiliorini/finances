<?php

use App\Services\AccountService;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function (AccountService $accountService) {
    return Inertia::render('Dashboard', [
        'accounts' => $accountService->list(),
    ]);
});
