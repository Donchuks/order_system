<?php

namespace App\Listeners;

use App\Traits\OrderService;
use Illuminate\Support\Facades\Log;

class OrderCancelledListener
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
        Log::info("::ORDER CANCELLED:: Order #".$event->order->id." has been cancelled by ".auth()->user()->fl_names());
        $this->logEvent($event->order, "::ORDER CANCELLED:: Order #".$event->order->id." has been cancelled by ".auth()->user()->fl_names());
    }
}
