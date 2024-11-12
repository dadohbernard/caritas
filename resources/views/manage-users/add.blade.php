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
            <div class="col-8">
                <div class="card card-header-color ">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title }}</h3>
                    </div>
                    @if (session()->has('success'))
    <div class="alert msg alert-success alert-dismissible"> {!! session('success') !!} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>
    @endif @if (session()->has('error'))
        <div class="alert msg alert-danger"> {!! session('error') !!} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>
    @endif
                    <form role="form" id="add-user" action="{{ route('manage-user-save') }}" name="add-category"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="category_name">First Name<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="first_name2" name="first_name">
                                        <small class="text-danger">{{ $errors->first('first_name') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="category_name">Last Name<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="last_name2" name="last_name">
                                        <small class="text-danger">{{ $errors->first('last_name') }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="category_name">Email Id<span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email2" name="email">
                                        <small class="text-danger">{{ $errors->first('email') }}</small>
                                    </div>
                                </div>



                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="role">Roles<span class="text-danger">*</span></label>
                                        <select class="form-control" name="role" id="role2">
                                            <option value="">Roles</option>
                                            @foreach ($roles as $key => $role)
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="role">Centrale<span class="text-danger">*</span></label>
                                        <select class="form-control" name="centrale_id" id="center_id">
                                            <option value="">Select Centrale</option>
                                            @foreach ($centrales as $key => $centrale)
                                                <option value="{{ $centrale->id }}">{{ $centrale->center_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <input type="hidden" name="center_ids" id="center_id" value="">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role">Community<span class="text-danger">*</span></label>
                                    <select class="form-control" name="community_id" id="community_id">

                                    </select>
                                </div>
                            </div>
                        </div>
                            </div>

                                <div class="col-md-6 mb-3 form-group">
                                    <label for="profile_pic">Profile Pic</label>
                                    <div class="custom-file">
                                        <input type="file" name="profile_pic" class="custom-file-input"
                                            id="profile_pic" accept="image/*">
                                        <img id="blah" />
                                        <p style="color:red;">(File size upto 5MB & allowed png, jpg & jpeg)</p>
                                        <label class="custom-file-label" for="profile_pic">Choose file</label>
                                    </div>
                                </div>
                                <br><br>
                                <div class="col-md-6 mb-3 form-group clearfix">
                                    <label for="profile_pic">Status</label><br><br>
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="radioPrimary1" name="status" checked=""
                                            value="1">
                                        <label for="radioPrimary1">Active</label>
                                    </div>
                                    <div class="icheck-danger d-inline">
                                        <input type="radio" id="radioPrimary2" name="status" value="0">
                                        <label for="radioPrimary2">Inactive</label>
                                    </div>
                                </div>

                            <div class="float-left col-md-6 mb-4">
                                <button type="submit" class="btn btn-dark btn-lg" id="send_btn2"> <i
                                        class="fa fa-plus"></i>&nbsp; Save</button>
                                <a href="{{ route('manage-user') }}"
                                    class="btn border border-dark btn-lg employeesclosee btn-cancel">Cancel</a>

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
    <!-- Bootstrap4 Duallistbox -->
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

            $('#add-user').validate({
                rules: {
                    first_name: {
                        required: true,
                    },
                    last_name: {
                        required: true,
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    phone: {
                        minlength: 14,
                    },
                    centrale_id:{
                        required: true,
                    },
                    community_id:{
                        required: true,
                    },
                    profile_pic: {
                        extension: "jpeg,jpg,png",
                        maxsize: 5242880 // <- 5 MB
                    },
                    role: {
                        required: true,
                    },

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
                    centrale_id:{
                        required: "Please select centrale",
                    },
                    community_id:{
                        required: "Please select community",
                    },
                    profile_pic: {
                        extension: "Please upload file in these format only (jpg, jpeg, png).",
                        maxsize: "File size must be less than 5 mb."
                    },
                    role: {
                        required: "Please select role"
                    },

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
            $('#phone').mask('(000) 000-0000');
        });

        function resetForm() {
            document.getElementById("add-user").reset();
        }
        $(document).on('change', '#center_id', function() {

var center = $(this).val();
$("#center_ids").val(center);
console.log(center)
var form_data = new FormData();

form_data.append('center_ids', center);


$.ajaxSetup({
    headers: {
        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
    }
});

$.ajax({
    url: "{{ route('view-community') }}",
    type: "POST",
    dataType: "json",
    data: form_data,
    cache: false,
    contentType: false,
    processData: false,
    beforeSend: function() {
        $('#community_id').empty().append('<option value="">Loading...</option>');
    },
    success: function(result) {
        $('#community_id').empty().append(
            '<option value="">Select community</option>');
        if (result.data != '') {
            $.each(result.data, function(key, val) {
                $('#community_id').append($('<option>', {
                    value: val.id,
                    text: val.community_name
                }));
            });
        }
        // $('#cert-id').append($('<option>', {
        //     value: "Other",
        //     text: "Other"
        // }));

        //    console.log(result);
    },
    error: function(error) {
        console.log(error);
    }
});
});
    </script>
@endsection
