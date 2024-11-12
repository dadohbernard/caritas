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
<div class="col-md-12 mb-3 parent">

    <div class="blinking-circle">{{ $amount }}RWF</div>
</div>
@endsection
