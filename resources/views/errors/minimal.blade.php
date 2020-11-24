@extends('layouts.global.global_layout')


@section('content')

    <!-- Start main-content -->
    <div class="main-content">

        <div class="container">
            <div class="left-section">
                <div class="inner-content text-center">
                    <h1 class="heading text-info">@yield('code')</h1>
                    <p class="subheading text-muted">@yield('message')</p>
                    <img src="/public/images/spotlight.png" alt="error">
                </div>
            </div>
        </div>

    </div>
    <!-- end main-content -->
@endsection
