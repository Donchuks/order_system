@extends('layouts.app')

@section('title')
    Permissions
@endsection

@section('css')
    @include('includes.datatable-css')
@endsection

@section('body')
    <!--begin::Content-->
    <section class="vbox">

        <!-- start create modal -->
        <div class="modal create_modal" id="create_modal">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="width:50%;">
                <div class="modal-content modal-content-demo" id="create_modal_content">
                    <div class="modal-header">
                        <h6 class="modal-title">Add New</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span style="display: none;" aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="form_create">
                            @csrf
                            <div class="row row-sm" style="padding-top: 0;">
                                <div class="col-md-12">
                                    <label for="name" class="form-control-label">Permission Name: <span class="tx-danger">*</span></label>
                                    <input class="form-control" id="name" name="name" placeholder="" required="" type="text">
                                </div>
                            </div>

                            <div class="row row-sm" style="padding-top: 15px;">
                                <div class="col-lg-12">
                                    <label for="guard_name" class="form-control-label">Guard:</label><br>
                                    <select style="width: 100%;" required="" class="form-control create_select" name="guard_name" id="guard_name">
                                        <option id="web">web</option>
                                    </select>
                                </div>
                            </div>

                        </form>
                    </div>

                    <div class="modal-footer" style="margin-top: 0">

                        <button style="width: 100%;" class="btn btn-primary btn-rounded btn-block" onclick="create();" type="button">Submit</button>
                        <button style="display: none;" class="btn ripple btn-secondary" data-dismiss="modal" type="button">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <section class="scrollable padder">
            <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="{{url('/')}}"><i class="fa fa-home"></i> Home</a></li>
                <li class="active">Permissions</li>
                <li><a href="javascript:void(0)" onclick="window.history.back();">Go Back</a></li>
            </ul>
            <div class="m-b-md">
                <h3 class="m-b-none">Permissions
                    <a href="javascript:void(0)" data-toggle="modal" data-effect="effect-scale" data-target="#create_modal" class="modal-effect btn btn-info mt-1 mb-1"><i class="flaticon2-add-square"></i> Add New</a>
                </h3>
            </div>
            <section class="panel panel-default" id="card_content">
                <header class="panel-heading">
                    All Permissions
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
    <!--end::Content-->

@endsection

@section('scripts')

    {!! $dataTable->scripts() !!}

    <script>
        "use strict";

        modal_animation('create_modal');
        modal_animation('edit_modal');

        function create() {

            let name = $("#name").val();
            let guard_name = $("#guard_name option:selected").val();

            if(name === '') {
                show_toast('Caution!', 'Please Enter Permission Name', 'warning');
                return;
            }

            if(guard_name === '') {
                show_toast('Caution!', 'Please Select Guard', 'warning');
                return;
            }


            ajax('{{route('users.roles.permissions.create')}}', {
                name: name,
                guard_name: guard_name,
                _token: _token
            }, 'create_modal_content', function (response) {
                if (response.status === 200) {
                    $('#create_modal').modal('hide');
                    resetForm('form_create');
                    waitme("card_content");
                    show_toast('Success', response.message, 'success');
                    setTimeout(function() {
                        refreshDataTable();
                        hidewaitme('card_content');
                    }, 200);
                }
            });
        }

    </script>

    @include('includes.datatable-js')

@endsection
