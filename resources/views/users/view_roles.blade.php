@extends('layouts.app')

@section('title')
    Role Permissions
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
                <li class="active">Role Permissions</li>
                <li><a href="javascript:void(0)" onclick="window.history.back();">Go Back</a></li>
            </ul>
            <div class="m-b-md">
                <h3 class="m-b-none">Role Permissions
                    <a href="javascript:void(0)" data-toggle="modal" data-effect="effect-scale" data-target="#create_modal" class="modal-effect btn btn-info mt-1 mb-1"><i class="flaticon2-add-square"></i> Add New</a>
                </h3>
            </div>

            <section class="panel panel-default" id="card_content">
                <header class="panel-heading">
                    All Role Permissions
                </header>

                        <div class="card mg-b-20" id="card_content">
                            <div class="card-header pb-0">

                                <div style="float: right; padding: 10px;" class="add_button ml-10">
                                    <a href="javascript:void(0)" data-toggle="modal" data-effect="effect-scale" data-target="#create_modal" class="modal-effect btn btn-info mt-1 mb-1"><i class="flaticon2-add-square"></i> Add New Permission</a>
                                </div>

                            </div>

                            <div class="card-body">

                                <div class="row" style="padding-top: 10px;">
                                    <div class="col-md-6" style="padding-bottom: 10px;">
                                        <div class="form-group">
                                            <label for="name" class="col-md-4 control-label">Name:</label>
                                            <div class="col-md-8">
                                                <input name="name" value="{{$role->name}}" type="text" class="form-control" placeholder="" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="guard_name" class="col-md-4 control-label">Guard:</label>
                                            <div class="col-md-8">
                                                <input name="guard_name" value="{{$role->guard_name}}" type="text" class="form-control" placeholder="" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="mt0 mb10" />

                                <div class="container">

                                    <h3> <em> <input type="checkbox"  multiple value="" id="selectAll"> Select All Permissions</em></h3>

                                    <br>
                                    <br>

                                    <div class="row">
                                        @foreach($permissions as $permission)
                                            <div class="col-md-4" style="padding-bottom: 1em">
                                                <input class="permissions" type="checkbox" name="permissions[]" id="permission_{{$permission->id}}" value="{{$permission->id}}" @if(\Spatie\Permission\Models\Role::findById(request()->segment(count(request()->segments())))->hasPermissionTo($permission->id)) checked @endif>
                                                <label class="form-check-label" for="permission_{{$permission->id}}">&nbsp;{{$permission->name}}&nbsp;&nbsp;</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <br>
                                <hr>

                                <div style="margin-bottom: 15px; padding-left: 10px;">
                                    <button id="submitData" class="btn btn-primary"><i class="fa fa-check-square-o"></i>SUBMIT DATA</button>
                                </div>

                            </div>
                </div>

            </section>
        </section>
    </section>
    <!--end::Content-->

@endsection

@section('scripts')


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
                    show_toast('Success', response.message, 'success');
                    setTimeout(function() {
                        refreshPage();
                    }, 200);
                }
            });
        }

        // $(document).on('change', 'input[Id="selectAll"]', function (e) {
        //     alert($(this).val());
        // });

        $('#selectAll').on('change', function (v) {

            let selectAll = $(this);

            if(selectAll.hasClass('checkedAll')) {
                $('.permissions').prop('checked', false)
                selectAll.removeClass('checkedAll');
            }
            else {
                $('.permissions').prop('checked', true)
                selectAll.addClass('checkedAll');
            }
        });

        $('#submitData').on('click', function () {
            let permissions = [];

            $(".permissions:checked").map(function() {
                permissions.push($(this).val());
            }).get().join(',');


            if(permissions.length < 1) {
                show_toast('Caution!', 'Please Select One or More Permissions', 'warning');
                return;
            }


            ajax('{{route('users.roles.permissions.sync')}}', {
                role_id: getLastPathVar(),
                permissions: permissions,
                _token: _token
            }, 'create_modal_content', function (response) {
                if (response.status === 200) {
                    $('#create_modal').modal('hide');
                    resetForm('form_create');
                    show_toast('Success', response.message, 'success');
                    setTimeout(function() {
                        window.location = '{{route('users.roles')}}'
                    }, 200);
                }
            });
        })

        function getLastPathVar() {
            return '{{request()->segment(count(request()->segments()))}}'
        }

    </script>

    @include('includes.datatable-js')

@endsection
