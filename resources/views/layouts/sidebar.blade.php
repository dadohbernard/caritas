<!-- <body id="body-pd" class="wireframe">
    <div class="l-navbar" id="nav-bar">
        <nav class="nav sidebar">

            <ul>
                @can('Manage-Members')

                <li><a href="{{ route('manage-members') }}" class="nav_logo"><i class="fa fa-users fa-lg" aria-hidden="true"></i>
                        <span class="nav_logo-name">Manage Beneficiaries</span> </a>
                </li>

              @endcan
              @can('Manage-Supports')
                <li><a href="{{ route('manage-support') }}" class="nav_logo"><i class="fa fa-cog" aria-hidden="true"></i>
                        <span class="nav_logo-name">Manage Supports</span> </a>
                </li>
                @endcan
                @can('Manage-Parish')
                <li><a href="{{ route('manage-parish') }}" class="nav_logo"><i class="fa fa-align-center fa-lg"
                            aria-hidden="true"></i>
                        <span class="nav_logo-name">Manage Parish</span> </a>
                </li>
                @endcan
              @can('Manage-Centrale')
              <li><a href="{{ route('manage-centrales') }}" class="nav_logo"><i class="fa fa-align-center fa-lg" aria-hidden="true"></i>
                <span class="nav_logo-name">Manage Centrale</span> </a>
        </li>
              @endcan
              @can('Manage-community')
              <li><a href="{{ route('manage-community') }}" class="nav_logo"><i class="fa fa-mars-double fa-lg" aria-hidden="true"></i>
                <span class="nav_logo-name">Ecclessial community</span> </a>
        </li>
              @endcan
              @can('Manage-categories')
              <li><a href="{{ route('manage-category') }}" class="nav_logo"><i class="fa fa-folder fa-lg" aria-hidden="true"></i>
                <span class="nav_logo-name">Manage Categories</span> </a>
        </li>
              @endcan
              @can('Manage-Income')
            <li><a href="{{ route('manage-income') }}" class="nav_logo"><i class="fa fa-money" aria-hidden="true"></i>
                    <span class="nav_logo-name">Manage Grant</span> </a>
            </li>
            @endcan
                @can('Manage-Users')

                <li><a href="{{ route('manage-user') }}" class="nav_logo"><i class="fa fa-user-circle" aria-hidden="true"></i>
                        <span class="nav_logo-name">Manage Users</span> </a>
                </li>

              @endcan
              @can('Manage-Roles')
                <li><a href="{{ route('role-list') }}" class="nav_logo"><i class="fa fa-cog" aria-hidden="true"></i>
                        <span class="nav_logo-name">Manage Roles</span> </a>
                </li>
                @endcan

            </ul>
        </nav>
    </div>
</body>




</div>

</div> -->





<body id="body-pd" class="wireframe">
    <div class="l-navbar" id="nav-bar">
        <nav class="nav sidebar">
            <ul>
                @can('Manage-Members')
                <li><a href="{{ route('manage-members') }}" class="nav_logo"><i class="fa fa-users fa-lg" aria-hidden="true"></i>
                        <span class="nav_logo-name">Manage Beneficiaries</span> </a>
                </li>
                @endcan
                
                @can('Manage-Supports')
                <li><a href="{{ route('manage-support') }}" class="nav_logo"><i class="fa fa-cog" aria-hidden="true"></i>
                        <span class="nav_logo-name">Manage Supports</span> </a>
                </li>
                @endcan
                
                @can('Manage-Parish')
                <li><a href="{{ route('manage-parish') }}" class="nav_logo"><i class="fa fa-align-center fa-lg" aria-hidden="true"></i>
                        <span class="nav_logo-name">Manage Parish</span> </a>
                </li>
                @endcan
                
                @can('Manage-Centrale')
                <li><a href="{{ route('manage-centrales') }}" class="nav_logo"><i class="fa fa-align-center fa-lg" aria-hidden="true"></i>
                        <span class="nav_logo-name">Manage Centrale</span> </a>
                </li>
                @endcan
                
                @can('Manage-community')
                <li><a href="{{ route('manage-community') }}" class="nav_logo"><i class="fa fa-mars-double fa-lg" aria-hidden="true"></i>
                        <span class="nav_logo-name">Ecclesial Community</span> </a>
                </li>
                @endcan
                
                @can('Manage-categories')
                <li><a href="{{ route('manage-category') }}" class="nav_logo"><i class="fa fa-folder fa-lg" aria-hidden="true"></i>
                        <span class="nav_logo-name">Manage Categories</span> </a>
                </li>
                @endcan
                
                @can('Manage-Income')
                <li><a href="{{ route('manage-income') }}" class="nav_logo"><i class="fa fa-money" aria-hidden="true"></i>
                        <span class="nav_logo-name">Manage Grant</span> </a>
                </li>
                @endcan
                
                @can('Manage-Users')
                <li><a href="{{ route('manage-user') }}" class="nav_logo"><i class="fa fa-user-circle" aria-hidden="true"></i>
                        <span class="nav_logo-name">Manage Users</span> </a>
                </li>
                @endcan
                
                @can('Manage-Roles')
                <li><a href="{{ route('role-list') }}" class="nav_logo"><i class="fa fa-cog" aria-hidden="true"></i>
                        <span class="nav_logo-name">Manage Roles</span> </a>
                </li>
                @endcan
                
                @can('Support-Predictor')
                <li><a href="{{ route('support-predictor') }}" class="nav_logo"><i class="fa fa-life-ring fa-lg" aria-hidden="true"></i>
                        <span class="nav_logo-name">Support Predictor</span> </a>
                </li>
                @endcan
            </ul>
        </nav>
    </div>
</body>
