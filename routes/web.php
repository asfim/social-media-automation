<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SocialInboxController;
use App\Http\Controllers\CRMController;
use App\Http\Controllers\MetaSettingsController;

// Dashboard
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

// Social Inbox
Route::prefix('inbox')->name('inbox.')->group(function () {
    Route::get('/messenger', [SocialInboxController::class, 'messenger'])->name('messenger');
    Route::get('/comments', [SocialInboxController::class, 'comments'])->name('comments');
    Route::get('/conversations', [SocialInboxController::class, 'conversations'])->name('conversations');
    Route::post('/conversations/{id}/send', [SocialInboxController::class, 'sendMessage'])->name('send');
    Route::post('/conversations/{id}/toggle-ai', [SocialInboxController::class, 'toggleAi'])->name('toggle-ai');
});

// Automation
Route::prefix('automation')->name('automation.')->group(function () {
    Route::get('/auto-reply', function () { return view('automation.auto-reply'); })->name('auto-reply');
    Route::get('/auto-comment', function () { return view('automation.auto-comment'); })->name('auto-comment');
    Route::get('/keyword-rules', function () { return view('automation.keyword-rules'); })->name('keyword-rules');
    Route::get('/ai-rules', function () { return view('automation.ai-rules'); })->name('ai-rules');
    Route::get('/spam', function () { return view('automation.spam'); })->name('spam');
});

// AI Assistant
Route::prefix('ai')->name('ai.')->group(function () {
    Route::get('/chat', function () { return view('ai.chat'); })->name('chat');
    Route::get('/knowledge', function () { return view('ai.knowledge'); })->name('knowledge');
    Route::get('/faq', function () { return view('ai.faq'); })->name('faq');
    Route::get('/products-services', function () { return view('ai.products'); })->name('products');
    Route::get('/settings', function () { return view('ai.settings'); })->name('settings');
});

// CRM Leads
Route::prefix('leads')->name('leads.')->group(function () {
    Route::get('/', [CRMController::class, 'all'])->name('all');
    Route::get('/new', [CRMController::class, 'newLeads'])->name('new');
    Route::get('/hot', [CRMController::class, 'hot'])->name('hot');
    Route::get('/follow-up', [CRMController::class, 'followUp'])->name('follow-up');
    Route::get('/pipeline', [CRMController::class, 'pipeline'])->name('pipeline');
    Route::post('/', [CRMController::class, 'store'])->name('store');
});

// Settings
Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/business', function () { return view('settings.business'); })->name('business');
    Route::get('/facebook', [MetaSettingsController::class, 'facebook'])->name('facebook');
    Route::post('/facebook', [MetaSettingsController::class, 'saveFacebook'])->name('facebook.save');
    Route::post('/facebook/disconnect', [MetaSettingsController::class, 'disconnectFacebook'])->name('facebook.disconnect');
    Route::get('/instagram', function () { return view('settings.instagram'); })->name('instagram');
    Route::get('/whatsapp', function () { return view('settings.whatsapp'); })->name('whatsapp');
    Route::get('/general', function () { return view('settings.general'); })->name('general');
});
