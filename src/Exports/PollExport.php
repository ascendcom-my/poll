<?php

namespace Bigmom\Poll\Exports;

use Bigmom\Poll\Exports\Sheets\QuestionSheet;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PollExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new QuestionSheet, 
        ];
    }
}
