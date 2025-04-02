<?php

namespace App\Domain\Candidate\Listeners;

use App\Domain\Candidate\Events\CandidateCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Resend\Laravel\Facades\Resend;

class SendEmailToCandidate implements ShouldQueue
{
    /**
     * Create the event listener.
     */

    public $tries = 3;
    public $backoff = 60;

    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CandidateCreated $event): void
    {
        // $html = view('emails.candidate_welcome', ['candidate' => $event->candidate])->render();

        // Resend::emails()->send([
        //     'from' => 'Hafizh <hafizh@resend.dev>',
        //     'to' => [$event->candidate->email],
        //     'subject' => 'Hello world',
        //     'html' => $html
        // ]);
    }
}
