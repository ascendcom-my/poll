<?php

use Bigmom\Poll\Facades\Vote;
use Bigmom\Poll\Http\Controllers\Api\QuestionController as ApiQuestionController;
use Bigmom\Poll\Http\Middleware\EnsureUserIsAuthorized;
use Bigmom\Poll\Http\Controllers\AuthController;
use Bigmom\Poll\Http\Controllers\GroupController;
use Bigmom\Poll\Http\Controllers\QuestionController;
use Bigmom\Poll\Http\Controllers\ImportController;
use Bigmom\Poll\Http\Controllers\OptionController;
use Bigmom\Poll\Http\Controllers\VoteController;
use Bigmom\Poll\Managers\QuestionManager;
use Bigmom\Poll\Models\Question;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::prefix('/poll')->name('poll.')->middleware(['web'])->group(function () {
    Route::middleware([\Bigmom\Poll\Http\Middleware\RedirectIfAuthenticated::class])->group(function () {
        Route::get('/', [AuthController::class, 'getLogin']);
        Route::get('/login', [AuthController::class, 'getLogin'])->name('getLogin');
        Route::post('/login', [AuthController::class, 'postLogin'])->name('postLogin');
    });
    Route::middleware([\Bigmom\Poll\Http\Middleware\Authenticate::class, EnsureUserIsAuthorized::class])->group(function () {
        Route::prefix('/question')->name('question.')->group(function () {
            Route::get('/', [QuestionController::class, 'getIndex'])->name('getIndex');
            Route::post('/create', [QuestionController::class, 'postCreate'])->name('postCreate');
            Route::post('/{question}/update', [QuestionController::class, 'postUpdate'])->name('postUpdate');
            Route::post('/{question}/delete', [QuestionController::class, 'postDelete'])->name('postDelete');
            Route::get('/import', [ImportController::class, 'getImport'])->name('getImport');
            Route::post('/import', [ImportController::class, 'postImport'])->name('postImport');
            Route::get('/download', [ImportController::class, 'downloadExport'])->name('downloadExport');
            Route::get('/template/download', [ImportController::class, 'downloadTemplate'])->name('downloadTemplate');
            Route::get('/json', [QuestionController::class, 'getJsonQuestions'])->name('getJsonQuestions');
            Route::prefix('{question}/option')->name('option.')->group(function () {
                Route::get('/', [OptionController::class, 'getIndex'])->name('getIndex');
            });
        });
        Route::prefix('/option/{option}')->name('option.')->group(function () {
            Route::post('/update', [OptionController::class, 'postUpdate'])->name('postUpdate');
        });
        Route::prefix('/group/{group}')->name('group.')->group(function () {
            Route::get('/', [GroupController::class, 'getIndex'])->name('getIndex');
            Route::post('/sort', [GroupController::class, 'postSort'])->name('postSort');
        });
        Route::prefix('/vote')->name('vote.')->group(function () {
            Route::post('/', [VoteController::class, 'castVote'])->name('castVote');
        });
        Route::post('/logout', [AuthController::class, 'postLogout'])->name('postLogout');

        Route::get('/debug', function () {
            $questions = (new QuestionManager)->getByGroup('question-all')
                ->filter(function ($value) {
                    return $value->is_ongoing;
                })->map(function ($value) {
                    return $value->token;
                })->all();
            return view('poll::debug', compact('questions'));
        });
    });
});
