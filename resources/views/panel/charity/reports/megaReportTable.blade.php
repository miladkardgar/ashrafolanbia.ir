<table class="table datatable-basic">
    <thead>
    <tr>
        <th>{{__('messages.id')}}</th>
        <th>{{__('messages.gateway')}}</th>
        <th>{{__('messages.status')}}</th>
        <th>{{__('messages.date')}}</th>
        <th>{{__('messages.amount')}}</th>
        <th>{{__('بابت')}}</th>
        <th>{{__('سرفصل پرداخت')}}</th>
        <th>  کد رهگیری   </th>
    </tr>
    </thead>
    <tbody>

    @foreach($vow_list as $row)
        @if(isset($row['id']))
            <tr>
                <td>{{$row['id']}}</td>
                <td>{{$row['gateway']}}</td>
                <td class="{{($row['status'] != "موفق" and $row['status'] != "پرداخت شده") ? "text-danger":""}}">{{$row['status']}}</td>
                <td><span dir="ltr">{{$row['payDate']}}</span></td>
                <td>{{number_format($row['amount'])}}</td>
                <td>{{$row['title']['title']}}</td>
                <td>{{$row['patern']['title']}}</td>
                <td>{{$row['tracking_code']}}</td>
            </tr>
        @endif
    @endforeach
    </tbody>
</table>