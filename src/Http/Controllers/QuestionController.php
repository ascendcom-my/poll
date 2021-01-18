<?php

namespace Bigmom\Poll\Http\Controllers;

use Bigmom\Poll\Actions\Question\CreateQuestion;
use Bigmom\Poll\Actions\Question\UpdateQuestion;
use Bigmom\Poll\Http\Resources\QuestionResource;
use Bigmom\Poll\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function getIndex()
    {
        return view('poll::question.index', [
            'questions' => Question::paginate(10)->withQueryString(),
            'questionTypes' => Question::TYPE,
        ]);
    }

    public function postCreate(Request $request)
    {
        $input = $request->input();
        if (isset($input['options']) && is_string($input['options'])) {
            $input['options'] = explode("||", $input['options']);
        } else {
            return back()
                ->withInput()
                ->withErrors('options', 'Options not provided.');
        }
        $status = (new CreateQuestion)->run($input);

        return $status->getCode() == 0
            ? redirect()
                ->back()
                ->with('success', $status->getMessage())
            : redirect()
                ->back()
                ->withErrors('error', $status->getMessage());
    }

    public function postUpdate(Question $question, Request $request)
    {
        $input = $request->input();
        
        $status = (new UpdateQuestion($question))->run($input);

        return $status->getCode() == 0
            ? redirect()
                ->back()
                ->with('success', $status->getMessage())
            : redirect()
                ->back()
                ->withErrors('error', $status->getMessage());
    }

    public function postDelete(Question $question)
    {
        $question->deleteChildren()->delete();

        return redirect()
            ->back()
            ->with('success', 'Question and all children successfully deleted.');
    }

    public function getJsonQuestions(Request $request)
    {
        $questions = $request->input('questions');

        return response()->json([
            'questions' => QuestionResource::collection(Question::whereIn('token', $questions)
                ->with('options')
                ->orderBy('sequence')
                ->get()
                ->filter(function($value) {
                    return $value->is_ongoing;
                })),
        ]);
    }
}
