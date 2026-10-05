<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SocialInboxController;
use App\Http\Controllers\CRMController;

// Dashboard
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

// Social Inbox
Route::prefix('inbox')->name('inbox.')->group(function () {
    Route::get('/messenger', [SocialInboxController::class, 'messenger'])->name('messenger');
    Route::get('/comments', [SocialInboxController::class, 'comments'])->name('comments');
    Route::get('/conversations', [SocialInboxController::class, 'conversations'])->name('conversations');
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
    Route::get('/new', function () { return view('leads.new'); })->name('new');
    Route::get('/hot', [CRMController::class, 'hot'])->name('hot');
    Route::get('/follow-up', function () { return view('leads.follow-up'); })->name('follow-up');
    Route::get('/pipeline', [CRMController::class, 'pipeline'])->name('pipeline');
});

// Settings
Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/business', function () { return view('settings.business'); })->name('business');
    Route::get('/facebook', function () { return view('settings.facebook'); })->name('facebook');
    Route::get('/instagram', function () { return view('settings.instagram'); })->name('instagram');
    Route::get('/whatsapp', function () { return view('settings.whatsapp'); })->name('whatsapp');
    Route::get('/general', function () { return view('settings.general'); })->name('general');
});
