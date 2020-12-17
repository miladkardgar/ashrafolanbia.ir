<form method="post" action="{{route('t_routine_payment')}}" class="">
    <?php
    $unpaid = $history->where('status','!=','paid')->count();
    if ($unpaid>0):
    ?>
    @csrf
<button type="submit" class="button mrn-button"> پرداخت انتخاب شده ها</button>

<br>
<label for="selectAllPyments" class="">انتخاب همه</label>
<input id="selectAllPyments" onclick="selectAllPayment(this)" type="checkbox">
<?php else: ?>
        <br>
        <?php endif ?>
        <table class="table table-responsive" style="display: block">
            <tr class="">
                <th class="text-center"> </th>
                <th class="text-center">موعد</th>
                <th class="text-center">مبلغ (ریال)</th>
                @if(isset($history[0]) and $history[0]['pay_date'])
                <th class="text-center">تاریخ پرداخت</th>
                @endif
                <th class="text-center">دوره</th>
                <th class="text-center">بابت</th>
                <th class="text-center">کد رهگیری</th>
            </tr>
    @forelse($history as $item)

        <tr>
            <td class="text-center">
                @if(!$item['pay_date'])
                    <input class="payment" name="payment[]" value="{{$item['id']}}" type="checkbox">
                @endif
            </td>
            <td class="text-center">{{miladi_to_shamsi_date($item['payment_date'])}}</td>
            <td class="text-center"> {{number_format($item['amount'])}}</td>
            @if($item['pay_date'])

            <td class="text-center">
                    {{miladi_to_shamsi_date($item['pay_date'])}}
            </td>
            @endif

            <td class="text-center">{{$item->description}}</td>

            <td class="text-center">{{$item['title']['title']}}</td>

            <td class="text-center">
                @if(!$item['pay_date'])
                    <span class="text-danger">در انتظار پرداخت</span>
                @else
                    <a target="_blank" href="{{route('t_payment_history_receipt',$item->transaction['tracking_code'])}}" class="text-info">
                        <i class="fa fa-mouse-pointer"></i>
                        {{$item->transaction['tracking_code']}}

                    </a>
                @endif

            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">یافت نشد.</td>
        </tr>
    @endforelse

</table>
</form>
{{$history->links()}}
