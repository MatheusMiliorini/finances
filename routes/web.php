<?php

use App\Services\AccountService;
use Illuminate\Http\Request;
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
})->name('dashboard.index');

Route::post('/account', function (Request $request, AccountService $accountService) {
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'balance' => ['required', 'integer'],
    ]);
    $account = $accountService->store([
        'name' => $request->name,
        'startBalance' => $request->balance,
        'currentBalance' => $request->balance,
    ]);
    return to_route('dashboard.index');
});
