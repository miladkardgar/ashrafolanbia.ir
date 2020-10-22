@extends('global.t-profile.frame')
<?php $active_sidebar = ['dashboard'] ?>
@section('mrn-content')


    <div class="mrn-status-user-widget">
        <ul>
            <li class="all_bills w-50">
                <div class="key_wrapper">
                    <span class="icon"><i class="fa fa-check-circle"></i></span>
                    <span class="icon-number"><a href="{{route('t_payment_history')}}" class=""><h2 class="">{{$paidPeriodCount}}</h2></a></span>
                    <span class="mrn-amount">
                        <a href="#" class="">
<h4 class="text-info">
    پرداخت شده
                            &nbsp; <span class="fa fa-heart text-theme-colored"> </span>
</h4>
                        </a></span>
                </div>
            </li>
            <li class="all_bills w-50 active">
                <div class="key_wrapper " >
                    <span class="icon"><i class="fa fa-hourglass-half"></i></span>
                    <span class="icon-number"><a href="#waiting" class=""><h2 class="">{{$unpaidPeriodCount}}</h2></a></span>
                    <span class="mrn-amount">
<a href="#waiting" class="">
<h4 class="text-info">در انتظار پرداخت
                            &nbsp; <span class="fa fa-heart heart-beat text-theme-colored"> </span>
</h4>
</a>
				</span>
                </div>
            </li>

        </ul>
    </div>

    @forelse($notifications as $notification)
    <div class="mrn-notifications-box-green">
        <h4 class="notifications"><i class="fa fa-bell-o"></i>  {{$notification['title']}} </h4>

        <ul class="list-unstyled">
            <li class="announce-read">
                <div class="notifications-content">
                    {!! $notification['body'] !!}
                </div>
            </li>
        </ul>
    </div>
    @empty

    @endforelse
@if(isset($period))
    <div class="mrn-notifications-box">
        <h2 class="notifications"> کمک ماهانه</h2>

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
                    <br>
                    <span class="notif-date">
                        مبلغ:
                        <span>{{number_format($period['amount'])}}</span>
                        ریال
                    </span>
                    <h3>نوبت بعدی پرداخت:
                    <span class="text-black-50">{{jdate('l',strtotime($period['next_date']))}}  {{miladi_to_shamsi_date($period['next_date']) }} </span>
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
        <h2 class="notifications">کمک ماهانه یا هفتگی شما فعال نیست</h2>

        <ul class="list-unstyled">
            <li class="announce-read">
                <div class="notifications-content">
                    <span class="notif-date">
                        شما میتوانید با ثبت کمک ماهانه یا هفتگی ما را در برنامه ریزی برای کمک موثرتر به خانواده های نیازمند یاری کنید.
                    </span>

                    <p>شما میتوانید از <a href="{{route('t_routine_vow')}}"><span
                                    style="color: #0000ff;">اینجا</span> </a> کمک ماهانه یا هفتگی خود را فعال کنید.</p>
                    <p style="text-align: center;">
                    </p></div>
            </li>
        </ul>
    </div>
    @endif
    <div class="mrn-notifications-box" id="waiting">
        <h4 class="notifications"><i class="fa fa-hourglass-half"></i>  در انتظار پرداخت  </h4>

            @include('global.t-profile.paymentList')
    </div>

@endsection