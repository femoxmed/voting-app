<?php

namespace App\Jobs;

use App\Models\Agm;
use App\Notifications\AgmCreatedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class SendAgmCreatedNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The AGM instance.
     *
     * @var \App\Models\Agm
     */
    protected Agm $agm;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Agm $agm)
    {
        $this->agm = $agm;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $users = $this->agm->company->shareholders->map->user->filter();

        if ($users->isNotEmpty()) {
            Notification::send($users, new AgmCreatedNotification($this->agm));
        }
    }
}
