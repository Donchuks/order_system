@extends('layouts.app')

@section('title')
    Add Order
@endsection

@section('body')

<section class="vbox">
    <section class="scrollable padder">
        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
            <li><a href="{{url('/')}}"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">New Product</li>
            <li><a href="javascript:void(0)" onclick="window.history.back();">Go Back</a></li>
        </ul>
        <div class="m-b-md">
            <h3 class="m-b-none">Add a Product</h3>
        </div>

        @include('includes.error')

        <section class="panel panel-default">
            <header class="panel-heading">
                Add Product
                <i class="fa fa-info-sign text-muted" data-toggle="tooltip" data-placement="bottom" data-title="ajax to load the data."></i>
            </header>

            <div class="panel-body">
                {!! Form::open(array('route'=>'product.store')) !!}
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Product Name</label>
                        {!! Form::text('name', null, ['placeholder'=>'Enter product name', 'class'=>'form-control input-lg','required']) !!}
                    </div>
                    <div class="form-group">
                        <label>Price</label>
                        {!! Form::text('price', null, ['placeholder'=>'Enter product price', 'class'=>'form-control input-lg','required']) !!}
                    </div>
                    <div class="form-group">
                        <label>Currency</label>
                        {!! Form::text('currency', null, ['placeholder'=>'Enter currency', 'class'=>'form-control input-lg','required']) !!}
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
