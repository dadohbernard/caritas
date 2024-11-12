@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="left-side-content">
        <div class="row mb-2">
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('manage-user') }}">Manager user</a></li>
                    <li class="breadcrumb-item active">{{ $title }}</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
    <div class="container-fluid">
        <div class="row mt-4">
            <div class="col-6">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show"> <button type="button" class="close"
                            data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button> {!! session('success') !!} </div>
                @endif
                <div class="card card-header-color">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title }}</h3>
                    </div>



                    <form role="form" id="change_password" action="{{ route('change-password-post') }}" name="add-user"
                        method="POST">
                        @csrf
                        <div class="card-body col-lg-12 col-sm-12">
                            <div class="form-group">
                                <label for="current_password">Enter old password<span
                                        class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="current_password" name="current_password"
                                    placeholder="Enter old password" value="">
                            </div>
                            <div class="form-group">
                                <label for="new_password">New password<span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="new_password" name="new_password"
                                    placeholder="Enter new password" value="">
                            </div>
                            <div class="form-group">
                                <label for="new_confirm_password">Confirm new password<span
                                        class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="new_confirm_password"
                                    name="new_confirm_password" placeholder="Confirm new password" value="">
                            </div>



                            <div class="card-footer">

                                <button type="submit" id='change_pswd_btn' class="btn btn-dark btn-lg">Change password</button>
                                <a href="{{ route('dashboard') }}" class="btn border border-dark btn-lg btn-cancel">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('style')
    <!-- Bootstrap4 Duallistbox -->
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') }}">
@endsection

@section('script')
    <script src="{{ asset('plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.mask.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            $('#change_password').validate({
                rules: {
                    current_password: {
                        required: true,
                    },
                    new_password: {
                        required: true,
                    },
                    new_confirm_password: {
                        required: true,
                    }
                },
                messages: {
                    current_password: {
                        required: "Please enter old password",
                    },
                    new_password: {
                        required: "Please enter new password",
                    },
                    new_confirm_password: {
                        required: "Please confirm new password",
                    }

                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
                submitHandler: function(form, e) {
                    e.preventDefault();
                    console.log('Form submitted');
                    var form_data = new FormData();

                    var current_password = $('#current_password').val();

                    form_data.append('current_password', current_password);

                    var new_password = $('#new_password').val();

                    form_data.append('new_password', new_password);

                    var new_confirm_password = $('#new_confirm_password').val();

                    form_data.append('new_confirm_password', new_confirm_password);

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: "{{ route('change-password-post') }}",
                        type: "POST",
                        dataType: "json",
                        data: form_data,
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function() {
                            $('#change_pswd_btn').html(
                                "<i class='fa fa-spin fa-spinner'></i> Change password"
                            );
                        },
                        success: function(result) {
                            $('#change_pswd_btn').html("Change password");
                            window.location.href = '';
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                    return false;
                }
            });
        });
    </script>
@endsection
