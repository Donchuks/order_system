@extends('layouts.app')

@section('title')
    Add Order
@endsection

@section('body')

<section class="vbox">
    <section class="scrollable padder">
        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
            <li><a href="{{url('/')}}"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">New Order</li>
            <li><a href="javascript:void(0)" onclick="window.history.back();">Go Back</a></li>
        </ul>
        <div class="m-b-md">
            <h3 class="m-b-none">Add a Order</h3>
        </div>

        @include('includes.error')

        <section class="panel panel-default">
            <header class="panel-heading">
                Add Order
                <i class="fa fa-info-sign text-muted" data-toggle="tooltip" data-placement="bottom" data-title="ajax to load the data."></i>
            </header>

            <div class="panel-body">
                {!! Form::open(array('route'=>'order.store')) !!}
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Name</label>
                            {!! Form::text('name', '', ['placeholder'=>'Enter full name', 'class'=>'form-control input-lg','required']) !!}
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            {!! Form::text('phone', '', ['placeholder'=>'Enter phone number', 'class'=>'form-control input-lg','required']) !!}
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            {!! Form::textarea('address', '', ['placeholder'=>'Enter full address', 'class'=>'form-control input-lg','rows'=>'3','required']) !!}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Delivery Date</label>
                            {!! Form::date('delivery_date', '', [ 'class'=>'form-control input-lg','required']) !!}
                        </div>
                        <div class="form-group">
                            <label>Select Product</label>
                            {!! Form::select('product_id', $products->pluck('name', 'id')->toArray(), 'S', ['class'=>'form-control m-b input-lg','required']) !!}
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Select Pament Option</label>
                                    {!! Form::select('payment_option', array('Postpay' => 'Postpay', 'Prepay (Full)' => 'Prepay (full)', 'Prepay (Half)' => 'Prepay (Half)'), 'S', ['class'=>'form-control m-b input-lg','required']) !!}
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
                                    <label class="radio-custom col-md-2 input-md">{!! Form::radio('order_status', \App\Enum\OrderStatus::ORDER_RECEIVED, 'checked') !!}<i class="fa fa-circle-o fa-1x"></i>Received </label>
                                    <label class="radio-custom col-md-2 input-md">{!! Form::radio('order_status', \App\Enum\OrderStatus::ORDER_PROCESSING) !!}<i class="fa fa-circle-o"></i>Processing </label>
                                    <label class="radio-custom col-md-2 input-md">{!! Form::radio('order_status', \App\Enum\OrderStatus::ORDER_READY_TO_SHIP) !!}<i class="fa fa-circle-o"></i>Ready </label>
                                    <label class="radio-custom col-md-2 input-md">{!! Form::radio('order_status', \App\Enum\OrderStatus::ORDER_SHIPPED) !!}<i class="fa fa-circle-o"></i>Shipped </label>
                                    <label class="radio-custom col-md-2 input-md">{!! Form::radio('order_status', \App\Enum\OrderStatus::ORDER_CANCELLED) !!}<i class="fa fa-circle-o"></i>Cancelled </label>
                                </div>
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
