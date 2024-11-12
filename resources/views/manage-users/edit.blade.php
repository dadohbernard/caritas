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
            <div class="col-10">
                <div class="card card-header-color">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title }}</h3>
                    </div>
                    @if (session()->has('success'))
    <div class="alert msg alert-success alert-dismissible"> {!! session('success') !!} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>
    @endif @if (session()->has('error'))
        <div class="alert msg alert-danger"> {!! session('error') !!} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>
    @endif

                        <form role="form" id="update-employee33" action="{{ route('manage-user-update') }}" name="add-user"
                            name="update-employee" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
<div class ="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label for="category_name">First Name<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="first_name"
                                                    name="first_name" value="{{ $info->first_name }}">
                                                <small
                                                    class="help-block text-danger">{{ $errors->first('first_name') }}</small>
                                            </div>
                                        </div>
                                        <input type="hidden" name="id" value="{{ $info->id }}" id="id">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label for="category_name">Last Name<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="last_name"
                                                    name="last_name" value="{{ $info->last_name }}">
                                                <small
                                                    class="help-block text-danger">{{ $errors->first('employee_last_name') }}</small>
                                            </div>
                                        </div>
                                        <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label for="category_name">Email Id<span
                                                        class="text-danger">*</span></label>
                                                <input type="email" class="form-control" id="email_id" name="email"
                                                    value="{{ $info->email }}">
                                                <small
                                                    class="help-block text-danger">{{ $errors->first('email') }}</small>
                                            </div>

                                        </div>


                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="role">Roles<span class="text-danger">*</span></label>
                                                <select class="form-control" name="role" id="role2">
                                                    <option value="">Roles</option>
                                                    @foreach ($roles as $key => $role)

                                                        <?php $selected = $role->id == $info->role ? 'selected' : ''; ?>
                                                        <option value="{{ $role->id }}" <?= $selected ?>>
                                                            {{ $role->name  }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                        <input type="hidden" name="user_id" id="user_id2"
                                            value="{{ $info->user_id }}">

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="role">Centrale<span class="text-danger">*</span></label>
                                                        <select class="form-control" name="centrale_id" id="center_id">
                                                            <option value="">Select Centrale</option>
                                                            @foreach ($centrales as $key => $centrale)
                                                            <?php $selected = $centrale->id == $info->centrale_id ? 'selected' : ''; ?>
                                                                <option value="{{ $centrale->id }}"  <?= $selected ?>>{{ $centrale->center_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <input type="hidden" name="center_ids" id="center_id" value="">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="role">Community<span class="text-danger">*</span></label>
                                                    <select class="form-control" name="community_id" id="community_id">
                                                        @foreach ($communities as $key => $community)
                                                        <?php $selected = $community->id == $info->community_id ? 'selected' : ''; ?>
                                                            <option value="{{ $community->id }}"  <?= $selected ?>>{{ $community->community_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 mb-3">
                                    <div class="col-xs-12 col-sm-18">
                                        <label for="profile_pic">Profile Pic</label>
                                        <div class="custom-file">
                                            <input type="file" name="profile_pic" class="custom-file-input"
                                                id="profile_pic">
                                            <p style="color:red;">(File size upto 5MB & allowed png, jpg & jpeg)</p>
                                            <label class="custom-file-label" for="profile_pic">Choose file</label>
                                            <input type="hidden" name="hidden_image" id="hidden_image"
                                                value="{{ $info->profile_picture }}">
                                            <input type="hidden" name="status" value="{{ $info->status }}">
                                        </div>
                                    </div>

                                    @if ($info->profile_picture)
                                    @if(File::exists(public_path($info->profile_picture)))
                                        <div class="row" id="delimg">
                                            <div class="col-sm-6"><a href="" style="color:#007bff;"
                                                    onclick="window.open('{{ asset($info->profile_picture) }}','targetWindow', 'toolbar=no, location=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1090px, height=550px, top=25px left=120px'); return false;">
                                                    View Profile Picture </a></div>
                                            <div class="col-sm-6">
                                                <a href="#" class="delete-place" style="color:#007bff;">Delete</a>
                                            </div>
                                        </div>
                                        @endif
                                    @endif
                                </div>
                                <div class="form-group clearfix">
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="radioPrimary1" name="status" value="1"
                                            {{ $info->status == 1 ? 'checked' : '' }}>
                                        <label for="radioPrimary1">Active</label>
                                    </div>
                                    <div class="icheck-danger d-inline">
                                        <input type="radio" id="radioPrimary2" name="status" value="0"
                                            {{ $info->status == 0 ? 'checked' : '' }}>
                                        <label for="radioPrimary2">Inactive</label>
                                    </div>
                                </div>



                                    <div class="float-right">
                                        <button type="submit" class="btn btn-dark btn-lg" id="send_btn2"> <i
                                                class="fa fa-edit"></i>&nbsp; Update</button>
                                        <a href="{{ route('manage-user') }}"
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
            $('#add-user').validate({
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
                    chapter: {
                        required: true,
                    },
                    profile_pic: {
                        extension: "jpeg,jpg,png",
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
            $('#phone').mask('(000) 000-0000');


        $(document).on('click', '.delete-place', function(){
    var id = $("#id").val();

    var del_url = "{{route('manage-user-images-delete')}}";
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
    }).then(function (result) {
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
        data:{
          id:id
        },
        success: function (data) {
          if(data){
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
