<?php

namespace Bigmom\Poll\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function getIsAbstainAttribute()
    {
        return substr($this->token, -7) == 'ABSTAIN';
    }

    public function cacheCount()
    {
        return $this->cache_count = $this->votes()->sum('weight');
    }

    public function deleteChildren()
    {
        foreach ($this->votes as $vote) {
            $vote->delete();
        }

        return $this;
    }
}
