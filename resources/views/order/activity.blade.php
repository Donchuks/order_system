@extends('layouts.app')

@section('title')
    Order #{{ \App\Helper\Helper::generate_id_number($order->id) }} Activity
@endsection

@section('css')
    @include('includes.datatable-css')
@endsection

@section('body')

<section class="vbox">

    <section class="scrollable padder">
        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
            <li><a href="{{url('/')}}"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Order Activity</li>
            <li><a href="javascript:void(0)" onclick="window.history.back();">Go Back</a></li>
        </ul>
        <div class="m-b-md">
            <h3 class="m-b-none">Order #{{ \App\Helper\Helper::generate_id_number($order->id) }} Activity

            </h3>
        </div>
        <section class="panel panel-default" id="card_content">
            <header class="panel-heading">
                <button onClick ="$('#dataTableBuilder').tableExport({type:'pdf',escape:'false',pdfFontSize:12,separator: ','});" class="btn btn-default btn-xs pull-right">PDF</button>
                <button onClick ="$('#dataTableBuilder').tableExport({type:'csv',escape:'false'});" class="btn btn-default btn-xs pull-right">CSV</button>
                <button onClick ="$('#dataTableBuilder').tableExport({type:'excel',escape:'false'});" class="btn btn-default btn-xs pull-right">Excel</button>
                <button onClick ="$('#dataTableBuilder').tableExport({type:'sql',escape:'false',tableName:'orders'});" class="btn btn-default btn-xs pull-right">SQL</button>
                <i class="fa fa-info-sign text-muted" data-toggle="tooltip" data-placement="bottom" data-title="ajax to load the data."></i>
            </header>

            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table table-hover align-middle mb-0']) !!}
            </div>

        </section>
    </section>
 </section>

@endsection

@section('scripts')
    {!! $dataTable->scripts() !!}

    @include('includes.datatable-js')

@endsection
