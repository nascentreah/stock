<?php

namespace App\Providers;

use App\Events\ChatMessageSent;
use App\Listeners\BroadcastChatMessage;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\AfterCompetitionClosed;
use App\Listeners\UserPointsEventsSubscriber;
use App\Listeners\CloneCompetitionIfRecurring;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        AfterCompetitionClosed::class => [ // event
            CloneCompetitionIfRecurring::class // listener
        ],
        ChatMessageSent::class => [
            BroadcastChatMessage::class
        ]
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        UserPointsEventsSubscriber::class,
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        // email verification is enabled add dynamic event listener for Registered event (to send verification link).
        if (config('settings.users.email_verification')) {
            Event::listen(Registered::class, SendEmailVerificationNotification::class);
        }
    }
}
