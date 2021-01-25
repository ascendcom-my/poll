<?php

namespace Bigmom\Poll\Http\Controllers;

use Bigmom\Poll\Exports\PollExport;
use Bigmom\Poll\Exports\QuestionTemplateExport;
use Bigmom\Poll\Imports\QuestionsImport;
use Bigmom\Poll\Objects\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function getImport()
    {
        return view('bigmom-poll::question.import');
    }

    public function downloadTemplate()
    {
        return Excel::download(new QuestionTemplateExport, 'question-template.xlsx');
    }

    public function postImport(Request $request)
    {
        $status = DB::transaction(function () use ($request) {
            $input = [$request->file('file')];

            if ($request->input('truncate')) {
                DB::table('questions')->truncate();
                DB::table('options')->truncate();
                DB::table('votes')->truncate();
            }

            Excel::import(new QuestionsImport, $request->file('file'));

            return new Status(0, 'Questions and options successfully imported.');
        });

        return $status->getCode() === 0
            ? redirect()
                ->back()
                ->with('success', $status->getMessage())
            : redirect()
                ->back()
                ->withErrors('error', 'An error has occured.');
    }

    public function downloadExport()
    {
        return Excel::download(new PollExport, 'poll.xlsx');
    }
}
