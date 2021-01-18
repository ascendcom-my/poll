<?php

namespace Bigmom\Poll\Actions\Question;

use Bigmom\Poll\Models\Option;
use Bigmom\Poll\Models\Question;
use Bigmom\Poll\Objects\Status;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CreateQuestion
{
    public function run(array $input): Status
    {
        $this->validate($input);

        $questionId = DB::transaction(function () use ($input) {
            $question = new Question;
            $question->title = $input['title'];
            $question->type = $input['type'];
            $question->is_ballot = isset($input['is_ballot']) ? $input['is_ballot'] : false;
            $question->allow_abstain = isset($input['allow_abstain']) ? $input['allow_abstain'] : false;
            $question->start_at = isset($input['start_at']) ? $input['start_at'] : null;
            $question->stop_at = isset($input['stop_at']) ? $input['stop_at'] : null;
            $question->allow_reveal_result = isset($input['allow_reveal_result']) ? $input['allow_reveal_result'] : false;
            $question->min = $input['min'];
            $question->max = $input['max'];
            $question->group_id = isset($input['group_id']) ? $input['group_id'] : config('poll.default-group', 'question-all');
            $question->sequence = Question::where('group_id', $question->group_id)->count();
            do {
                $question->token = Str::orderedUuid();
            } while (Question::where('token', $question->token)->exists());
            $question->save();

            foreach ($input['options'] as $index => $text) {
                $option = new Option;
                $option->text = $text;
                $option->question_id = $question->id;
                $option->cache_count = 0;
                $option->token = $question->token . str_pad($index, 10, "0", STR_PAD_LEFT);
                $option->save();
            }

            if ($question->type == 0 && $question->allow_abstain) {
                $option = new Option;
                $option->text = isset($input['abstain_text']) ? $input['abstain_text'] : 'Abstain';
                $option->question_id = $question->id;
                $option->cache_count = 0;
                $option->token = $question->token . 'ABSTAIN';
                $option->save();
            }

            return $question->id;
        });

        return $questionId
            ? new Status(0, 'Question successfully created.')
            : new Status(1, 'An error occured.');
    }

    public function validate(array $input)
    {
        if (isset($input['allow_abstain'])) {
            $abstainRules = ['boolean'];
        } else {
            $abstainRules = ['nullable'];
        }
        if (isset($input['stop_at'])) {
            $startAtRules = ['required', 'date'];
        } else {
            $startAtRules = ['nullable', 'date'];
        }
        Validator::make($input, [
            'title' => ['required', 'string', 'max:191'],
            'type' => ['required', Rule::in(array_keys(Question::TYPE))],
            'is_ballot' => ['nullable', 'boolean'],
            'allow_abstain' => $abstainRules,
            'abstain_text' => ['nullable', 'string', 'max:191'],
            'start_at' => $startAtRules,
            'stop_at' => ['nullable', 'date', 'after:start_at'],
            'allow_reveal_result' => ['nullable', 'boolean'],
            'min' => ['required', 'integer', 'min:1', 'max:' . (count($input['options']) - 1)],
            'max' => ['required', 'integer', 'min:1', 'gte:min', 'max:' . count($input['options'])],
            'group_id' => ['nullable', 'string', 'max:191'],
            'options' => ['required', 'array'],
            'options.*' => ['required', 'string', 'max:191'],
        ])->validate();
    }
}
