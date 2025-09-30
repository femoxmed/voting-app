<?php

namespace App\Events;

use App\Models\VotingItem;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VotingItemClosed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The voting item instance.
     *
     * @var \App\Models\VotingItem
     */
    public $votingItem;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\VotingItem  $votingItem
     * @return void
     */
    public function __construct(VotingItem $votingItem)
    {
        $this->votingItem = $votingItem;
    }
}
