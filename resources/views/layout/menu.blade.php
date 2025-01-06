
{{-- @extends($navbar) --}}
@extends('layout.TopBar') 
@section('menu-content')

<div class="left_col scroll-view" >

  <div class="navbar nav_title" style="border: 20;border-color :#fff;margin-top : 20px;margin-bottom : -20px">
    <a  class="site_title" style="background-color: #fff; display: flex; justify-content: center; align-items: center;">
      <img src="{{ asset('asset/image/logo1.png') }}" alt="PCQ Configure" style="width: auto; height: 60px; margin-bottom: 10px; margin-top: 10px">
    </a>
  </div>


            <div class="clearfix"></div>



            <!-- sidebar menu -->
           <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <br>
  <div class="menu_section">
   
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
          
          <li><a href="{{ route('network.clientList') }}">DHCP Client</a></li>
          <li><a href="{{ route('network.trafficUsage') }}">Traffic Data</a></li>
        </ul>
      </li>
      <li><a><i class="fa fa-bars"></i>Quality of Services<span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="{{ route('qos.simple_queue') }}">Simple Queue</a></li>
          <li><a href="{{ route('qos.queue_type') }}">Queue Profile</a></li>
        </ul>
      </li>
      <li><a><i class="glyphicon glyphicon-off "></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;System<span class="fa fa-chevron-down"></span></a>
       <ul class="nav child_menu">
         <li> <a  data-toggle="modal" data-target="#customModal">Logout</a></li>
         
        </ul>
      </li>

    

    </ul>
  </div>
</div>

          
            <!-- /menu footer buttons -->
            
          </div>
          


@endsection