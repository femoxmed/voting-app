<?php

namespace App\Listeners;

use App\Events\AgmClosed;
use App\Notifications\VotingItemResultNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use Illuminate\Queue\InteractsWithQueue;

class SendAgmResultNotifications implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\AgmClosed  $event
     * @return void
     */
    public function handle(AgmClosed $event)
    {
        // Get all notifiable users from the shareholders
        $users = $event->agm->company->shareholders
            ->map(fn ($shareholder) => $shareholder->user)
            ->filter();

        if ($users->isEmpty()) {
            return;
        }
        foreach ($event->agm->votingItems as $votingItem) {
            Notification::send($users, new VotingItemResultNotification($votingItem));
        }
    }
}
