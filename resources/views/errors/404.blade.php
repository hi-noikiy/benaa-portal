@extends('layouts.app')

@section('title', '800Benaa | Page Not Found')

@section('content')

<div class="block">
    <div class="container">
        <div class="row justify-content-center mt-3">
            <div class="col-12">
                <h1 class="about-us__title">Sorry, the page you are looking for is not found!</h1>        
                <div class="order-success__actions text-center">
                    <a href="{{URL::to('/')}}" class="btn btn-xs btn-primary">Go To Home</a>
                </div>       
            </div>
        </div>
    </div>
</div>
@endsection