<?php

namespace Bigmom\Poll\Actions\Vote;

use App\Jobs\Poll\RecordVote;
use Bigmom\Poll\Facades\Vote as VoteManager;
use Bigmom\Poll\Models\Option;
use Bigmom\Poll\Objects\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CastVote
{
    protected $options;

    public function run(array $input)
    {
        $this->validate($input);

        $input['options'] = $this->options;

        if (config('poll.record-vote.use-queue', false)) {
            RecordVote::dispatch($input)->onQueue(config('poll.record-vote.queue'), 'default');
        } else {
            RecordVote::dispatchSync($input);
        }

        return $this->options;
    }

    public function validate(array $input)
    {
        $validator = Validator::make($input, [
            'option' => ['required', 'array', 'min:1'],
            'option.*' => ['required', 'exists:options,token'],
            'voter_type' => ['required', 'string', 'max:191'],
            'weight' => ['required', 'integer'],
            'voter_snapshot' => ['nullable', 'json', 'max:191'],
            'vote_at' => ['required', 'date'],
        ]);

        $validator->validate();

        if (!class_exists($input['voter_type'])) {
            throw new ValidationException($validator, response((new Status(1, "Voter type does not exist."))->all(), 500));
        } else if (!(new $input['voter_type']) instanceof Model) {
            throw new ValidationException($validator, response((new Status(1, "Voter type is not a model instance."))->all(), 500));
        }

        Validator::make($input, [
            'voter_id' => ['required', 'exists:' . (new $input['voter_type'])->getTable()  . ',id'],
        ])->validate();

        $options = [];
        $hasAbstain = false;
        foreach ($input['option'] as $optionToken) {
            if (!$optionToken instanceof Option) {
                $option = Option::where('token', $optionToken)->first();
            }
            array_push($options, $option);
            if ($option->question_id != $options[0]->question_id) {
                throw new ValidationException($validator, response((new Status(1, "Options belong to different questions."))->all(), 406));
            }
            if ($option->isAbstain) {
                $hasAbstain = true;
            }
        }

        if ($hasAbstain && count($options) != 1) {
            throw new ValidationException($validator, response((new Status(1, "Has abstain with other options."))->all(), 406));
        }

        $this->options = $options;
        $question = $options[0]->question;

        if (!$question->is_ongoing) {
            throw new ValidationException($validator, response((new Status(1, 'Question is not currently votable.'))->all(), 406));
        }

        if (VoteManager::checkVoted($input['voter_type']::find($input['voter_id']), $question)) {
            throw new ValidationException($validator, response((new Status(1, "Voter has already voted."))->all(), 406));
        }

        if (collect($input['option'])->duplicates()->isNotEmpty()) {
            throw new ValidationException($validator, response((new Status(1, "Multiple votes for the same option."))->all(), 406));
        }

        if (count($input['option']) < $question->min) {
            throw new ValidationException($validator, response((new Status(1, "At least {$question->min} options needed."))->all(), 406));
        }

        if (count($input['option']) > $question->max) {
            throw new ValidationException($validator, response((new Status(1, "At most {$question->max} options allowed."))->all(), 406));
        }
    }
}
