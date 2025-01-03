@extends('layout.main')
@section('navbar')
   <div class="top_nav">
        
          <div class="nav_menu">
          
            <div style="display: flex; justify-content: space-between; align-items: center;margin-top: 10px;padding-left : 20px">
                <h2 style="color: #264d4a;font-weight :bold">Selamat datang,  {{ session('user') }}</h2>
         
            </div>
                
           
        </div>
        </div>
@endsection