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
                @if (session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {!! session('success') !!} <button type="button" class="close" data-dismiss="alert"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button></div>
                    @endif @if (session()->has('error'))
                        <div class="alert alert-danger"> {!! session('error') !!} </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div><br />
                    @endif
                    <div class="card card-header-color">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title }}</h3>
                        </div>

                        <form role="form" id="update-user" action="{{ route('manage-update-profile') }}" name="add-user"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body col-12">
                                <div class="form-group">
                                    <label for="fname">First Name<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="fname" name="first_name"
                                        placeholder="Enter First name"
                                        value="{{ old('first_name') ? old('first_name') : $info->first_name }}">
                                </div>
                                <div class="form-group">
                                    <label for="lname">Last Name<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="lname" name="last_name"
                                        placeholder="Enter Last name"
                                        value="{{ old('last_name') ? old('last_name') : $info->last_name }}">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email Address<span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Enter email" value="{{ old('email') ? old('email') : $info->email }}">
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone_number"
                                        placeholder="Enter Phone Number"
                                        value="{{ old('phone_number') ? old('phone_number') : $info->phone_number }}">
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="role">Role</label>
                                            <select class="form-control" name="role" id="role2">
                                                <option value="">Roles</option>
                                                @foreach ($roles as $key => $role)

                                                <?php $selected = $role->id == $info->role ? 'selected' : ''; ?>
                                                <option value="{{ $role->id }}" <?=$selected ?>>
                                                    {{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-12">
                                    <div class="col-xs-12 col-sm-12">
                                        <label for="profile_pic">Profile Pic</label>
                                        <div class="custom-file">
                                            <input type="file" name="profile_pic" class="custom-file-input"
                                                id="profile_pic">
                                            <img id="blah" />
                                            <p style="color:red;">(File size upto 5MB & allowed png, jpg & jpeg)</p>
                                            <label class="custom-file-label" for="profile_pic">Choose file</label>

                                            <input type="hidden" name="hidden_image" id="hidden_image"
                                                value="{{ $info->profile_picture }}">
                                            {{-- <input type="hidden" name="status" value="{{ $info->status }}"> --}}
                                        </div>
                                    </div>
                                    @if ($info->profile_picture)
                                        <div class="row" id="delimg">
                                            <div class="col-sm-12"><a href="" style="color:#007bff;"
                                                    onclick="window.open('{{ asset($info->profile_picture) }}','targetWindow', 'toolbar=no, location=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1090px, height=550px, top=25px left=120px'); return false;">
                                                    View Profile Picture</a></div>
                                            <div class="col-sm-6">
                                                <a href="#" class="delete-place" style="color:#007bff;">Delete</a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-footer">
                                    <input type="hidden" name="id" id="id" value="{{ $info->id }}">
                                    <button type="submit" class="btn btn-dark btn-lg">Save</button>
                                    <a href="{{ route('dashboard') }}"
                                        class="btn border border-dark btn-lg btn-cancel">Cancel</a>
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

            profile_pic.onchange = evt => {
                const [file] = profile_pic.files
                if (file) {
                    blah.src = URL.createObjectURL(file)
                }
            }

            $('#update-user').validate({
                rules: {
                    fname: {
                        required: true,
                    },
                    lname: {
                        required: true,
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    phone: {
                        minlength: 14,
                    },
                    profile_pic: {
                        extension: "jpeg,jpg,png,gif",
                        maxsize: 5242880 // <- 5 MB
                    },
                    role: {
                        required: true,
                    }

                },
                messages: {
                    fname: {
                        required: "Please enter a first name",
                    },
                    lname: {
                        required: "Please enter a last name",
                    },
                    email: {
                        required: "Please enter a email address",
                        email: "Please enter a vaild email address"
                    },
                    phone: {
                        minlength: "Please enter a valid phone number"
                    },
                    profile_pic: {
                        extension: "Please upload file in these format only (jpg, jpeg, png).",
                        maxsize: "File size must be less than 5 mb."
                    },
                    role: {
                        required: "Please select role"
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
                }
            });
        });

        $(document).ready(function() {
            $('#phone').mask('+000000000000');

            if ($('#role').val() == '1') {
                $('#show_delete_feature').show();
            }


            $(document).on('click', '.delete-place', function() {
                var id = $("#id").val();

                var del_url = "{{ route('manage-user-images-delete') }}";
                swal({
                    title: 'Are you want to delete image ?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#22D69D',
                    cancelButtonColor: '#FB8678',
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
                            data: {
                                id: id
                            },
                            success: function(data) {
                                if (data) {
                                    swal({
                                        title: "Success",
                                        text: "Deleted Successfully.",
                                        type: "success",
                                        confirmButtonColor: "#22D69D"
                                    });
                                    $("#delimg").hide();
                                    $("#hidden_image").val("");

                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
