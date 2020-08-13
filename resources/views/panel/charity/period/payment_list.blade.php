@if ($unpaid)

    <form method="post" id="remove_transaction_form" action="{{route('charity.remove_routine_transaction')}}" class="">
        @csrf

        <button type="submit" class="btn btn-danger pull-left">حذف انتخاب شده ها</button>
        <label for="selectAllPyments" class="">انتخاب همه</label>
        <input id="selectAllPyments" onclick="selectAllPayment(this)" type="checkbox">
        <input type="hidden" name="user_id" value="{{$userInfo['id']}}">
        @else
            <br>
        @endif

    <!-- Table -->
        <table class="table ">
            <tr class="">
                @if ($unpaid)
                <th class="text-center"></th>
                @endif

                <th class="text-center">موعد</th>
                <th class="text-center">مبلغ (ریال)</th>
                @if (!$unpaid)
                    <th class="text-center">تاریخ پرداخت</th>
                @endif
                <th class="text-center">بابت</th>
                <th class="text-center">شرح</th>
                <th class="text-center">وضعیت</th>
            </tr>
            @forelse($historyItems as $item)

                <tr>
                    @if ($unpaid)
                    <td class="text-center">
                        <input class="payment" name="remove[]" value="{{$item['id']}}" type="checkbox">
                    </td>
                    @endif

                    <td class="text-center">{{miladi_to_shamsi_date($item['payment_date'])}}</td>
                    <td class="text-center"> {{number_format($item['amount'])}}</td>
                    @if (!$unpaid)
                        <td class="text-center">{{miladi_to_shamsi_date($item['pay_date'])}}</td>
                    @endif

                    <td class="text-center"> ایتام و محرومین</td>

                    <td class="text-center">{{$item->description}}</td>
                    <td class="text-center">
                        @if(!$item['pay_date'])
                            <span class="text-danger">در انتظار پرداخت</span>
                        @else
                            <span class="icon-check text-green"></span>
                        @endif

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">یافت نشد.</td>
                </tr>
            @endforelse

        </table>
        <!-- /table -->
        @if ($unpaid)

    </form>

@endif