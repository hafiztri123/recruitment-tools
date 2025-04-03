<?php

namespace App\Domain\Interviewer\Jobs;

use App\Domain\Interviewer\Interfaces\InterviewerServiceInterface;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AssignUserAsInterviewer implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private int $interviewerID,
        private int $interviewID
    ) { }

    public function handle()
    {
        if ($this->batch() && $this->batch()->cancelled()) {
            return;
        }

        $interviewerService = app(InterviewerServiceInterface::class);

        $interviewerService->assignInterviewer(
            [
                'interview_id' => $this->interviewID,
                'user_id' => $this->interviewerID
            ]
        );
    }
}
