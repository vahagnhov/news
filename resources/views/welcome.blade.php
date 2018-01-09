@extends('layouts.app')
@section('links')
    <ul class="nav navbar-nav">
        &nbsp;
        <li class="active"><a href="{{ url('/') }}">Home</a></li>
        <li><a href="{{ url('/news/view') }}">News</a></li>
    </ul>
@endsection
@section('content')
    <div class="container">
        <div class="starter-template">
            <div class="row">
                <h3>Welcome! This is a news site</h3>
            </div>
        </div>
    </div>
    <!-- /.container -->
@endsection
