<?php

namespace App\Events;

use App\Models\Agm;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AgmClosed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The AGM instance.
     *
     * @var \App\Models\Agm
     */
    public Agm $agm;

    /**
     * Create a new event instance.
     */
    public function __construct(Agm $agm)
    {
        $this->agm = $agm;
    }
}
