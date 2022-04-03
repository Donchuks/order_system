@extends('layouts.app')

@section('title')
    Users
@endsection

@section('css')
    @include('includes.datatable-css')
@endsection

@section('body')
    <!--begin::Content-->
    <section class="vbox">

        <!-- start create  modal -->
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
                                <div class="col-md-6">
                                    <label for="first_name" class="form-control-label">First Name: <span class="tx-danger">*</span></label>
                                    <input class="form-control" id="first_name" name="first_name" placeholder="" required="" type="text">
                                </div>
                                <div class="col-md-6">
                                    <label for="last_name" class="form-control-label">Last Name: <span class="tx-danger">*</span></label>
                                    <input class="form-control" id="last_name" name="last_name" placeholder="" required="" type="text">
                                </div>
                            </div>

                            <div class="row row-sm" style="padding-top: 15px;">
                                <div class="col-md-6">
                                    <label for="username" class="form-control-label">Username: <span class="tx-danger">*</span></label>
                                    <input class="form-control" id="username" name="username" placeholder="" required="" type="text">
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-control-label">Email: <span class="tx-danger">*</span></label>
                                    <input class="form-control" id="email" name="email" placeholder="" required="" type="email">
                                </div>

                            </div>

                            <div class="row row-sm" style="padding-top: 15px;">
                                <div class="col-lg-12">
                                    <label for="gender" class="form-control-label">Gender:</label><br>
                                    <select style="width: 100%;" required="" class="form-control create_select" name="gender" id="gender">
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>


                            <div class="row row-sm" style="padding-top: 15px;">
                                <div class="col-lg-12">
                                    <label for="password" class="form-control-label">Password: <span class="tx-danger">*</span></label>
                                    <input class="form-control" id="password" name="password" placeholder="" required="" type="password">
                                </div>
                            </div>


                            <div class="row row-sm" style="padding-top: 15px;">
                                <div class="col-lg-12">
                                    <label for="role" class="form-control-label">Role:</label><br>
                                    <select style="width: 100%;" required="" class="form-control create_select_search" name="role" id="role">
                                        <option></option>
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}">{{$role->name}}</option>
                                        @endforeach
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



        <!-- start edit modal -->
        <div class="modal edit_modal" id="edit_modal">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="width:50%;">
                <div class="modal-content modal-content-demo" id="edit_modal_content">
                    <div class="modal-header">
                        <h6 class="modal-title">Update</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span style="display: none;" aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="form_edit">
                            @csrf
                            <input type="hidden" id="edit_id"/>
                            <div class="row row-sm" style="padding-top: 0;">
                                <div class="col-md-6">
                                    <label for="edit_first_name" class="form-control-label">First Name: <span class="tx-danger">*</span></label>
                                    <input class="form-control" id="edit_first_name" name="edit_first_name" placeholder="" required="" type="text">
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_last_name" class="form-control-label">Last Name: <span class="tx-danger">*</span></label>
                                    <input class="form-control" id="edit_last_name" name="edit_last_name" placeholder="" required="" type="text">
                                </div>
                            </div>

                            <div class="row row-sm" style="padding-top: 15px;">
                                <div class="col-md-6">
                                    <label for="edit_username" class="form-control-label">Username: <span class="tx-danger">*</span></label>
                                    <input class="form-control" id="edit_username" name="edit_username" placeholder="" required="" type="text">
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_email" class="form-control-label">Email: <span class="tx-danger">*</span></label>
                                    <input class="form-control" id="edit_email" name="edit_email" placeholder="" required="" type="text">
                                </div>

                            </div>

                            <div class="row row-sm" style="padding-top: 15px;">
                                <div class="col-lg-12">
                                    <label for="edit_gender" class="form-control-label">Gender:</label><br>
                                    <select style="width: 100%;" required="" class="form-control edit_select" name="edit_gender" id="edit_gender">
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>


                            <div class="row row-sm" style="padding-top: 15px;">
                                <div class="col-lg-12">
                                    <label for="edit_role" class="form-control-label">Role:</label><br>
                                    <select style="width: 100%;" required="" class="form-control edit_select_search" name="edit_role" id="edit_role">
                                        <option></option>
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}">{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                        </form>
                    </div>

                    <div class="modal-footer" style="margin-top: 0">

                        <button style="width: 100%;" class="btn btn-primary btn-rounded btn-block" onclick="edit();" type="button">Submit</button>
                        <button style="display: none;" class="btn ripple btn-secondary" data-dismiss="modal" type="button">Close</button>
                    </div>
                </div>
            </div>
        </div>


        <section class="scrollable padder">
            <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="{{url('/')}}"><i class="fa fa-home"></i> Home</a></li>
                <li class="active">Users</li>
                <li><a href="javascript:void(0)" onclick="window.history.back();">Go Back</a></li>
            </ul>
            <div class="m-b-md">
                <h3 class="m-b-none">Users
                    <a href="javascript:void(0)" data-toggle="modal" data-effect="effect-scale" data-target="#create_modal" class="modal-effect btn btn-info mt-1 mb-1"><i class="flaticon2-add-square"></i> Add New</a>
                </h3>
            </div>
            <section class="panel panel-default" id="card_content">
                <header class="panel-heading">
                    All Users
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

        // modal_animation('create_modal');
        // modal_animation('edit_modal');

        function create() {

            let first_name = $("#first_name").val();
            let last_name = $("#last_name").val();
            let username = $("#username").val();
            let email = $("#email").val();
            let gender = $("#gender option:selected").val();
            let password = $("#password").val();
            let role = $("#role option:selected").val();

            if(first_name === '') {
                show_toast('Caution!', 'Please Enter First Name', 'warning');
                return;
            }

            if(last_name === '') {
                show_toast('Caution!', 'Please Enter Last Name', 'warning');
                return;
            }

            if(username === '') {
                show_toast('Caution!', 'Please Enter Username', 'warning');
                return;
            }

            if(email === '') {
                show_toast('Caution!', 'Please Enter Email', 'warning');
                return;
            }


            if(gender === '') {
                show_toast('Caution!', 'Please Select Gender', 'warning');
                return;
            }

            if(password === '') {
                show_toast('Caution!', 'Please Enter Password', 'warning');
                return;
            }

            if(role === '') {
                show_toast('Caution!', 'Please Select a Role', 'warning');
                return;
            }

            ajax('{{route('users.create')}}', {
                first_name: first_name,
                last_name: last_name,
                username: username,
                email: email,
                gender: gender,
                password: password,
                role: role,
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



        function grab_edits(id) {

            ajax('{{route('users.get')}}', {
                id: id,
                _token: _token
            }, 'card_content', function (response) {
                $.each(response.data, function (key, value) {
                    $('#edit_modal .modal-title').html('Updating Value: ' + value.first_name);
                    $('#edit_id').val(value.id);
                    $('#edit_first_name').val(value.first_name);
                    $('#edit_last_name').val(value.last_name);
                    $('#edit_username').val(value.username);
                    $('#edit_email').val(value.email);
                    $('#edit_gender').val(value.gender).change();
                    if (value.roles.length > 0)
                        $('#edit_role').val(value.roles[0].id).change();
                })

                // jQuery.noConflict();
                $('#edit_modal').modal('toggle');
            });
        }

        function edit() {

            let id = $('#edit_id').val();
            let first_name = $("#edit_first_name").val();
            let last_name = $("#edit_last_name").val();
            let username = $("#edit_username").val();
            let email = $("#edit_email").val();
            let gender = $("#edit_gender option:selected").val();
            let role = $("#edit_role option:selected").val();

            if(first_name === '') {
                show_toast('Caution!', 'Please Enter First Name', 'warning');
                return;
            }

            if(last_name === '') {
                show_toast('Caution!', 'Please Enter Last Name', 'warning');
                return;
            }

            if(username === '') {
                show_toast('Caution!', 'Please Enter Username', 'warning');
                return;
            }

            if(email === '') {
                show_toast('Caution!', 'Please Enter Email', 'warning');
                return;
            }

            if(gender === '') {
                show_toast('Caution!', 'Please Select Gender', 'warning');
                return;
            }

            if(role === '') {
                show_toast('Caution!', 'Please Select a Role', 'warning');
                return;
            }

            ajax('{{route('users.edit')}}', {
                id: id,
                role_id: role,
                first_name: first_name,
                last_name: last_name,
                username: username,
                email: email,
                gender: gender,
                role: role,
                _token: _token
            }, 'edit_modal_content', function (response) {
                if (response.status === 200) {
                    $('#edit_modal').modal('hide');
                    resetForm('form_edit');
                    waitme("card_content");
                    show_toast('Success', response.message, 'success');
                    setTimeout(function() {
                        refreshDataTable();
                        hidewaitme('card_content');
                    }, 200);
                }
                else {
                    show_toast('Caution!', response.message, 'warning');
                }
            });
        }


    </script>

    @include('includes.datatable-js')

@endsection
