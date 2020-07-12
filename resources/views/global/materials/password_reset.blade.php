@extends('layouts.global.global_layout')
@section('content')
    <div class="main-content">
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-md-push-3">
                        <h4 class="text-gray mt-0 pt-5"> {{__('messages.password_reset_title')}}</h4>
                        <hr>
                        @if(session()->has('message'))
                            <div class="alert alert-success text-center">
                                {{ session()->get('message') }}
                            </div>
                        @endif
                        @php
                            session()->remove('message')
                        @endphp
                        <p>
                            @if (Auth::check())
                                @php \Illuminate\Support\Facades\Auth::logout() @endphp
                            @else
                            @endif
                        </p>
                        @if(isset($code_sent))
                        <form name="login-form" class="clearfix" method="POST" action="{{route('password_change')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="g-recaptcha-response">
                            <input type="hidden" name="name" value="{{$login}}">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="code">{{__('messages.confirmation_code')}}</label>
                                    <input id="code" name="code" dir="ltr" class="form-control" type="text">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="code">{{__('messages.password')}}</label>
                                    <input id="code" name="password" dir="ltr" class="form-control" type="password">
                                </div>
                            </div>
                            <div class="row">
                                <div class=" col-md-6 col-md-offset-3">
                                    <button type="submit"
                                            class="btn btn-colored  btn-theme-colored p-10 mt-15 btn-block">
                                        {{__('messages.change_password')}}</button>
                                </div>
                            </div>
                        </form>
                        @else
                        <form name="login-form" class="clearfix" method="POST" action="{{route('password_reset_request')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="g-recaptcha-response">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="name">{{__('messages.email_or_mobile')}}</label>
                                    <input id="name" name="name" dir="ltr" class="form-control" type="text">
                                </div>
                            </div>
                            <div class="row">

                                <div class=" col-md-6 col-md-offset-3">
                                    <button type="submit"
                                            class="btn btn-colored  btn-theme-colored p-10 mt-15 btn-block">
                                        {{__('messages.password_reset_request')}}</button>
                                </div>

                            </div>
                        </form>
                        @endif
                        <div class="row">

                            <div class=" col-md-6 col-md-offset-3">

                                <a href="{{route('global_register_page')}}"
                                   class="btn btn-default btn-sm p-10 btn-block">{{__('messages.register')}}</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
@section('footer_js')

    <script src="https://www.google.com/recaptcha/api.js?render={{env('SITE_KEY')}}&hl={{App()->getLocale()=="fa"?'fa':'en'}}"></script>
    <script>
        grecaptcha.ready(function () {
            grecaptcha.execute("{{env('SITE_KEY')}}", {action: 'contact'}).then(function (token) {
                document.querySelector('input[name=g-recaptcha-response]').value = token
            });
        });
    </script>
@endsection


