<?php

namespace Bigmom\Poll\Http\Resources;

use Bigmom\Poll\Facades\Vote;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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
        ];

        if ($this->allow_reveal_result) {
            $returnedArray['options'] = OptionWithVoteCountResource::collection($this->options);
        } else {
            $returnedArray['options'] = OptionNoVoteCountResource::collection($this->options);
        }

        $user = Auth::guard(config('poll.voter-guard'))->user();
        if ($user) {
            $returnedArray['has_voted'] = Vote::checkVoted($user, $this->resource) ?: false;
        }

        return $returnedArray;
    }
}
