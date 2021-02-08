<?php

namespace App\Jobs\Poll;

use Bigmom\Poll\Actions\Vote\CastVote;
use Bigmom\Poll\Models\Vote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class RecordVote implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 30;

    protected $input;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $input)
    {
        $this->input = $input;

        $this->tries = config('poll.record-vote.tries', 3);
        $this->timeout = config('poll.record-vote.timeout', 30);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $input = $this->input;
        (new CastVote)->validate($input);
        DB::transaction(function () use ($input) {
            foreach ($input['options'] as $option) {
                $vote = new Vote;
                $vote->voter_id = $input['voter_id'];
                $vote->voter_type = $input['voter_type'];
                $vote->question_id = $option->question_id;
                $vote->option_id = $option->id;
                $vote->weight = $input['weight'];
                $vote->vote_at = $input['vote_at'];
                $vote->voter_snapshot = isset($input['voter_snapshot']) ? $input['voter_snapshot'] : null;
                $vote->save();

                $option->cacheCount();
                $option->save();
            }
        });
    }
}
