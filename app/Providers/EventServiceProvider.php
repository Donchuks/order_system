<?php

namespace App\Providers;

use App\Events\OrderCancelledEvent;
use App\Events\OrderProcessingEvent;
use App\Events\OrderReadyShipmentEvent;
use App\Events\OrderReceivedEvent;
use App\Events\OrderShippedEvent;
use App\Listeners\OrderCancelledListener;
use App\Listeners\OrderProcessingListener;
use App\Listeners\OrderReadyShipmentListener;
use App\Listeners\OrderReceivedListener;
use App\Listeners\OrderShippedListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

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

        OrderReceivedEvent::class => [
                OrderReceivedListener::class,
        ],

        OrderProcessingEvent::class => [
            OrderProcessingListener::class,
        ],

        OrderReadyShipmentEvent::class => [
            OrderReadyShipmentListener::class,
        ],

        OrderShippedEvent::class => [
            OrderShippedListener::class,
        ],

        OrderCancelledEvent::class => [
            OrderCancelledListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
