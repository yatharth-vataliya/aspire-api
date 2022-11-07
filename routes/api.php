<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LoanController;
use App\Http\Controllers\Api\AdminController;
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

Route::post("/register", [RegisterController::class, "register"])->name("register");
Route::post("/login", [LoginController::class, "login"])->name("login");

Route::group(["middleware" => ["auth:sanctum", "abilities:access-customer"]], function () {

    Route::get("/user", fn(Request $request) => $request->user())->name("get-user");

    Route::controller(LoanController::class)->group(function () {
        Route::post("/create-loan", "store")->name("loan.store");
        Route::get("/current-user-loans", "getCurrentUserLoan")->name("loan.current-user-loans");
    });
});

Route::post("/admin/login", [AdminController::class, "login"])->name("admin.login");
Route::group(["middleware" => ["auth:admin"], "prefix" => "admin"], function () {
    Route::controller(AdminController::class)->group(function() {
        Route::get("/current-admin", fn(Request $request) => $request->user())->name("admin.get-user");
    });
    Route::post("/approve-loan/{loan}", [LoanController::class, "approveLoan"])->name("loan.approve");
});
