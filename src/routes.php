<?php

use Bigmom\Poll\Facades\Vote;
use Bigmom\Poll\Http\Controllers\Api\QuestionController as ApiQuestionController;
use Bigmom\Auth\Http\Middleware\EnsureUserIsAuthorized;
use Bigmom\Poll\Http\Controllers\AuthController;
use Bigmom\Poll\Http\Controllers\GroupController;
use Bigmom\Poll\Http\Controllers\QuestionController;
use Bigmom\Poll\Http\Controllers\ImportController;
use Bigmom\Poll\Http\Controllers\OptionController;
use Bigmom\Poll\Http\Controllers\VoteController;
use Bigmom\Poll\Managers\QuestionManager;
use Bigmom\Poll\Models\Question;
use Bigmom\Auth\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::prefix('/bigmom/poll')->name('bigmom-poll.')->middleware(['web'])->group(function () {
    Route::middleware([Authenticate::class, EnsureUserIsAuthorized::class.':poll-manage'])->group(function () {
        Route::get('/', function () {
            return redirect()
                ->route('bigmom-poll.question.getIndex');
        });
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

        Route::get('/debug', function () {
            $questions = (new QuestionManager)->getByGroup('question-all')
                ->filter(function ($value) {
                    return $value->is_ongoing;
                })->map(function ($value) {
                    return $value->token;
                })->all();
            return view('bigmom-poll::debug', compact('questions'));
        })->name('getDebug');
    });
});
