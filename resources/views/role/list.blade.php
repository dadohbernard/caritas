@extends('layouts.app')

@section('content')
    <style>
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            z-index: -1;
            width: 100vw;
            height: 100vh;
            background-color: #000;
        }

        .delete-role {

            width: 100% !important;

        }
    </style>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 mt-5">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Settings</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
                <!-- <div class="col-sm-6">
                                                        <h1>{{ $title }}</h1>
                                                    </div> -->
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">
                        <div class="col-sm-3" style="margin-bottom: 10px;">
                            <!-- <a href="{{ route('role-add') }}" class="btn btn-block btn-outline-dark">Add Role</a> -->
                            <a href="{{ route('role-add') }}" class="btn btn-success">
                                <i class="fa fa-plus"></i>&nbsp;Add Role</a>
                        </div>
                        <table id="role-list" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Role</th>
                                    <th>Permissions</th>
                                    <th>created</th>
                                    <th>modified</th>
                                    <th>status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
    <div class="modal fade" id="delete-role-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content delete-role">
                <div class="modal-body">
                    <p>Are you sure you want to delete this role?</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="confirm-delete" data-dismiss="modal">OK</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection
@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <style type="text/css">
        .pointer {
            cursor: pointer;
        }
    </style>
@endsection
@section('script')
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>

    <script type="text/javascript">
        var dataTable = $('#role-list').DataTable({
            "processing": true,
            "serverSide": true,
            "dom": '<"pull-left"f><"pull-right"l>tip',
            "pageLength": 10,
            "bPaginate": true,
            "bLengthChange": true,
            "responsive": true,
            "searching": true,
            "bInfo": true,
            "aaSorting": [],
            "ajax": "{{ route('role-datatable') }}",
            "fnDrawCallback": function() {
                $('.toggle-class').bootstrapToggle();
            },
            "columns": [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'permissions',
                    name: 'permissions'
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    "render": function(data) {
                        var nData = (data != null) ? moment(data).format('DD/MM/YYYY') : '';
                        return nData;
                    }
                },
                {
                    data: 'updated_at',
                    name: 'updated_at',
                    "render": function(data) {
                        var nData = (data != null) ? moment(data).format('DD/MM/YYYY') : '';
                        return nData;
                    }
                }, {
                    data: 'status',
                    name: 'status',
                },
                {
                    data: 'action',
                    name: 'action',
                    class: 'text-center',
                    sortable: false
                },
            ],
            'columnDefs': [{
                'visible': false,
                'targets': [0]
            }],
            'order': [
                [0, 'desc']
            ]
        });
        $(document).on('click', '.delete-role', function() {
            $('#confirm-delete').attr('data-role-id', $(this).data('role-id'));
            $('#delete-role-modal').modal('show');
        });
        $(document).on('click', '#confirm-delete', function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "{{ route('role-delete') }}",
                data: {
                    role_id: $('#confirm-delete').data('role-id')
                },
                success: function(data) {
                    $('#confirm-delete').attr('data-role-id', '');
                    if (data.status == 'success') {
                        toastr.success(data.message)
                        $('#role-list').DataTable().draw();
                    } else {
                        toastr.error(data.message)
                    }
                }
            });


        });
        $(document).on('change', '.toggle-class', function() {
            var id = $(this).attr('data-id');
            var status_url = $(this).attr('data-url');
            if ($(this).is(":checked")) {
                var status = 1;
                var statusname = "Activate";
            } else {
                var status = 0;
                var statusname = "De-activate";
            }
            swal({
                title: 'Are you sure want to ' + statusname + '?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#E1261C',
                cancelButtonColor: '#EBEBEB',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                confirmButtonClass: 'btn',
                cancelButtonClass: 'btn',
            }).then(function(result) {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        url: status_url,
                        data: {
                            id: id,
                            status: status
                        },
                        success: function(data) {
                            if (data) {
                                swal({
                                    title: "Success",
                                    text: "Status Updated Successfully.",
                                    type: "success",
                                    confirmButtonColor: '#E1261C',
                                });
                                $('#role-list').DataTable().draw();
                            }
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                } else {
                    $("#role-list").DataTable().draw();
                }
            });
        });
    </script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
@endsection
