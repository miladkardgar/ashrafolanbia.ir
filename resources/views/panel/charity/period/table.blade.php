<table class="table table-columned ">
    <thead>
    <tr>
        <th></th>
        <th>{{__('messages.name')}}</th>
        <th>{{__('messages.phone')}}</th>
        <th>{{__('messages.status')}}</th>
        <th>{{__('messages.type')}}</th>
        <th>{{__('messages.amount')}}</th>
        <th>{{__('messages.waiting_paid')}}</th>
        <th>{{__('تاریخ آخرین پرداختی')}}</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $key => $user)
        <tr>
            <td>{{$key+1}}</td>
            <td>{{$user['name']}}</td>
            <td>{{$user['phone']}}</td>
            <td>{{$user['routine_status']?"فعال" :""}}</td>
            <td>{{$user['routine_type'] }}</td>
            <td>{{number_format(intval($user['routine_amount'])) }}</td>
            <td>{{$user['unpaid'] }}</td>
            <td>{{$user['last_paid'] }}</td>
            <td>
                <a data-toggle="tooltip" data-placement="top" title="{{__('messages.view')}}"
                   href="{{route('charity_periods_show',['user_id'=>$user['id'],'id'=>999999])}}"
                   class="btn btn-outline-dark btn-sm"><i class="icon-eye"></i></a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
