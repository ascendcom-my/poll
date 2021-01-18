<?php

trait HasVotes
{
    public function votes()
    {
        return $this->morphMany(\Bigmom\Poll\Models\Vote::class, 'voter');
    }
}
