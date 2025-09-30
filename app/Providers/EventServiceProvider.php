<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use App\Events\AgmClosed;
use App\Listeners\SendAgmResultNotifications;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\AgmCreated;
use App\Events\VotingItemClosed;
use App\Listeners\SendAgmCreatedNotification;
use App\Listeners\SendVotingItemResultNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        VotingItemClosed::class => [
            SendVotingItemResultNotification::class,
        ],
        AgmClosed::class => [
            SendAgmResultNotifications::class,
        ],
    ];

}
