<?php

use App\Http\Controllers\Api\V1\RmaApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/rmas', [RmaApiController::class, 'index']);
    Route::post('/rmas', [RmaApiController::class, 'store']);
    Route::get('/rmas/{rma_number}', [RmaApiController::class, 'show']);
    Route::post('/rmas/{rma_number}/refund', [RmaApiController::class, 'processRefund']);
    Route::get('/modules', [RmaApiController::class, 'modules']);
    Route::post('/webhooks/incoming/{provider}', [RmaApiController::class, 'incomingWebhook']);
});
