<?php

namespace Bigmom\Poll\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function option()
    {
        return $this->belongsTo(Option::class);
    }

    public function voter()
    {
        return $this->morphTo();
    }
}
