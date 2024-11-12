@extends('layouts.app')

@section('content')
<div class="content-wrapper">
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
              <center>
                <h1 class="m-0 text-dark">{{ $title }}</h1>
              </center>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active">Change Password</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
  <div class="row">
    <div class="col-md-6">
        @if (count($errors) > 0)
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif 
        @if(session()->has('success'))  
            <div class="alert alert-success"> {!! session('success') !!} </div>
        @endif @if(session()->has('error')) 
            <div class="alert alert-danger"> {!! session('error') !!} </div>  
        @endif
      <div class="card card-header-color">
        <form id="change-password-form" action="{{ route('change-password') }}" method="POST">
			  	@csrf
			    <div class="card-body">
            <div class="row">
			    		<div class="col-sm-12">
			    			<div class="form-group">
								<label for="password">Current password</label>
								<input type="password" class="form-control" id="password" name="current_password" placeholder="current Password">
								<small class="help-block text-danger">{{ $errors->first('current_assword') }}</small>
							</div>
			    		</div>
			    	</div>
			    	<div class="row">
			    		<div class="col-sm-12">
			    			<div class="form-group">
								<label for="password">Password</label>
								<input type="password" class="form-control" id="password" name="password" placeholder="Password">
								<small class="help-block text-danger">{{ $errors->first('password') }}</small>
							</div>
			    		</div>
			    	</div>
			    	<div class="row">
			    		<div class="col-sm-12">
			    			<div class="form-group">
								<label for="confirm_password">Confirm Password</label>
								<input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Password">
								<small class="help-block text-danger">{{ $errors->first('confirm_password') }}</small>
							</div>
			    		</div>
			    	</div>
			    </div>
			    <!-- /.card-body -->
			    <div class="card-footer">
			      <button type="submit" class="btn btn-dark btn-lg">Save</button>
			      <a href="{{ route('dashboard') }}" class="btn border border-dark btn-lg btn-cancel">Cancel</a>
			    </div>
			  </form>
      </div>
    </div>
  </div>
</section>
</div>

@endsection
@section('script')
<script type="text/javascript">
	$('#change-password-form').bootstrapValidator({
		fields: {
			password: {
                validators: {
                    notEmpty: {
                        message: 'The password is required and cannot be empty'
                    },
                    identical: {
                        field: 'confirm_password',
                        message: 'The password and its confirm must be the same'
                    },
                    stringLength: {
                        max: 50,
                        message: 'Password must be less than 50 characters'
                    }
                }
            },
			confirm_password: {
                validators: {
                    notEmpty: {
                        message: 'The confirm password is required and cannot be empty'
                    },
                    identical: {
                        field: 'password',
                        message: 'The password and its confirm must be the same'
                    }
                }
            }
		}
	});
</script>
@if(session('success'))
<script type="text/javascript">
  toastr.success("{{ session('success') }}")
</script>
@endif
@endsection