@extends('global.t-profile.frame')
<?php $active_sidebar = ['payment_history'] ?>
@section('mrn-content')
    <div class="mrn-status-user-widget">
        <ul>
            <li class="all_bills w-50">
                <a class="key_wrapper" data-toggle="tab" href="#routine">
                    <span class="mrn-amount">
                        کمک ماهانه
                    </span>
                </a>
            </li>

            <li class="all_bills w-50">
                <a class="key_wrapper" data-toggle="tab" href="#others">
                    <span class="mrn-amount">
                        سایر پرداخت ها
                    </span>
                </a>
            </li>

        </ul>
    </div>

    <div class="mrn-notifications-box tab-content">

        <h4 class="notifications"><i class="fa fa-clock-o"></i> سابقه پرداخت های شما</h4>
        <div id="routine" class="tab-pane fade in active">
        @include('global.t-profile.paymentList')
        </div>
        <div id="others" class="tab-pane fade">
        @include('global.t-profile.paymentListOther')
        </div>

    </div>

@endsection