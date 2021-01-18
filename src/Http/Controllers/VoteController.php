<?php

namespace Bigmom\Poll\Http\Controllers;

use Bigmom\Poll\Facades\Vote;
use Bigmom\Poll\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    public function castVote(Request $request)
    {
        $options = Vote::cast(
            $request->input('option'),
            Auth::guard(config('poll.voter-guard'))->user(),
            1
        );

        $result = [];
        if ($options[0]->question->allow_reveal_result) {
            foreach ($options as $option) {
                $result[$option->token] = $option->cache_count;
            }
        }

        return response()->json(['result' => $result]);
    }

    public function checkVoted(Request $request)
    {
        return Vote::checkVoted(
            Auth::guard(config('poll.voter-guard')),
            Question::where('token', $request->input('question'))->firstOrFail()
        );
    }
}
