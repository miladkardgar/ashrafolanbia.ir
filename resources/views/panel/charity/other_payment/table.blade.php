<table class="table table-columned">
    <thead>
    <tr>
        <th>{{__('messages.id')}}</th>
        <th>{{__('messages.name_family')}}</th>
        <th>{{__('messages.phone')}}</th>
        <th>{{__('messages.amount')}}</th>
        <th>{{{__('messages.payment_date')}}}</th>
        <th>{{__('messages.patern')}}</th>
        <th>{{__('messages.title')}}</th>
        <th>{{__('messages.status')}}</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php $i = 1; ?>
    @foreach($otherPayments as $payment)
        <tr>
            <td>{{$payment['id']}}</td>
            <td>{{$payment['user']['people']['name']}} {{$payment['user']['people']['family']}}</td>
            <td>{{$payment['phone']}}</td>
            <td>{{number_format($payment['amount'])}} {{__('messages.rial')}}</td>
            <td>
                @if($payment['payment_date'])
                    {{jdate("Y-m-d",strtotime($payment['payment_date']))}}
                @endif
            </td>
            <td>{{$payment['patern']['title']}}</td>
            <td>
                {{$payment['title']['title']}}
            </td>
            <td>
                @if(isset($payment['tranInfo'][0]) && $payment['tranInfo'][0]['status']=='SUCCEED')
                    <span
                            class="badge badge-success">{{__('messages.'.$payment['tranInfo'][0]['status'])}}</span>
                @elseif(isset($payment['tranInfo'][0]) && $payment['tranInfo'][0]['status']=='FAILED')
                    <span
                            class="badge badge-danger">{{__('messages.'.$payment['tranInfo'][0]['status'])}}</span>
                @else
                    <span class="badge badge-danger">{{__('messages.unknown')}}</span>
                @endif
            </td>

            <td>
                <a href="{{route('charity_payment_list_vow_show',['id'=>$payment['id']])}}"
                   data-toggle="tooltip" data-placement="top" title="{{__('messages.view')}}"
                   class="btn btn-outline-dark btn-sm"><i class="icon-eye"></i></a>
            </td>
        </tr>
        <?php $i++; ?>
    @endforeach
    </tbody>
</table>
