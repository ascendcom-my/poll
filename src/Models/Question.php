<?php

namespace Bigmom\Poll\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $casts = [
        'start_at' => 'datetime:Y-m-d H:i',
        'stop_at' => 'datetime:Y-m-d H:i',
    ];

    public const TYPE = [
        'Normal Poll',
    ];

    public function options()
    {
        return $this->hasMany(Option::class);
    }

    public function getTypeNameAttribute()
    {
        return SELF::TYPE[$this->type];
    }

    public function getStatusNameAttribute()
    {
        if ($this->is_not_started) {
            $statusName = 'Not started';
        } else if ($this->is_ended) {
            $statusName = 'Ended';
        } else {
            $statusName = 'Ongoing';
        }

        return $statusName;
    }

    public function getIsNotStartedAttribute()
    {
        return ($this->start_at === null || $this->start_at->isFuture());
    }

    public function getIsOngoingAttribute()
    {
        return (!$this->is_not_started && !$this->is_ended);
    }

    public function getIsEndedAttribute()
    {
        return ($this->stop_at && $this->stop_at->addMinutes(10)->isPast());
    }

    public function getInputTypeAttribute()
    {
        return ($this->min === 1 && $this->max === 1) ? 'radio' : 'checkbox';
    }
    
    public function getVoterCountAttribute()
    {
        return Vote::where('question_id', $this->id)->distinct('voter')->count();
    }

    public function getFormattedStartAtAttribute()
    {
        return $this->start_at ? $this->start_at->format('Y-m-d H:i:s') : null;
    }

    public function getFormattedStopAtAttribute()
    {
        return $this->stop_at ? $this->stop_at->format('Y-m-d H:i:s') : null;
    }

    public function deleteChildren()
    {
        foreach ($this->options as $option) {
            $option->deleteChildren()->delete();
        }

        return $this;
    }
}
