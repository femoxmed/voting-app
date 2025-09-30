<?php

namespace App\Listeners;

use App\Events\AgmCreated;
use App\Notifications\AgmCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendAgmCreatedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  \App\Events\AgmCreated  $event
     * @return void
     */
    public function handle(AgmCreated $event)
    {
        $agm = $event->agm;

        // Eager load the user relationship for efficiency
        $shareholders = $agm->company->shareholders()->with('user')->get();

        // Using a collection-based approach is cleaner than a foreach loop
        $users = $shareholders->map->user->filter();

        if ($users->isNotEmpty()) {
            \Illuminate\Support\Facades\Notification::send($users, new AgmCreatedNotification($agm));
        }
    }
}
