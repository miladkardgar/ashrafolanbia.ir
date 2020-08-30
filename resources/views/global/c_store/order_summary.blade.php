@extends('global.c_store.frame')
@section('css2')
    <link rel="stylesheet" href="{{URL::asset('/public/assets/panel/css/iranBanks/ibl.css')}}">
@stop
@section('mrn-content')
    <div class="mrn-main-page-content">
        <div class="mrn-content-inner mrn-container" role="main">

                <div class="mrn-row has-sidebar sidebar-right" style="transform: none;">

                    <div class="product-single-main p-2">
                        <div class="product-single-top-part p-15">
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
                        <div class="product-single-top-part p-15">
                                <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents"
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
                                    @foreach($card as $key => $value)
                                        <?php $total += $value['price'] * $value['quantity']; ?>

                                        <tr class="woocommerce-cart-form__cart-item cart_item">


                                            <td class="product-thumbnail">
                                                <a href="{{route('global.c_store_show',[$value['slug']])}}">
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

                                    <tr>
                                        <td colspan="6" class="mrn-card-item-actions">

                                            <a href="{{route('global.c_store_checkout')}}"
                                                    class="button btn btn-theme-colored button_update_cart"
                                                    name="update_cart" value="بروز رسانی سبدخرید" >ویرایش موارد انتخاب شده</a>

                                        </td>
                                    </tr>

                                    </tbody>
                                </table>
                        </div>
                    </div>

                    <div class="product-single-aside sticky-sidebar"
                         style="position: relative; overflow: visible; box-sizing: border-box; min-height: 1px;">


                        <div class="theiaStickySidebar"
                             style="padding-top: 0px; padding-bottom: 1px; position: static; transform: none; top: 0px; left: 1041.1px;">


                            <div class="product-info-box">

                                <div class="sell_course">
                                    <strong>جمع کل :</strong>
                                    <p class="price">

                                        <ins><span class="woocommerce-Price-amount amount">{{number_format($total/10)}}&nbsp;<span
                                                        class="woocommerce-Price-currencySymbol">تومان</span></span>
                                        </ins>
                                    </p>
                                </div>


                                @foreach($gateways as $gateway)
                                    <div class="col-md-6 col-xs-4" >
                                        <div class="radio text-center">
                                            <label>
                                                <strong>{!! $gateway['logo'] !!}</strong><br>
                                                <input type="radio" name="gateway"
                                                       id="gateway_{{$gateway['id']}}"
                                                       value="{{$gateway['id']}}"
                                                       checked="checked">
                                                <strong><small>{{$gateway['bank']['name']}}</small></strong>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach

                                <form class="cart" method="get" action="{{route('global.c_store_card_completion_phone')}}">

                                    <button type="submit" name="add-to-cart" value="302"
                                            class="single_add_to_cart_button button alt">
                                        <i class="fa fa-credit-card"></i>
                                    پرداخت
                                    </button>

                                </form>


                            </div>

                        </div>
                    </div>

                </div>
        </div>
    </div>

@endsection
