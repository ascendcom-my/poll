<?php

namespace Bigmom\Poll\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class QuestionTemplateExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        return view('poll::export.template.question');
    }
}
