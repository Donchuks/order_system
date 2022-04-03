@extends('layouts.app')

@section('title')
    Order #{{ \App\Helper\Helper::generate_id_number($order->id) }} INVOICE
@endsection

@section('body')

    <section class="vbox bg-white">
        <header class="header b-b b-light hidden-print">
            <button href="#" class="btn btn-sm btn-info pull-right" onClick="window.print();">Print</button>
            <p>Invoice</p>
        </header>
        <section class="scrollable wrapper" id="print">
            <div class="row">
                <div class="col-xs-6">
                    <h2 style="margin-top: 0px">Order <b>System</b></h2>
                    <p>1 Infinite Loop <br>
                        Ave Maria, Dansoman<br>
                        Ghana
                    </p>
                </div>
                <div class="col-xs-6 text-right">
                    <h4>INVOICE</h4>
                </div>
            </div>
            <div class="well m-t" style="margin-bottom: 50px">
                <div class="row">
                    <div class="col-xs-6">
                        <strong>TO:</strong>
                        <h4>{{ $order->name }}</h4>
                        <p>
                            {{ $order->address }}
                        </p>
                    </div>
                    <div class="col-xs-6 text-right">
                        <p class="h4">#{{ \App\Helper\Helper::generate_id_number($order->id) }}</p>
                        <h5>{{ Carbon\Carbon::parse($order->created_at)->format('d M, Y') }}</h5>
                        <p class="m-t m-b">Delivery Date: <strong>{{ Carbon\Carbon::parse($order->delivery_date)->format('d M, Y') }}</strong><br>
                            BOX ID: <strong>{{ $order->box_id }}</strong>
                        </p>
                        @if(!is_null($order->shipment))
                            <hr>
                            <p class="m-t m-b">Shipping Company: <strong>{{ $order->shipment->shipping_company }}</strong><br>
                            <p class="m-t m-b">Tracking Number: <strong>{{ $order->shipment->tracking_number }}</strong><br>
                            <p class="m-t m-b">Shipping Attachment: <strong>@if(!is_null($order->shipment->shipping_attachment)) <a href="{{ asset('uploads/shipping_attachments/'.$order->shipment->shipping_attachment) }}">Download</a> @else N/A @endif</strong><br>
                            <p class="m-t m-b">Shipping Date: <strong>{{ Carbon\Carbon::parse($order->shipment->shipping_date)->format('d M, Y') }}</strong><br>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="line"></div>
            <table class="table">
                <thead>
                <tr>
                    <th width="60">QTY</th>
                    <th>DESCRIPTION</th>
                    <th width="120">TOTAL</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>1</td>
                    <td>{{ $order->product->name }}</td>
                    <td>{{ $order->product->currency }} {{ $order->product->price }}</td>
                </tr>
                <tr>
                    <td colspan="2" class="text-right"><strong>Subtotal</strong></td>
                    <td>{{ $order->product->currency }} {{ $order->product->price }}</td>
                </tr>
                <tr>
                    <td colspan="2" class="text-right no-border"><strong>VAT Included in Total</strong></td>
                    <td>GHS 0.00</td>
                </tr>
                <tr>
                    <td colspan="2" class="text-right no-border"><strong>Total</strong></td>
                    <td><strong>{{ $order->product->currency }} {{ $order->product->price }}</strong></td>
                </tr>
                </tbody>
            </table>
            <div class="row">
                <div class="col-xs-8">
                    <p><i> The invoice is generated from the order system. Thanks for your patronage.</i></p>

                    <p>Recvied By: __________________ </p>
                </div>
            </div>
        </section>
    </section>

@endsection
