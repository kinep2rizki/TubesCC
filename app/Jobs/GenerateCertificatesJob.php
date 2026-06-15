<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateCertificatesJob implements ShouldQueue
{
    use Queueable;

    protected $eventId;
    protected $template;

    /**
     * Create a new job instance.
     */
    public function __construct($eventId, $template)
    {
        $this->eventId = $eventId;
        $this->template = $template;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $event = \App\Models\Event::findOrFail($this->eventId);
        
        // Fetch all attended participants using cursor() for lazy loading (memory efficient)
        $participants = \App\Models\EventParticipant::with('user')
            ->where('event_id', $this->eventId)
            ->where('status', 'Attended')
            ->cursor();

        foreach ($participants as $participant) {
            // Check if certificate already exists
            $certificate = \App\Models\Certificate::firstOrCreate(
                ['event_participant_id' => $participant->id],
                [
                    'template_style' => $this->template,
                    'file_url' => '/certificates/dummy-certificate.pdf', // Dummy simulated URL
                    'issued_at' => now(),
                ]
            );

            // Send notification to user
            if ($participant->user) {
                $participant->user->notify(new \App\Notifications\CertificateGeneratedNotification($event, $certificate));
            }
        }
    }
}
