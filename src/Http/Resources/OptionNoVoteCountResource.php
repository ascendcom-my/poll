<?php

namespace Bigmom\Poll\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OptionNoVoteCountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'text' => $this->text,
            'token' => $this->token,
        ];
    }
}
