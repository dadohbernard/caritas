<header class="header" id="header">
    <div class="header_toggle"><i id="header-toggle" class="fa fa-bars text-block"></i> </div>
    <div class="header_logo"> <a href="{{ url('/dashboard') }}">
        <img src="{{ asset('assets/images/logo.png') }}" alt="" class="logo-img">
    </a> </div>
    <div class="center-digital">
        <h2 style="font-size: 15px;" class="text-uppercase">

            <b>
                {{-- @if(Auth::user()->role == 5)
                President of Parish
                @elseif(Auth::user()->role == 1)
                Admin of
                @elseif(Auth::user()->role == 3)
                President of Diocesse
                @elseif(Auth::user()->role == 2)
                Community chief
                @elseif(Auth::user()->role == 6)
                Father
                @elseif(Auth::user()->role == 4)
                President of Centrale
                @endif --}}
                {{-- @endif
                @endif
                @endif
                @endif --}}
                @if(Auth::user()->role !=1 )Welcome
                @endif
                {{-- to --}}
                {{-- {{ config('app.name') }} --}}
 @if(Auth::user()->role ==1)
 ADMIN PANEL
 @endif
 @if(Auth::user()->role ==5) In
Nyundo Parish
@endif
 @if(Auth::user()->role ==3) In
  Nyundo Diocese
  @endif
                @if(Auth::check())
                @if(Auth::user()->centrale_id !== null )
                @if(Auth::user()->role ==2 )
                 in Community of {{ $data['details']->community_name }}
                @elseif(Auth::user()->role ==4)
                  in Centrale {{ $data['details']->center_name }}

                @endif
                @endif
                @endif

            </b>
        </h2>
    </div>



    <div data-toggle="dropdown" class="row-user-account">
        <div class="header_img">
            <img src="{{ !empty(Auth::user()->profile_picture) ? URL::asset(Auth::user()->profile_picture) : asset('/profile-pictures/1114160.png') }}"
                class="img-circle elevation-2" alt="">
        </div>
        <div class="name-user">{{ Auth::user()->name . ' ' . Auth::user()->last_name }}</div>
        <div class="name-user"><i class="fa fa-caret-down" aria-hidden="true"></i></div>
    </div>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <div class="dropdown-divider"></div>
        <a href="{{ route('manage-edit-profile') }}" class="dropdown-item">Edit Profile</a>
        <a href="{{ route('change-password') }}" class="dropdown-item">Change Password</a>
        {{-- <a href="{{ route('reset') }}" class="dropdown-item">Reset Password</a> --}}
        <div class="dropdown-divider"></div>
        <a href="{{ route('logout') }}" class="dropdown-item">Logout</a>
    </div>
</header>
<br />
<br />
