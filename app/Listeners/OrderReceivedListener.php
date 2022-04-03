<?php

namespace App\Listeners;

use App\Traits\OrderService;
use Illuminate\Support\Facades\Log;

class OrderReceivedListener
{
    use OrderService;
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
     * @param object $event
     * @return void
     */
    public function handle(object $event)
    {
        Log::info("::ORDER RECEIVED:: Order #".$event->order->id." has been received by the system");
        $this->logEvent($event->order, "::ORDER RECEIVED:: Order #".$event->order->id." has been received by the system");
    }
}
