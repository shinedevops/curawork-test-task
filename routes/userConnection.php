<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\SentRequestController;
use App\Http\Controllers\ReceivedRequestController;
use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\HomeController;


// user connection routes
Route::get("list-suggestions", [SuggestionController::class, "index"])->name('suggestions');
Route::post("send-request", [SuggestionController::class, "create"])->name('send-request');
Route::get("list-sent-requests", [SentRequestController::class, "index"])->name('list-sent-requests');
Route::get("withdraw-request/{id}", [SentRequestController::class, "destroy"])->name('withdraw-request');
Route::get("list-received-requests", [ReceivedRequestController::class, "index"])->name('list-received-requests');
Route::get("accept-request/{id}", [ReceivedRequestController::class, "update"])->name('accept-request');
Route::get("connections", [ConnectionController::class, "index"])->name('connections');
Route::get("get-all-count", [HomeController::class, "index"])->name('get-all-count');
 