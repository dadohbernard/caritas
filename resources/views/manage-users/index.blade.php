@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="left-side-content">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    @if (session()->has('success'))
        <div class="alert msg alert-success alert-dismissible"> {!! session('success') !!} <a href="#" class="close"
                data-dismiss="alert" aria-label="close">&times;</a></div>
        @endif @if (session()->has('error'))
            <div class="alert msg alert-danger"> {!! session('error') !!} <a href="#" class="close"
                    data-dismiss="alert" aria-label="close">&times;</a></div>
        @endif
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <div class="row">
                                <div class="col-md-8">

                                    <a href="{{ route('manage-user-add') }}" class="btn btn-success">
                                        <i class="fa fa-plus"></i>&nbsp;Add User</a>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-sm-6">Role Filter:</div>
                                        <div class="col-sm-6">
                                            <form method="get" class="" role="form">
                                                <div class="form-group">
                                                    <select class="form-control form-control-sm" name="r"
                                                        id="r" onchange="this.form.submit();">
                                                        <option value="">---All---</option>
                                                        @foreach ($roles as $key => $val)
                                                            <option value="{{ $val['name'] }}"
                                                                {{ $role == $val['name'] ? 'selected' : '' }}>
                                                                {{ $val['name'] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <table id="manage-users" class="table table-bordered">
                                    <thead>
                                        <tr class="table-row">
                                            <th>id</th>
                                            <th>FIRST NAME</th>
                                            <th>LAST NAME</th>
                                            {{-- <th>PHONE NUMBER</th> --}}
                                            <th>EMAIL</th>
                                            <th>ROLE</th>
                                            <th>Centrale</th>
                                            <th>Community</th>
                                            <th>MODIFIED</th>
                                            <th>View Activities</th>
                                            <th>STATUS</th>
                                            <th>ACTION</th>

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
        <div class="modal fade" id="reset" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalToggleLabel"> </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p id="msg"></p>
                        <form id="sent-link" method="POST" action="{{ route('reset-post') }}">
                            @csrf
                            {{-- @foreach ($users as $user) --}}
                            <div class="form-group">

                                <div class="form-group-field">
                                    <input id="email-data" type="hidden"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value=" " required autocomplete="email" autofocus>
                                </div>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="modal-footer">
                                <button id="send-link" type="submit"
                                    class="btn btn-primary btn-block">{{ __('Send Reset Link') }}</button>

                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <!-- /.content -->
    @endsection
    @section('style')
        <link rel="stylesheet" type="text/css" href="{{ asset('custom/datatables/css/dataTables.bootstrap4.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('custom/datatables_custom.css') }}">
    @endsection
    @section('script')
        <script type="text/javascript" src="{{ asset('custom/datatables/js/jquery.dataTables.js') }}"></script>
        <script type="text/javascript" src="{{ asset('custom/datatables/js/dataTables.bootstrap4.js') }}"></script>
        <script type="text/javascript" src="{{ asset('custom/datatables/js/dataTables.rowReorder.js') }}"></script>
        <script type="text/javascript" src="{{ asset('custom/datatables/js/dataTables.scroller.js') }}"></script>
        <script type="text/javascript" src="{{ asset('custom/datatables_custom.js') }}"></script>
        <script type="text/javascript">
            $(document).ready(function() {

                $('#manage-users').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "dom": '<"pull-left"f><"pull-right"l>tip',
                    "pageLength": 25,
                    "searching": true,
                    "bPaginate": true,
                    "bLengthChange": true,
                    "bInfo": true,
                    "aaSorting": [],
                    "order": [
                        [0, "desc"]
                    ],

                    "ajax": "{{ route('getUserListAjax', ['role' => $role]) }}",
                    "dataSrc": "data",
                    "fnDrawCallback": function() {
                        $('.toggle-class').bootstrapToggle();
                    },
                    "columns": [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'first_name',
                            name: 'first_name'

                        },
                        {
                            data: 'last_name',
                            name: 'last_name'
                        },

                        // {
                        //     data: 'phone_number',
                        //     name: 'phone_number'
                        // },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'roles',
                            name: 'roles'
                        },

                        {
                            data: 'center_name',
                            name: 'center_name'

                        },
                        {
                            data: 'community_name',
                            name: 'community_name'
                        },

                        {
                            data: 'updated_at',
                            name: 'updated_at',
                            "render": function(data) {
                                var nData = (data != null) ? moment(data).format('DD/MM/YYYY') : '';
                                return nData;
                            }
                        },
                        {
                            data: 'activity',
                            name: 'activity'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            sortable: false
                        },
                    ],
                    'columnDefs': [{
                            responsivePriority: 1,
                            targets: 5
                        },
                        {
                            responsivePriority: 2,
                            targets: 10
                        },
                        {
                            'visible': false,
                            'targets': [0]
                        }
                    ],
                    'order': [
                        [0, 'desc']
                    ]
                });

                $(document).on('click', '.delete-user', function() {
                    var id = $(this).attr('data-id');
                    var del_url = $(this).attr('data-url');
                    swal({
                        title: 'Are you sure?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#E1261C',
                        cancelButtonColor: '#EBEBEB',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'No, cancel!',
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
                                type: "DELETE",
                                dataType: 'json',
                                url: del_url,
                                success: function(data) {
                                    if (data) {
                                        swal({
                                            title: "Success",
                                            text: "Deleted Successfully.",
                                            type: "success",
                                            confirmButtonColor: '#E1261C',
                                        });
                                        $('#manage-users').DataTable().draw();
                                    }
                                }
                            });
                        }
                    });
                });
            });
            //activate or inactive user
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
                            beforeSend: function() {
                                $('.loader1').show();
                            },
                            success: function(data) {
                                $('.loader1').hide();
                                if (data) {
                                    swal({
                                        title: "Success",
                                        text: "Status Updated Successfully.",
                                        type: "success",
                                        confirmButtonColor: '#E1261C',
                                    });
                                    $('#manage-users').DataTable().draw();
                                }
                            }
                        });
                    } else {
                        $("#manage-users").DataTable().draw();
                    }
                });
            });
        </script>
    @endsection
