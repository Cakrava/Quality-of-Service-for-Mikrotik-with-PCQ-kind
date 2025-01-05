@php
    $activePage = session()->get('active_page');
@endphp


{{-- Network --}}
@if($activePage == 'clientList')
@include('Component.modal_logout')
@elseif($activePage == 'dashboard')
@include('Component.modal_logout') 
@include('Component.modal_configure_interface') 


@elseif($activePage == 'address')
@include('Component.modal_logout') 
@include('Component.modal_configure_address') 



@elseif($activePage == 'simple_queue')
@include('Component.modal_logout')
@include('Component.modal_configure_simple_queue')

@elseif($activePage == 'queue_type')
@include('Component.modal_logout')
@include('Component.modal_configure_queue_type')
@endif


