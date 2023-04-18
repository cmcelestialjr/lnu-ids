<!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary" style="background-color: #1A1A1A;">
    <!-- Brand Logo -->
    <a href="" class="brand-link">
      <img src="{{ asset('assets/images/logo/ids_logo_dark.png') }}" alt="IDS logo" class="brand-image">
      <span class="brand-text font-weight-light brand-wordmark">LNU IDS V1.0</span>
      <hr class="mt-4 mb-0" style="background: #333333;">
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset($profile_url) }}" class="img-circle" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{$name}}</a>
        </div>
      </div>
      <hr class="mt-0 mb-4" style="background: #333333;">

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <p class="brand-text left-sidebar-header">My Modules</p>
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          @foreach($systems as $row)
            @php
            if($row->shorten==$system_selected){
                $system_menu_open = 'menu-open';
                $system_active = 'active';
                $system_text = 'text-default';
            }else{
                $system_menu_open = '';
                $system_active = '';
                $system_text = 'text-primary';
            }
            @endphp
            <li class="nav-item {{$system_menu_open}}">
                <a href="#" class="nav-link {{$system_active}}">
                    <i class="nav-icon {{$row->icon}} {{$system_text}}"></i>
                    <p class="ml-2">
                        {{$row->shorten}}
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @foreach($row->navs as $nav)
                        @if(in_array($nav->id,$systems_nav_array))
                        @php                        
                        if(count($nav->navSubs)>=1){
                            $system_nav_href = '#';
                            $nav_url = array();
                            foreach($nav->navSubs as $sub){
                                $nav_url[] = $sub->url;
                            }
                            if(in_array($nav_selected,$nav_url) && $row->shorten==$system_selected){
                                $system_nav_menu_open = 'menu-open';
                                $system_nav_active = 'active';
                            }else{
                                $system_nav_menu_open = '';
                                $system_nav_active = '';
                            }
                        }else{
                            
                            $system_nav_href = url('/ids/'.mb_strtolower($row->shorten).'/'.$nav->url.'/n');
                            if($nav->url==$nav_selected && $row->shorten==$system_selected){
                                $system_nav_menu_open = '';
                                $system_nav_active = 'active';
                            }else{
                                $system_nav_menu_open = '';
                                $system_nav_active = '';
                            }
                        }
                        @endphp
                        <li class="nav-item {{$system_nav_menu_open}}">
                            <a href="{{$system_nav_href}}" class="nav-link {{$system_nav_active}}">
                                &nbsp; <i class="{{$nav->icon}} text-info nav-icon"></i>
                                    <p class="ml-3">{{$nav->name}}</p>
                                @if(count($nav->navSubs)>=1)
                                    <i class="fas fa-angle-left right"></i>
                                @endif
                            </a>                        
                            @foreach($nav->navSubs as $sub)
                                @php
                                $system_nav_sub_href = url('/ids/'.mb_strtolower($row->shorten).'/'.$sub->url.'/s');
                                if($sub->url==$nav_selected && $row->shorten==$system_selected){
                                    $system_nav_sub_active = 'active';
                                }else{
                                    $system_nav_sub_active = '';
                                }
                                @endphp
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{$system_nav_sub_href}}" class="nav-link {{$system_nav_sub_active}}">
                                        &nbsp; &nbsp; &nbsp;<i class="far fa-circle text-orange nav-icon"></i>
                                        <p>{{$sub->name}}</p>
                                        </a>
                                    </li>
                                </ul>
                            @endforeach
                        </li>
                        @endif
                    @endforeach
                </ul>
            </li>
          @endforeach
          @if($user->level_id==1)
            @php
            if($system_selected=='USERS'){
                $system_menu_open = 'menu-open';
                $system_active = 'active';
            }else{
                $system_menu_open = '';
                $system_active = '';
            }
            if($nav_selected=='home' && $system_selected=='USERS'){
                $system_nav_sub_active_home = 'active';
            }else{
                $system_nav_sub_active_home = '';
            }
            if($nav_selected=='list' && $system_selected=='USERS'){
                $system_nav_sub_active_list = 'active';
            }else{
                $system_nav_sub_active_list = '';
            }
            if($nav_selected=='systems' && $system_selected=='USERS'){
                $system_nav_sub_active_default = 'active';
            }else{
                $system_nav_sub_active_default = '';
            }
            @endphp
            <p class="brand-text left-sidebar-header mt-4">Settings</p>
            <li class="nav-item {{$system_menu_open}}">
                <a href="#" class="nav-link {{$system_active}}">
                    <i class="nav-icon fa fa-users"></i>
                    <p>
                        Users
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{url('/ids/users/home/n')}}" class="nav-link {{$system_nav_sub_active_home}}">
                            &nbsp; <i class="fa fa-home text-info nav-icon"></i>
                            <p>
                                Home
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url('/ids/users/list/n')}}" class="nav-link {{$system_nav_sub_active_list}}">
                            &nbsp; <i class="fa fa-users text-info nav-icon"></i>
                            <p>
                                List
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url('/ids/users/systems/n')}}" class="nav-link {{$system_nav_sub_active_default}}">
                            &nbsp; <i class="fa fa-cog text-info nav-icon"></i>
                            <p>
                                Systems
                            </p>
                        </a>
                    </li>
                </ul>
            </li>
            <br><br>
          @endif         
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>