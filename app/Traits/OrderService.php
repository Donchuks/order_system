<?php

namespace App\Traits;

use App\Enum\OrderStatus;
use App\Models\Order;
use App\Models\OrderActivityLog;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Http\Request;

trait OrderService {

    public function updateOrderStatus(Request $request, Faker $faker, int $id) {
        if ($request->order_status == OrderStatus::ORDER_SHIPPED)
            $request->validate([
                'shipping_company' => 'required|string',
                'tracking_number' => 'required|string',
                'shipping_attachment' => 'sometimes|nullable|mimes:pdf,xlx,csv,jpg,jpeg,png|max:2048',
                'shipping_date' => 'required|date',
            ]);

        $order = Order::find($id);

        if ($request->order_status == OrderStatus::ORDER_READY_TO_SHIP)
            $order->box_id = 'BX'.$faker->randomNumber(6, true);

        $order->order_status = $request->order_status;
        $order->save();

        if ($request->order_status == OrderStatus::ORDER_SHIPPED) {
            $fileName = null;
            if ($request->hasFile('shipping_attachment')) {
                $file = $request->shipping_attachment;
                $fileName = 'ORDER'.$order->id.'-'.time().'.'.$file->extension();
                $file->move(public_path('uploads/shipping_attachments'), $fileName);
            }

            $order->shipment()->delete();
            $order->addShipment([
                'shipping_attachment' => $fileName,
                'shipping_company' => $request->shipping_company,
                'tracking_number' => $request->tracking_number,
                'shipping_date' => $request->shipping_date,
            ]);
        }
    }

    public function logEvent(Order $order, string $activity) {

        OrderActivityLog::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'activity' => $activity,
            'current_state' => $order->order_status
        ]);
    }
}
