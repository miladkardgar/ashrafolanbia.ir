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
        <table class="table table-responsive">
            <tr class="">
                <th class="text-center"> </th>
                <th class="text-center">مبلغ</th>
                <th class="text-center">تاریخ</th>
                <th class="text-center">تاریخ پرداخت</th>
                <th class="text-center"> </th>
            </tr>
    @foreach($history as $item)
        <tr>
            <td class="text-center">
                @if(!$item['pay_date'])
                    <input class="payment" name="payment[]" value="{{$item['id']}}" type="checkbox">
                @endif
            </td>
            <td class="text-center"> {{number_format($item['amount'])}}</td>
            <td class="text-center">{{miladi_to_shamsi_date($item['payment_date'])}}</td>
            <td class="text-center">
                @if($item['pay_date'])

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
</form>
{{$history->links()}}
