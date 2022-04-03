<?php

namespace App\Listeners;

use App\Traits\OrderService;
use Illuminate\Support\Facades\Log;

class OrderReadyShipmentListener
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
        Log::info("::ORDER READY TO SHIP:: Order #".$event->order->id." has been changed to READY TO SHIP by ".auth()->user()->fl_names());
        $this->logEvent($event->order, "::ORDER READY TO SHIP:: Order #".$event->order->id." has been changed to READY TO SHIP by ".auth()->user()->fl_names());
    }
}
