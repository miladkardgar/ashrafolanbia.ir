@extends('global.t-profile.frame')
<?php $active_sidebar = ['payment_history'] ?>
@section('mrn-content')

    <div class="mrn-notifications-box">
        <h4 class="notifications"><i class="fa fa-clock-o"></i> سابقه پرداخت های شما</h4>
        @include('global.t-profile.paymentList')
    </div>

@endsection