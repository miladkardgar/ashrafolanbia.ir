@extends('global.t-profile.frame')
<?php $active_sidebar = ['payment_history'] ?>
@section('mrn-content')

    <div class="mrn-notifications-box">
        <h4 class="notifications"><i class="fa fa-clock-o"></i> سابقه پرداخت های شما</h4>

        <button class="button mrn-button"> پرداخت انتخاب شده ها</button>
<br>
        <table class="table table-responsive">
            @foreach($history as $item)
                <tr>
                    <td class="text-center">
                    @if(!$item['pay_date'])
                        <input type="checkbox">
                    @endif
                    </td>
                    <td class="text-center">مبلغ: {{number_format($item['amount'])}}</td>
                    <td class="text-center">تاریخ : {{miladi_to_shamsi_date($item['payment_date'])}}</td>
                    <td class="text-center">
                        @if($item['pay_date'])
                            تاریخ پرداخت :
                        {{miladi_to_shamsi_date($item['pay_date'])}}
                        @endif
                    </td>
                    <td>
                        @if(!$item['pay_date'])
                            <span class="text-info">پرداخت نشده</span>
                        @endif
                    </td>
                </tr>
            @endforeach

        </table>
        {{$history->links()}}
    </div>

@endsection