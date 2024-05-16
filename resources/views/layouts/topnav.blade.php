<!-- Navbar -->
<nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
        <a href="" class="navbar-brand">
            <img src="{{ asset('assets/images/logo/ids_logo_dark.png') }}" alt="LNU IDS Logo" class="brand-image elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">LNU IDS</span>
        </a>

      <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse order-3" id="navbarCollapse">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            @foreach($systems as $row)
            @php
            $system_active = ($row->shorten==$system_selected) ? 'color: #4682b4 !important;font-weight: bold;' : '';
            @endphp
            <li class="nav-item dropdown">
                <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle" style="{{$system_active}}">
                    {{$row->shorten}}
                </a>
                <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                    @foreach($row->navs as $nav)
                        @if(in_array($nav->id,$systems_nav_array))
                            @php
                            if(count($nav->navSubs)>=1){
                                $system_nav_href = '#';
                            }else{
                                $system_nav_href = url('/ids/'.mb_strtolower($row->shorten).'/'.$nav->url.'/n');
                            }
                            @endphp
                            @if(count($nav->navSubs)==0)
                                <li><a href="{{$system_nav_href}}" class="dropdown-item"><i class="{{$nav->icon}} text-info nav-icon"></i> {{$nav->name}} </a></li>
                            @else
                                <li class="dropdown-submenu dropdown-hover">
                                    <a id="dropdownSubMenu2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle"><i class="{{$nav->icon}} text-info nav-icon"></i>
                                        {{$nav->name}}
                                    </a>
                                    <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
                                        @foreach($nav->navSubs as $sub)
                                            @php
                                            $system_nav_sub_href = url('/ids/'.mb_strtolower($row->shorten).'/'.$sub->url.'/s');
                                            @endphp
                                            <li>
                                                <a tabindex="-1" href="{{$system_nav_sub_href}}" class="dropdown-item"><i class="{{$sub->icon}} text-primary nav-icon"></i>
                                                    {{$sub->name}}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                        @endif
                    @endforeach
                </ul>
            </li>
            @endforeach
            @if($user->level_id==1)
                @php
                $system_active = ($system_selected=='USERS') ? 'color: #4682b4 !important;font-weight: bold;' : '';
                @endphp
                <li class="nav-item dropdown">
                    <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle" style="{{$system_active}}">
                        SETTINGS
                    </a>
                    <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                        <li>
                            <a href="{{url('/ids/users/home/n')}}" class="dropdown-item"><i class="fa fa-home text-info nav-icon"></i> Home </a>
                        </li>
                        <li>
                            <a href="{{url('/ids/users/list/n')}}" class="dropdown-item"><i class="fa fa-users text-info nav-icon"></i> Users </a>
                        </li>
                        <li>
                            <a href="{{url('/ids/users/systems/n')}}" class="dropdown-item"><i class="fa fa-cog text-info nav-icon"></i> Systems </a>
                        </li>
                    </ul>
                </li>
            @endif
        </ul>
      </div>

      <!-- Right navbar links -->
      <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
            <i class="fa fa-search"></i>
            </a>
            <div class="navbar-search-block">
            <form class="form-inline">
                <div class="input-group input-group-sm">
                <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-navbar" type="submit">
                    <i class="fa fa-search"></i>
                    </button>
                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                    <i class="fa fa-times"></i>
                    </button>
                </div>
                </div>
            </form>
            </div>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="fa fa-user"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right text-center">
            <a href="#" class="dropdown-item">
                <p class="dropdown-item-header">My Account</p>
                <img src="{{ asset($profile_url) }}" alt="User Avatar" class="mr-3 img-circle" style="height:100px;width:100px;">
                <h4 class="dropdown-item-title">
                    {{$name}}
                </h4>
                <hr class="mb-0">
            </a>
            <div class="container p-3">
            <a href="{{url('logout')}}" class="dropdown-item-button btn-primary ">Logout</a>
            </div>
            <div class="dropdown-divider"></div>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fa fa-arrows-alt"></i>
            </a>
        </li>
      </ul>
  </nav>
  <!-- /.navbar -->

{{--
<style>
.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #f4f6f9;
    padding: 0px 20px;
    margin: 0 1em;
}
.round-bottom-button {
    border-top:none;
    border-bottom: 1px solid transparent;
    border-left: 1px solid transparent;
    border-right: 1px solid transparent;
    background-color: #007bff; /* Button background color */
    color: #fff; /* Button text color */
    padding: 5px 10px; /* Button padding */
    border-radius: 0 0 10px 10px; /* Round only the bottom corners */
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s, border-color 0.3s; /* Smooth transition effect */
}

.round-bottom-button:hover {
    background-color: #fff; /* Lighter background color on hover */
    color: #007bff;  /* Button text color on hover */
    border-color: #007bff; /* Border color on hover */
}
</style>
<div class="top-bar">
    <button class="round-bottom-button">Click me</button>
</div> --}}
