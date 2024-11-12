@extends('layouts.theme')
@section('content')
    <!DOCTYPE html>
    <html>

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="assets/images/fav-1.webp" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="assets/css/login.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    </head>

    <body>
        <div class="container-fluid login-fluid">
            <div class="container">
                <div class="login-panal row">
                    <div class="col-md-6 col-sm-6 col-xs-12 p-0">
                        <div class="login-img">
                            <img src="{{ asset('assets/images/logo.png') }}" class="logo-login" alt="logo">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="login">
                            <h4>Welcome Back!</h4>
                            <p class="f-16 custom-text-capitalize">Caritas Community Care Hub</p>
                            @if (session()->has('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {!! session('success') !!}
                                    <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            @if (session()->has('error'))
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                {!! session('error') !!}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                              </div>
                            @endif

                            <form role="form"  method="POST" action="{{ route('admin-login-post') }}" id="login-form"  name="login">
                                @csrf
                                <div class="form-group">
                                    <label for="exampleInputEmail">Username</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend bg-transparent">
                                            <span class="input-group-text bg-transparent border-right-0">
                                                <i class="fa fa-user" aria-hidden="true"></i>
                                            </span>
                                        </div>
                                        <input type="text"
                                            class="form-control border-left-0  @error('email') is-invalid @enderror"
                                            name="email" id="email" placeholder="Email" >
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputPassword">Password</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend bg-transparent">
                                            <span class="input-group-text bg-transparent border-right-0">
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                            </span>
                                        </div>
                                        <input type="password"
                                            class="form-control border-left-0 @error('password') is-invalid @enderror "
                                            name="password" id="password" placeholder="Password" >
                                    </div>
                                </div>

                                <div class="row remember">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <label class="form-check-label text-muted">
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                        </label>
                                                    </div>
                                                </div>

                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-right pb-3">
                                        <a href="{{ route('forgot-password') }}" class="auth-link">Forgot
                                            password?</a>
                                    </div>
                                </div>


                                <div class="">
                                    <button class="btn btn-block btn-primary" type="submit" name="log" id="log"
                                        value="Log In Here">Login </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>
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

            $("#login-form").validate({
                rules: {
                    email: {
                        required: true,
                        email: true,
                    },
                    password: {
                        required: true,
                    },
                },
                messages: {
                    email: {
                        required: "Please your email required",
                        email: "Please enter a vaild email address"
                    },
                    password: {
                        required: "Please your password required"
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
    </script>
@endsection
