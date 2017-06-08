@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>All Accessible Tasks</h1>
        <em>{{Auth::user()->name}}</em>


    </div>
@endsection