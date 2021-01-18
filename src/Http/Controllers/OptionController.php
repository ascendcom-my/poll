<?php

namespace Bigmom\Poll\Http\Controllers;

use Bigmom\Poll\Actions\Option\UpdateOption;
use Bigmom\Poll\Models\Option;
use Bigmom\Poll\Models\Question;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function getIndex(Question $question)
    {
        return view('poll::option.index', compact('question'));
    }

    public function postUpdate(Option $option, Request $request)
    {
        $input = $request->input();
        $input['option_id'] = $option->id;

        $status = (new UpdateOption)->run($input);

        return $status->getCode() == 0
            ? redirect()
                ->back()
                ->with('success', $status->getMessage())
            : redirect()
                ->back()
                ->withErrors('error', 'An error has occured.');
    }
}
