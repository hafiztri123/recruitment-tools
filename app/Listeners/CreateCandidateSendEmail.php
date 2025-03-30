<?php

namespace App\Listeners;

use App\Events\CandidateCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Resend\Laravel\Facades\Resend;

class CreateCandidateSendEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CandidateCreated $event): void
    {
        $html = view('emails.candidate_welcome', ['candidate' => $event->candidate])->render();

        Resend::emails()->send([
            'from' => 'Hafizh <hafizh@resend.dev>',
            'to' => [$event->candidate->email],
            'subject' => 'Hello world',
            'html' => $html
        ]);
    }
}
