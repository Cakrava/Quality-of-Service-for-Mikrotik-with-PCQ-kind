
{{-- @extends($navbar) --}}
@extends('layout.TopBar') 
@section('menu-content')

<div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="index.html" class="site_title" style="background-color: #ecedee; display: flex; justify-content: center; align-items: center;">
                <img src="{{ asset('asset/image/logo1.png') }}" alt="PCQ Configure" style="width: auto; height: 50px; margin-top: 10px">
              </a>
            </div>

            <div class="clearfix"></div>

      
            <!-- sidebar menu -->
           <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <br>
  <div class="menu_section">
    <h3>General</h3>
    <ul class="nav side-menu">
      <!-- Dashboard: Tampilan Umum -->
      <li><a><i class="fa fa-dashboard"></i> Dashboard <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="{{ route('dashboard.dashboard') }}">Tampilan Utama</a></li>
        </ul>
      </li>

      <!-- Menu Pengelolaan Data Guru -->
      <li><a><i class="fa fa-wifi"></i>Network<span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="{{ route('network.address') }}">IP Address</a></li>
          <li><a href="">DHCP Server</a></li>
          <li><a href="{{ route('network.clientList') }}">Client List</a></li>
        </ul>
      </li>
      <li><a><i class="fa fa-bars"></i>Quality of Services<span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="{{ route('qos.simple_queue') }}">Simple Queue</a></li>
          <li><a href="{{ route('qos.queue_type') }}">Queue Profile</a></li>
        </ul>
      </li>

      <!-- Menu Pengelolaan Data Siswa -->
      <li><a><i class="fa fa-stack-exchange"></i> System <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="tambah_siswa.html">User</a></li>
         
        </ul>
      </li>

    </ul>
  </div>
</div>

          
            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
            
              
             
           
              
              
              <a title="Logout" data-toggle="modal" data-target="#customModal">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
              

<!-- Placeholder for modal content -->

    
            </div>
            <!-- /menu footer buttons -->
            
          </div>


@endsection