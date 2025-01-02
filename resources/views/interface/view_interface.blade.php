
@extends('layout.menu')

@section('content')
<div class="right_col" role="main">
   <h1>Interface List</h1>
    <ul>
        @foreach ($interfaces as $interface)
            <li>{{ $interface['name'] ?? 'Unnamed Interface' }}</li>
        @endforeach
    </ul>
</div>
@endsection
