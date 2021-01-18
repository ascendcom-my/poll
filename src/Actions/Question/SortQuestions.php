<?php

namespace Bigmom\Poll\Actions\Question;

use Bigmom\Poll\Models\Question;
use Bigmom\Poll\Objects\Status;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SortQuestions
{
    protected $questions = [];

    public function run(array $input): Status
    {
        $this->validate($input);

        foreach ($this->questions as $index => $question) {
            $question->sequence = $index;
            $question->save();
        }

        return new Status(0, 'Questions successfully sorted.');
    }

    public function validate(array $input)
    {
        Validator::make($input, [
            'group_id' => 'required|string|max:191',
            'sorted' => 'required|array|min:1',
            'sorted.*' => 'required|exists:questions,token',
        ])->validate();

        $questions = [];

        foreach ($input['sorted'] as $questionToken) {
            $question = Question::where('token', $questionToken)->first();

            if ($question->group_id != $input['group_id']) {
                throw new ValidationException(Validator::make([], []), response((new Status(1, 'Questions do not belong to group.'))->all(), 406));
            }
            array_push($questions, $question);
        }

        $this->questions = $questions;
    }
}
