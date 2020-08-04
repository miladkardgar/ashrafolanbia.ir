
        <table class="table table-responsive">
            <tr class="">
                <th class="text-center"> </th>
                <th class="text-center">مبلغ</th>
                <th class="text-center">تاریخ پرداخت</th>
                <th class="text-center">بابت</th>
            </tr>
    @foreach($otherHistory as $item_o)
        <tr>
            <td class="text-center">

            </td>
            <td class="text-center"> {{number_format($item_o['amount'])}}</td>
            <td class="text-center">{{miladi_to_shamsi_date($item_o['payment_date'])}}</td>
            <td >{{$item_o['patern']['title']}}

                {{$item_o['title']['title'] ? " - ".$item_o['title']['title'] :""}}
            </td>
        </tr>
    @endforeach

</table>
