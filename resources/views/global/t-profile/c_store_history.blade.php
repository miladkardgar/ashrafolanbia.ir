@extends('global.t-profile.frame')
<?php $active_sidebar = ['c_store'] ?>
@section('mrn-content')

    @if($order)
    <div class="mrn-notifications-box tab-content table-responsive">
        <h4>
            اطلاعات سفارش شماره
            {{$order['id']}}
        </h4>
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
            @foreach($order->items as $item)
                <?php $total += $item['price'] * $item['quantity']; ?>

                <tr class="woocommerce-cart-form__cart-item cart_item">


                    <td class="product-thumbnail">
                        <a href="{{route('global.c_store_show',[$item['slug']])}}" target="_blank">
                            <img width="200" height="120" src="{{$item['image']}}"
                                 class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail"
                                 alt=""></a></td>

                    <td class="product-name" data-title="محصول">
                        <a href="{{route('global.c_store_show',[$item['slug']])}}">{{$item['name']}}</a>
                    </td>

                    <td class="product-price" data-title="قیمت">
                                                <span class="woocommerce-Price-amount amount">{{number_format($item['price']/10)}}&nbsp;<span
                                                            class="woocommerce-Price-currencySymbol">تومان</span></span>
                    </td>

                    <td class="product-quantity" data-title="تعداد">
                        <span class="woocommerce-Price-amount amount">{{$item['quantity']}}&nbsp;</span>


                    </td>

                    <td class="product-subtotal" data-title="جمع">
                                                <span class="woocommerce-Price-amount amount">{{number_format($item['price']*$item['quantity']/10)}}&nbsp;<span
                                                            class="woocommerce-Price-currencySymbol">تومان</span></span>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>

    </div>
    @endif

    <div class="mrn-notifications-box tab-content">

        <h4 class="notifications"><i class="fa fa-bar-chart"></i> سوابق سفارش های شما</h4>
        <div class="table-responsive">
            <table class="table ">
                <tr class="">
                    <th class="text-center">شماره سفارش</th>
                    <th class="text-center">تاریخ سفارش</th>
                    <th class="text-center">مبلغ (ریال)</th>
                    <th class="text-center">وضعیت</th>
                    <th class="text-center">مشاهده</th>
                </tr>
                @forelse($orders as $value)

                    <tr>
                        <td class="text-center">
                            {{$value['id']}}
                        </td>
                        <td class="text-center">
                            {{miladi_to_shamsi_date($value['created_at'])}}
                        </td>
                        <td class="text-center">
                            {{number_format($value['amount'])}}
                        </td>

                        <td class="text-center">
                            @switch($value['process_status'])
                                @case('new')
                                در انتظار پردازش
                                @break
                                @case('processing')
                                در دست اقدام
                                @break
                                @case('canceled')
                                لغو شده
                                @break
                                @case('done')
                                انجام شده
                                @break
                                @default
                                نامشخص
                            @endswitch
                        </td>

                        <td class="text-center">
                            <a href="{{route('t_c_store',$value['id'])}}" class="btn {{($order and $order['id'] == $value['id']) ? 'btn-warning':'btn-theme-colored'}} btn-sm">
                                <i class="fa fa-eye"></i>
                            </a>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">یافت نشد.</td>
                    </tr>
                @endforelse

            </table>
            {{$orders->links()}}
        </div>


    </div>

@endsection