<?php

namespace App\Events;

use App\Models\Agm;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AgmCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The AGM instance that was created.
     *
     * @var \App\Models\Agm
     */
    public Agm $agm;

    public function __construct(Agm $agm)
    {
        $this->agm = $agm;
    }
}
