@extends('layouts.app')

@section('title')
    Edit Order
@endsection


@section('body')

    <section class="vbox">
        <section class="scrollable padder">
            <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="{{url('/')}}"><i class="fa fa-home"></i> Home</a></li>
                <li class="active">Update Order</li>
                <li><a href="javascript:void(0)" onclick="window.history.back();">Go Back</a></li>
            </ul>
            <div class="m-b-md">
                <h3 class="m-b-none">Update Order</h3>
            </div>

            @include('includes.error')

            <section class="panel panel-default">
                <header class="panel-heading">
                    Update Order
                    <i class="fa fa-info-sign text-muted" data-toggle="tooltip" data-placement="bottom" data-title="ajax to load the data."></i>
                </header>

                <div class="panel-body">
                    {!! Form::model($order, array('method'=>'PATCH','route' => array('order.update', $order->id), 'enctype' => "multipart/form-data"), ['enctype' => "multipart/form-data"]) !!}
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Name</label>
                            {!! Form::text('name', null, ['placeholder'=>'Enter full name', 'class'=>'form-control input-lg','required', 'disabled']) !!}
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            {!! Form::text('phone', null, ['placeholder'=>'Enter phone number', 'class'=>'form-control input-lg','required', 'disabled']) !!}
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            {!! Form::textarea('address', null, ['placeholder'=>'Enter full address', 'class'=>'form-control input-lg','rows'=>'3','required', 'disabled']) !!}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Delivery Date</label>
                            {!! Form::date('delivery_date', null, [ 'class'=>'form-control input-lg','required', 'disabled']) !!}
                        </div>
                        <div class="form-group">
                            <label>Select Product</label>
                            {!! Form::select('product_id', $products->pluck('name', 'id')->toArray(), 'S', ['class'=>'form-control m-b input-lg','required', 'disabled']) !!}
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Select Pament Option</label>
                                    {!! Form::select('payment_option', array('Postpay' => 'Postpay', 'Prepay (Full)' => 'Prepay (full)', 'Prepay (Half)' => 'Prepay (Half)'), 'S', ['class'=>'form-control m-b input-lg','required', 'disabled']) !!}
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="line line-dashed line-lg pull-in"></div>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-2">Status</label>
                                <div class="col-sm-10 ">
                                    @if(auth()->user()->hasRole(\App\Enum\RoleName::SHIPPING))
                                        <label class="order_status radio-custom col-md-2 input-md">{!! Form::radio('order_status', \App\Enum\OrderStatus::ORDER_PROCESSING) !!}<i class="fa fa-circle-o"></i>Processing </label>
                                        <label class="order_status radio-custom col-md-2 input-md">{!! Form::radio('order_status', \App\Enum\OrderStatus::ORDER_READY_TO_SHIP) !!}<i class="fa fa-circle-o"></i>Ready </label>
                                        <label class="order_status radio-custom col-md-2 input-md">{!! Form::radio('order_status', \App\Enum\OrderStatus::ORDER_SHIPPED) !!}<i class="fa fa-circle-o"></i>Shipped </label>
                                    @elseif(auth()->user()->hasRole(\App\Enum\RoleName::PICKING))
                                        <label class="order_status radio-custom col-md-2 input-md">{!! Form::radio('order_status', \App\Enum\OrderStatus::ORDER_RECEIVED) !!}<i class="fa fa-circle-o fa-1x"></i>Received </label>
                                        <label class="order_status radio-custom col-md-2 input-md">{!! Form::radio('order_status', \App\Enum\OrderStatus::ORDER_PROCESSING) !!}<i class="fa fa-circle-o"></i>Processing </label>
                                        <label class="order_status radio-custom col-md-2 input-md">{!! Form::radio('order_status', \App\Enum\OrderStatus::ORDER_READY_TO_SHIP) !!}<i class="fa fa-circle-o"></i>Ready </label>
                                    @else
                                        <label class="order_status radio-custom col-md-2 input-md">{!! Form::radio('order_status', \App\Enum\OrderStatus::ORDER_RECEIVED) !!}<i class="fa fa-circle-o fa-1x"></i>Received </label>
                                        <label class="order_status radio-custom col-md-2 input-md">{!! Form::radio('order_status', \App\Enum\OrderStatus::ORDER_PROCESSING) !!}<i class="fa fa-circle-o"></i>Processing </label>
                                        <label class="order_status radio-custom col-md-2 input-md">{!! Form::radio('order_status', \App\Enum\OrderStatus::ORDER_READY_TO_SHIP) !!}<i class="fa fa-circle-o"></i>Ready </label>
                                        <label class="order_status radio-custom col-md-2 input-md">{!! Form::radio('order_status', \App\Enum\OrderStatus::ORDER_SHIPPED) !!}<i class="fa fa-circle-o"></i>Shipped </label>
                                        <label class="order_status radio-custom col-md-2 input-md">{!! Form::radio('order_status', \App\Enum\OrderStatus::ORDER_CANCELLED) !!}<i class="fa fa-circle-o"></i>Cancelled </label>

                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" id="row_shipping_details" style="display: none; padding-top: 10px;">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Shipping Company:</label>
                                {!! Form::text('shipping_company', $order->shipment->shipping_company ?? null, ['placeholder'=>'Enter shipping company', 'class'=>'form-control input-lg']) !!}
                            </div>
                            <div class="form-group">
                                <label>Tracking Number:</label>
                                {!! Form::text('tracking_number', $order->shipment->tracking_number ?? null, ['placeholder'=>'Enter tracking number', 'class'=>'form-control input-lg']) !!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Shipping Attachment:</label>
                                {!! Form::file('shipping_attachment', null, ['placeholder'=>'Upload attachment', 'class'=>'form-control input-lg']) !!}
                            </div>

                            <div class="form-group" style="padding-top: 5px;">
                                <label>Shipping Date: @if(!is_null($order->shipment)):{{\Carbon\Carbon::parse($order->shipment->shipping_date)->format('d/m/Y')}}@endif</label>
                                {!! Form::date('shipping_date', !is_null($order->shipment) ? \Carbon\Carbon::parse($order->shipment->shipping_date)->format('d/m/Y') : null, [ 'class'=>'form-control input-lg']) !!}
                            </div>

                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="line line-dashed line-lg pull-in"></div>
                        {!! Form::submit('Submit', [ 'class'=>'btn btn-default']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>

            </section>
        </section>
    </section>

@endsection

@section('scripts')
    <script>
        let order_status = $('input[name=order_status]:checked').val();

        if(order_status === "ORDER_SHIPPED") {
            $('#row_shipping_details').show();
        } else {
            $('#row_shipping_details').hide();
        }

        $('.order_status').change(function(){
            order_status = $('input[name=order_status]:checked').val();
            if(order_status === "ORDER_SHIPPED") {
                $('#row_shipping_details').show();
            } else {
                $('#row_shipping_details').hide();
            }
        });
    </script>
@endsection
