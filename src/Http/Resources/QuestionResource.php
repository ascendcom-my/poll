<?php

namespace Bigmom\Poll\Http\Resources;

use Bigmom\Poll\Facades\Vote;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $returnedArray = [
            'title' => $this->title,
            'type' => $this->type,
            'allow_abstain' => $this->allow_abstain,
            'min' => $this->min,
            'max' => $this->max,
            'token' => $this->token,
            'input_type' => $this->input_type,
            'voter_count' => $this->voter_count,
        ];

        if ($this->allow_reveal_result) {
            $returnedArray['options'] = OptionWithVoteCountResource::collection($this->options);
        } else {
            $returnedArray['options'] = OptionNoVoteCountResource::collection($this->options);
        }

        $user = Auth::guard(config('poll.voter-guard'))->user();
        if ($user) {
            $returnedArray['has_voted'] = Vote::checkVoted($user, $this->resource) ?: false;
            $returnedArray['votes'] = Cache::rememberForever($user->id . '-' . $this->id . '-voted_options', function () use ($user) {
                $votes = $user->votes()
                    ->where('question_id', $this->id)
                    ->with('option')
                    ->get()
                    ->pluck('option.token');
                if (count($votes) === 0) {
                    return null;
                } else {
                    return $votes;
                }
            });
            if ($returnedArray['votes'] === null) {
                $returnedArray['votes'] = [];
            }
        }

        return $returnedArray;
    }
}
