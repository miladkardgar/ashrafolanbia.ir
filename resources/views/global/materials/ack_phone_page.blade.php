@extends('layouts.global.global_layout')
@section('content')
    <div class="main-content">

        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-md-push-3">


                        <div class="icon-box mb-0 p-0">

                            <h4 class="notifications"><i class="fa fa-mobile-phone"></i> شماره موبایل خود را تایید کنید
                            </h4>
                        </div>
                        <hr>
                        <!-- new post form -->
                        <form method="GET" class="form register-form" action="{{route('global_profile_send_sms')}}">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="phone">{{__(' شماره موبایل ')}}
                                        <a href="{{route('logout')}}"  style="color: blue!important;"> شماره اشتباه است؟ </a>
                                    </label>
                                    <input id="phone" name="mobile"
                                           value="{{$userInfo['phone']? $userInfo['phone'] :""}}"
                                           {{$userInfo['phone']? 'disabled' :""}} required="required" type="text"
                                           class="form-control "
                                           style="margin-top: 1em"
                                           placeholder="{{__('messages.mobile')}}">

                                </div>
                                <div class="form-group col-md-6 ">

                                    <button class="btn btn-info mt-1" type="submit"
                                    >{{__('کد را دریافت نکردم، مجدد ارسال شود')}}</button>
                                </div>
                            </div>


                        </form>
                        @if($userInfo['code_phone_send'] and (time()-strtotime($userInfo['code_phone_send']))< 320 )
                            <form method="Post" class="form" action="{{route('global_profile_verify_mobile')}}">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-md-12 ">
                                        <label for="activation_code">{{__('کد ارسالی را وارد کنید')}}</label>

                                        <input id="activation_code" required="required" type="text"
                                               class="form-control "
                                               style="margin-top: 1em" name="code"
                                               placeholder="{{__('کد فعال سازی')}}">

                                    </div>
                                    <div class="form-group col-md-12 ">
                                        <button class="btn btn-success btn-lg btn-block mt-1" type="submit">
                                            {{__('تایید')}}</button>
                                    </div>
                                </div>


                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection