<?php

namespace Bigmom\Poll\Actions\Question;

use Bigmom\Poll\Models\Question;
use Bigmom\Poll\Objects\Status;
use Exception;
use Illuminate\Support\Facades\Validator;

class UpdateQuestion
{
    protected $question;

    public function __construct(Question $question)
    {
        $this->question = $question;
    }

    public function run(array $input): Status
    {
        $this->validate($input);

        $input = collect($input);

        $this->question->title = $input['title'];
        $this->question->allow_reveal_result = $input->has('allow_reveal_result');
        if ($input->has('start_at')) {
            $this->question->start_at = $input->get('start_at');
        }
        if ($input->has('stop_at')) {
            $this->question->stop_at = $input->get('stop_at');
        }

        if ($input->get('group_id')) {
            if ($this->question->group_id != $input->get('group_id')) {
                $this->question->group_id = $input->get('group_id');
            }
        } else if ($this->question->group_id != config('poll.default-group', 'question-all')) {
            $this->question->group_id = config('poll.default-group', 'question-all');
        }

        $this->question->save();

        return new Status(0, 'Question successfully updated.');
    }

    public function validate(array $input)
    {
        // start at must be required if stop at exists in input
        if (isset($input['stop_at'])) {
            $startAtRules = ['required', 'date'];
        } else {
            $startAtRules = ['nullable', 'date'];
        }
        Validator::make($input, [
            'title' => ['required', 'string', 'max:191'],
            'start_at' => $startAtRules,
            'stop_at' => ['nullable', 'date', 'after:start_at'],
            'allow_reveal_result' => ['nullable', 'boolean'],
            'group_id' => ['nullable', 'string', 'max:191'],
        ])->validate();
    }
}
