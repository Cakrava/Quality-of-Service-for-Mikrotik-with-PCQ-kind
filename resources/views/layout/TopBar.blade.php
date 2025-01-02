@extends('layout.main')
@section('navbar')
   <div class="top_nav">
        
          <div class="nav_menu">
            <nav>
             
            
            </nav>
            <div style="display: flex; justify-content: space-between; align-items: center;margin-top: 10px">
                <h2>Selamat datang,  {{ session('user') }}</h2>
         
            </div>
                
           
        </div>
        </div>
@endsection