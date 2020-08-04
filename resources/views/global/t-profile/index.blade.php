@extends('global.t-profile.frame')
<?php $active_sidebar = ['dashboard'] ?>
@section('mrn-content')


    <div class="mrn-status-user-widget">
        <ul>
            <li class="all_bills w-50">
                <div class="key_wrapper">
                    <span class="icon"><i class="fa fa-certificate"></i></span>
                    <span class="icon-number"><h2 class="">{{$paidPeriodCount}}</h2></span>
                    <span class="mrn-amount">

								<span class="">مورد</span></span><span class="title">پرداخت شده</span>
                </div>
            </li>
            <li class="all_bills w-50">
                <div class="key_wrapper">
                    <span class="icon"><i class="fa fa-clock-o"></i></span>
                    <span class="icon-number"><h2 class="">{{$unpaidPeriodCount}}</h2></span>
                    <span class="mrn-amount">


				<span class="wc-Symbol">مورد</span></span><span class="title">در انتظار پرداخت</span>
                </div>
            </li>

        </ul>
    </div>

{{--    <div class="mrn-notifications-box-green">--}}
{{--        <h4 class="notifications"><i class="fa fa-bell-o"></i>  اطلاعیه </h4>--}}

{{--        <ul class="list-unstyled">--}}
{{--            <li class="announce-read">--}}
{{--                <div class="notifications-content">--}}
{{--                    <p>--}}
{{--                        فراخوان کمک برای ساخت 8 مدرسه در مناطق زلزله زده--}}
{{--                    </p>--}}

{{--                </div>--}}
{{--            </li>--}}
{{--        </ul>--}}
{{--    </div>--}}
@if(isset($period))
    <div class="mrn-notifications-box">
        <h2 class="notifications"> کمک ماهیانه</h2>

        <ul class="list-unstyled">
            <li class="announce-read">
                <div class="notifications-content">
                    <span class="notif-date">
                        تاریخ شروع:
                        <span>{{miladi_to_shamsi_date($period['start_date'])}}</span>
                    </span>
                    <br>
                    <span class="notif-date">
                        دوره:
                        <span>{{__("words.monthly_".$period['period'])}}</span>
                    </span>
                    <h3>نوبت بعدی پرداخت:
                    <span class="text-black-50">{{jdate('l',strtotime($period['next_date']))}} | {{miladi_to_shamsi_date($period['next_date']) }} </span>
                    </h3>
                    <p>شما میتوانید مبلغ و دوره زمانی تعهد خود را از <a href="{{route('t_routine_vow')}}"><span
                                    style="color: #0000ff;">اینجا</span> </a> ویرایش کنید.</p>
                    <p style="text-align: center;">
                    </p></div>
            </li>
        </ul>
    </div>
@else
    <div class="mrn-notifications-box">
        <h2 class="notifications">تعهد پرداخت مستمر شما فعال نیست</h2>

        <ul class="list-unstyled">
            <li class="announce-read">
                <div class="notifications-content">
                    <span class="notif-date">
                        شما میتوانید با ثبت تعهد پرداخت منظم ما را در برنامه ریزی برای کمک موثرتر به خانواده های نیازمند یاری کنید.
                    </span>

                    <p>شما میتوانید از <a href="{{route('t_routine_vow')}}"><span
                                    style="color: #0000ff;">اینجا</span> </a> کمک دوره ای خود را فعال کنید.</p>
                    <p style="text-align: center;">
                    </p></div>
            </li>
        </ul>
    </div>
    @endif
    <div class="mrn-notifications-box">
        <h4 class="notifications"><i class="fa fa-clock-o"></i> در انتظار پرداخت</h4>

            @include('global.t-profile.paymentList')
    </div>

@endsection