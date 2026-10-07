<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Meta Webhook Endpoints
Route::prefix('meta')->group(function () {
    Route::get('/webhook', [WebhookController::class, 'verify']); // Verification URL
    Route::post('/webhook', [WebhookController::class, 'handle']); // Receiving Events
});

// Internal Dashboard APIs (Called from Frontend)
use App\Http\Controllers\DashboardApiController;

Route::prefix('internal')->group(function () {
    Route::post('/settings/business', [DashboardApiController::class, 'saveBusinessSettings']);
    Route::post('/ai/knowledge', [DashboardApiController::class, 'addKnowledge']);
    Route::put('/ai/knowledge/{knowledge}', [DashboardApiController::class, 'updateKnowledge']);
    Route::delete('/ai/knowledge/{knowledge}', [DashboardApiController::class, 'deleteKnowledge']);
    Route::post('/ai/faq', [DashboardApiController::class, 'addFaq']);
    Route::post('/ai/products', [DashboardApiController::class, 'addProduct']);
    Route::put('/ai/products/{product}', [DashboardApiController::class, 'updateProduct']);
    Route::post('/ai/chat/simulate', [DashboardApiController::class, 'simulateChat']);
});
