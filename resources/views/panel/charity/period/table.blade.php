
<table class="table table-columned ">
    <thead>
    <tr>
        <th>ردیف</th>
        <th>{{__('messages.name')}}</th>
        <th>{{__('messages.phone')}}</th>
        <th>{{__('تاریخ عضویت')}}</th>
        <th>{{__('وضعیت تعهد')}}</th>
        <th>{{__('کمک ماهانه/هفتگی')}}</th>
        <th>{{__('messages.amount')}} (ریال) </th>
        <th>{{__('پرداخت شده')}}</th>
        <th>{{__('تاریخ آخرین پرداخت')}}</th>
        <th>{{__('messages.waiting_paid')}}</th>
        <th>مشاهده جزئیات</th>
    </tr>
    </thead>
    <tbody>
    @php $i=1+(isset($_REQUEST['page']) ? ($_REQUEST['page']-1) * $users->perPage() :0); @endphp
    @foreach($users as $key => $user)
        <tr>
            <td>{{$i}}</td>
            <td>{{$user['name']}}</td>
            <td>{{$user['phone']}}</td>
            <td>{{jdate('Y/m/d',strtotime($user['created_at']))}}</td>
            <td>{{$user['routine_status']?"فعال" :""}}</td>
            <td>{{$user['routine_type'] }}</td>
            <td>{{number_format(intval($user['routine_amount'])) }}</td>
            <td>{{$user['paid'] }}</td>
            <td>{{$user['last_paid'] }}</td>
            <td>{{$user['unpaid'] }}  </td>

            <td>
                <a data-toggle="tooltip" data-placement="top" title="{{__('messages.view')}}"
                   href="{{route('charity_periods_show',['user_id'=>$user['id'],'id'=>999999])}}"
                   class="btn btn-outline-dark btn-sm"><i class="icon-eye"></i></a>
            </td>
        </tr>
        @php $i++; @endphp
    @endforeach
    </tbody>
</table>
