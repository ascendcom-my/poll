<?php

namespace Bigmom\Poll\Concerns;

use Bigmom\Poll\Models\Vote;

trait CanVote
{
    public function votes()
    {
        return $this->morphMany(Vote::class, 'voter');
    }
}
