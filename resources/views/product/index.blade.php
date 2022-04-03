@extends('layouts.app')

@section('title')
    All Orders
@endsection

@section('css')
    @include('includes.datatable-css')
@endsection

@section('body')

<section class="vbox">

    <section class="scrollable padder">
        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
            <li><a href="{{url('/')}}"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Products</li>
            <li><a href="javascript:void(0)" onclick="window.history.back();">Go Back</a></li>
        </ul>
        <div class="m-b-md">
            <h3 class="m-b-none">Products
                <a href="{{ route('product.create') }}" class="modal-effect btn btn-info mt-1 mb-1"><i class="flaticon2-add-square"></i> Add New</a>
            </h3>
        </div>
        <section class="panel panel-default" id="card_content">
            <header class="panel-heading">
                All Products Data
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

    <script>

        function delete_notify(id) {
            swal({
                title: "<small>Reason for deleting this product?</small>",
                input: "textarea",
                showCancelButton: true,
                confirmButtonColor: "#3858f9",
                confirmButtonText: "Proceed!",
                cancelButtonText: "No, cancel!",
                closeOnConfirm: false,
                closeOnCancel: true,
                showLoaderOnConfirm: true,
                inputPlaceholder: "type in a reason...",
                inputValidator: function(comment) { // validates your input
                    return new Promise(function(resolve, reject) {
                        if(comment === '') {
                            resolve('comment cannot be empty!')
                            return
                        }
                        swal.close();
                        _delete(id, comment);
                    });
                }
            });
        }


        function _delete(id, comment) {

            ajax('{{route('product.delete')}}', {
                id: id,
                comment: comment,
                _token: _token
            }, 'card_content', function (response) {
                if(response.status === 200) {
                    show_toast('Success!', response.message, 'success');
                    refreshDataTable();
                }
                else {
                    show_toast('Error!', response.message, 'error');
                }
            });
        }
    </script>

    @include('includes.datatable-js')

@endsection
