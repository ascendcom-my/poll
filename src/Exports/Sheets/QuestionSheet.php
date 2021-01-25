<?php

namespace Bigmom\Poll\Exports\Sheets;

use Bigmom\Poll\Models\Question;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class QuestionSheet implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $questions = Question::with('options')->get();

        return view('bigmom-poll::export.sheet.question', compact('questions'));
    }
}
