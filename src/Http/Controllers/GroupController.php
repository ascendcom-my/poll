<?php

namespace Bigmom\Poll\Http\Controllers;

use Bigmom\Poll\Actions\Question\SortQuestions;
use Bigmom\Poll\Models\Question;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function getIndex(string $group)
    {
        $questions = Question::where('group_id', rawurldecode($group))
            ->orderBy('sequence', 'asc')
            ->get();
        return view('poll::group.index', compact('questions', 'group'));
    }

    public function postSort(string $group, Request $request)
    {
        $input = $request->input();
        $input['group_id'] = $group;
        $status = (new SortQuestions)->run($input);

        return response()->json($status->all());
    }
}
