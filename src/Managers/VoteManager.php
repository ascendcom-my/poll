<?php

namespace Bigmom\Poll\Managers;

use Bigmom\Poll\Actions\Vote\CastVote;
use Bigmom\Poll\Models\Vote;
use Bigmom\Poll\Models\Question;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class VoteManager
{
    public function cast($option, Model $voter, $weight = 1, $vote_at = null)
    {
        $voter_type = get_class($voter);
        $voter_snapshot = $voter->toJson();
        $voter_id = $voter->id;
        if ($voter_id === null) {
            throw new ValidationException(Validator::make([], []), response((new Status(1, "Voter ID not found."))->all(), 500));
        }

        if (strtotime($vote_at) === false) {
            $vote_at = Carbon::now()->format('Y-m-d H:i:s');
        }

        return (new CastVote)->run(compact('option', 'voter_id', 'voter_type', 'weight', 'voter_snapshot', 'vote_at'));
    }

    public function checkVoted(Model $voter, Question $question)
    {
        return Cache::rememberForever($this->resolveVoterQuestionCacheKey($voter, $question), function () use ($voter, $question) {
            return Vote::where('voter_id', $voter->id)
                ->where('voter_type', get_class($voter))
                ->where('question_id', $question->id)
                ->exists() 
                ?: null;
        });
    }

    public function resolveVoterQuestionCacheKey(Model $voter, Question $question)
    {
        return "{$voter->id}-{$question->token}";
    }
}
