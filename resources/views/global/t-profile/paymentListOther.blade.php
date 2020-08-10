
        <table class="table table-responsive">
            <tr class="">
                <th class="text-center"> </th>
                <th class="text-center">مبلغ (ریال)</th>
                <th class="text-center">تاریخ پرداخت</th>
                <th class="text-center">بابت</th>
                <th class="text-center">شرح</th>
                <th class="text-center">وضعیت</th>
            </tr>
    @forelse($otherHistory as $item_o)
        <tr>
            <td class="text-center">

            </td>
            <td class="text-center"> {{number_format($item_o['amount'])}}</td>
            <td class="text-center">{{miladi_to_shamsi_date($item_o['payment_date'])}}</td>
            <td class="text-center">{{$item_o['patern']['title']}}

                {{$item_o['title']['title'] ? " - ".$item_o['title']['title'] :""}}
            </td>
            <td class="text-center"> کمک موردی {{$item_o['id']}} </td>
            <td class="text-center" ><span class="fa fa-check"></span> </td>
        </tr>
                @empty

                <tr >
                    <td colspan="6" class="text-center">یافت نشد.</td>
                </tr>
    @endforelse

</table>
