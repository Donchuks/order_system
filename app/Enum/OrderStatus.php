<?php

namespace App\Enum;

abstract class OrderStatus {
    const ORDER_RECEIVED = 'ORDER_RECEIVED';
    const ORDER_PROCESSING = 'ORDER_PROCESSING';
    const ORDER_READY_TO_SHIP = 'ORDER_READY_TO_SHIP';
    const ORDER_SHIPPED = 'ORDER_SHIPPED';
    const ORDER_CANCELLED = 'ORDER_CANCELLED';
}