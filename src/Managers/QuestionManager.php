<?php

namespace Bigmom\Poll\Managers;

use Bigmom\Poll\Models\Question;
use Illuminate\Support\Str;

class QuestionManager
{
    public function getByGroup(string $groupId)
    {
        return Question::where('group_id', $groupId)
            ->with('options')
            ->orderBy('sequence')
            ->get();
    }

    public function resolveQuestion($questionId)
    {
        if ($questionId instanceof Question) {
            return $questionId;
        } else if (Str::isUuid($questionId)) {
            return Question::where('token', $questionId)->firstOrFail();
        } else {
            return Question::findOrFail($questionId);
        }
    }
}
