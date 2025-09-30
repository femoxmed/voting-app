<?php

namespace App\Listeners;

use App\Events\VotingItemClosed;
use App\Notifications\VotingItemResultNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendVotingItemResultNotification implements ShouldQueue
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
     * @param  \App\Events\VotingItemClosed  $event
     * @return void
     */
    public function handle(VotingItemClosed $event)
    {
        $shareholders = $event->votingItem->agm->company->shareholders()->with('user')->get();

        foreach ($shareholders as $shareholder) {
            $shareholder->user->notify(new VotingItemResultNotification($event->votingItem));
        }
    }
}
