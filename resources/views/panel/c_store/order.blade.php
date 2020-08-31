<?php
$active_sidbare = ['c_store', 'c_store_orders']
?>

@extends('layouts.panel.panel_layout')
@section('css')
    <link rel="stylesheet" href="{{URL::asset('/public/assets/global/js/fancybox/dist/jquery.fancybox.min.css')}}"
          type="text/css" media="screen"/>
@endsection
@section('content')

    <div class="content">

        <div class="card">
            <div class="card-body">
                <form action="{{route('c_store.change_order_status',[$order['id']])}}" method="post" class="">
                    @csrf
                    <div class="row">
                        <div class="col-md-2 offset-3">
                            <label for="Status" class="label-default float-right font-weight-bolder">وضعیت:</label>
                        </div>
                        <div class="col-md-4 ">
                            <select name="status" id="Status" class="form-control">
                                <option value="new" {{$order['process_status'] == 'new'?"selected":""}} class="">سفارش جدید</option>
                                <option value="processing" {{$order['process_status'] == 'processing'?"selected":""}} class="">در حال انجام</option>
                                <option value="done" {{$order['process_status'] == 'done'?"selected":""}} class="">انجام شده</option>
                                <option value="canceled" {{$order['process_status'] == 'canceled'?"selected":""}} class="">لغو شده</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-block btn-success ">ثبت وضعیت</button>

                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        
        <div class="card">
            <div class="card-body">
                <div class="row p-15">
                    <div class="col-md-4 mt-5">
                        <span style="font-weight: bolder">استان:</span>
                        {{get_provinces($order['province'])['name']}}
                    </div>
                    <div class="col-md-4 mt-5">
                        <span style="font-weight: bolder">شهر:</span>
                        {{get_provinces($order['cities'])['name']}}
                    </div>
                    <div class="col-md-4 mt-5">
                        <span style="font-weight: bolder">{{__('messages.receiver_name')}} :</span>
                        {{$order['receiver']}}
                    </div>
                    <div class="col-md-4 mt-5">
                        <span style="font-weight: bolder">{{__('messages.zip_code')}} :</span>
                        {{$order['zip_code']}}
                    </div>
                    <div class="col-md-4 mt-5">
                        <span style="font-weight: bolder">{{__('messages.phone')}} :</span>
                        {{$order['phone']}}
                    </div>
                    <div class="col-md-4 mt-5">
                        <span style="font-weight: bolder">{{__('messages.mobile')}} :</span>
                        {{$order['mobile']}}
                    </div>
                    <div class="col-md-4 mt-5">
                        <span style="font-weight: bolder">{{__('messages.condolences_to')}} :</span>
                        {{$order['condolences_to']}}
                    </div>
                    <div class="col-md-4 mt-5">
                        <span style="font-weight: bolder">{{__('messages.on_behalf_of')}} :</span>
                        {{$order['from_as']}}
                    </div>
                    <div class="col-md-4 mt-5">
                        <span style="font-weight: bolder">{{__('messages.late_name')}} :</span>
                        {{$order['late_name']}}
                    </div>
                    <div class="col-md-4 mt-5">
                        <span style="font-weight: bolder">{{__('messages.meeting_date')}} :</span>
                        {{miladi_to_shamsi_date($order['date'])}}
                    </div>
                    <div class="col-md-4 mt-5">
                        <span  style="font-weight: bolder">{{__('messages.meeting_time')}} :</span>
                        {{$order['time']}}
                    </div>
                    <div class="col-md-12 mt-10">
                        <span style="font-weight: bolder">{{__('messages.meeting_address')}} :</span>
                        {{$order['meeting_address']}}
                    </div>
                    <div class="col-md-12 mt-10">
                        <span style="font-weight: bolder">{{__('messages.descriptions')}} :</span>
                        {{$order['description']}}
                    </div>

                </div>

            </div>
        </div>
        <div class="card table-responsive">
            <table class="table table-striped"
                   cellspacing="0">
                <thead>
                <tr>
                    <th class="product-thumbnail">&nbsp;</th>
                    <th class="product-name">مورد</th>
                    <th class="product-price">قیمت</th>
                    <th class="product-quantity">تعداد</th>
                    <th class="product-subtotal">جمع</th>
                </tr>
                </thead>
                <tbody>
                <?php $total = 0; ?>
                @foreach($order->items as $value)
                    <?php $total += $value['price'] * $value['quantity']; ?>

                    <tr class="woocommerce-cart-form__cart-item cart_item">


                        <td class="product-thumbnail">
                            <a href="{{route('global.c_store_show',[$value['slug']])}}" target="_blank">
                                <img width="400" height="229" src="{{$value['image']}}"
                                     class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail"
                                     alt=""></a></td>

                        <td class="product-name" data-title="محصول">
                            <a href="{{route('global.c_store_show',[$value['slug']])}}">{{$value['name']}}</a>
                        </td>

                        <td class="product-price" data-title="قیمت">
                                                <span class="woocommerce-Price-amount amount">{{number_format($value['price']/10)}}&nbsp;<span
                                                            class="woocommerce-Price-currencySymbol">تومان</span></span>
                        </td>

                        <td class="product-quantity" data-title="تعداد">
                            <span class="woocommerce-Price-amount amount">{{$value['quantity']}}&nbsp;</span>


                        </td>

                        <td class="product-subtotal" data-title="جمع">
                                                <span class="woocommerce-Price-amount amount">{{number_format($value['price']*$value['quantity']/10)}}&nbsp;<span
                                                            class="woocommerce-Price-currencySymbol">تومان</span></span>
                        </td>
                    </tr>
                @endforeach



                </tbody>
            </table>

        </div>

    </div>

@endsection