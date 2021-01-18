<?php

namespace Bigmom\Poll\Imports;

use Bigmom\Poll\Actions\Question\CreateQuestion;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $input = $row->all();
            if (isset($input['options']) && is_string($input['options'])) {
                $input['options'] = explode("||", $input['options']);
            } else {
                return back()
                    ->withInput()
                    ->withErrors('options', 'Options not provided.');
            }
            (new CreateQuestion)->run($input);
        }
    }
}
