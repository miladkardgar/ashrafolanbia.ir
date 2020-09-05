<?php
$active_sidbare = ['c_store','c_store_orders']
?>

@extends('layouts.panel.panel_layout')
@section('js')

@endsection
@section('css')

@endsection
@section('content')

    <div class="content">
        <div class="card">
            <div class="card-header header-elements-inline bg-light"><span class="card-title">{{__('لیست سفارشات')}}</span>

            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>{{__('messages.phone')}}</th>
                        <th>{{__('messages.price')}}</th>
                        <th>{{__('تاریخ ثبت')}}</th>
                        <th>{{__('تاریخ و ساعت مراسم')}}</th>
                        <th>{{__('وضعیت پردازش')}}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{\App\User::find($order['user_id'])['phone']}}</td>
                                <td>{{number_format($order['amount'])}}</td>
                                <td>{{miladi_to_shamsi_date($order['pay_date'])}}</td>
                                <td>{{miladi_to_shamsi_date($order['date']) ." ". $order['time']}}</td>
                                <td><div class="badge badge-warning">{{$order['process_status']}}</div></td>
                                <td>
                                    <a href="{{route('c_store.order',['id'=>$order['id']])}}" class="btn btn-sm btn-outline-success ">
                                        <span class="icon-eye"></span>
                                    </a>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection