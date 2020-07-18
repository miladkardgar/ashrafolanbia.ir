@extends('global.t-profile.frame')
<?php $active_sidebar = ['dashboard'] ?>
@section('mrn-content')

    <div class="mrn-notices-wrapper"></div>

    <div class="mrn-status-user-widget">
        <ul>
            <li class="all_bills">
                <div class="key_wrapper">
                    <span class="icon"><i class="fa fa-certificate"></i></span>
                    <span class="mrn-amount">
				        {{$paidPeriodCount}}
								<span class="">دوره</span></span><span class="title">پرداخت شده</span>
                </div>
            </li>
            <li class="all_bills">
                <div class="key_wrapper">
                    <span class="icon"><i class="fa fa-clock-o"></i></span>
                    <span class="mrn-amount">
                        {{$unpaidPeriodCount}}

				<span class="wc-Symbol">دوره</span></span><span class="title">در انتظار پرداخت</span>
                </div>
            </li>

            <li class="all_bills w-50">
                <div class="key_wrapper">
                    <span class="icon"><i class="fa fa-credit-card"></i></span>
                    <span class="mrn-amount">
				<a class="woo-wallet-menu-contents" href="https://iranaviator.com/my-account/woo-wallet/"
                   title="اعتبار فعلی کیف پول"><span class="woocommerce-Price-amount amount">{{number_format($paidPeriodAmount)}}&nbsp;<span
                                class="">تومان</span></span></a>

				</span><span class="title">حمایت شما تاکنون</span>
                </div>
            </li>
        </ul>
    </div>

    <div class="mrn-notifications-box-green">
        <h4 class="notifications"><i class="fa fa-bell-o"></i>  اطلاعیه </h4>

        <ul class="list-unstyled">
            <li class="announce-read">
                <div class="notifications-content">
                    <p>
                        فراخوان کمک برای ساخت 8 مدرسه در مناطق زلزله زده
                    </p>

                </div>
            </li>
        </ul>
    </div>

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
                    <span class="text-black-50">{{miladi_to_shamsi_date($period['next_date'])}}</span>
                    </h3>
                    <p>شما میتوانید مبلغ و دوره زمانی تعهد خود را از <a href="#"><span
                                    style="color: #0000ff;">اینجا</span> </a> ویرایش کنید.</p>
                    <p style="text-align: center;">
                    </p></div>
            </li>
        </ul>
    </div>

    <div class="mrn-notifications-box">
        <h4 class="notifications"><i class="fa fa-clock-o"></i> در انتظار پرداخت</h4>

        <table class="table table-responsive">
            @foreach($unpaidPeriod as $unpaid)
                <tr>
                    <td class="text-center"><input type="checkbox"> </td>
                    <td class="text-center">مبلغ: {{number_format($unpaid['amount'])}}</td>
                    <td class="text-center">تاریخ : {{miladi_to_shamsi_date($unpaid['payment_date'])}}</td>
                </tr>
            @endforeach

        </table>
        <button class="button mrn-button"> پرداخت انتخاب شده ها</button>
    </div>

@endsection