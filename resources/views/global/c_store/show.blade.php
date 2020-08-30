@extends('global.c_store.frame')
@section('meta')
    <meta name="keywords" content="{{$product['keywords']}}">
@endsection
@section('mrn-content')
    <div class="mrn-main-page-content">

        <div class="mrn-content-inner mrn-container" role="main">

            @if($product['warning_info'])
                <div class="mrn-notifications-box-warning">
                    <!-- new post form -->
                    {{$product['warning_info']}}
                </div>
            @endif
            <div class="mrn-row has-sidebar sidebar-right" style="transform: none;">

                <div class="product-single-main">

                    <div class="product-single-top-part">

                        <div class="col-md-7">
                            <img src="{{$image['large']}}"
                                 alt="" id="expandedImg" style="
                                width: 528px;
                                margin-right: 0px;
                                float: right;
                                display: block;
                                position: relative;
                                overflow: hidden;
                            ">
                        </div>
                        <div class="col-md-5">
                            <ol class="mrn-flex-control-thumbs">
                                @foreach($product['images'] as $images)
                                <li>
                                    <img src="{{$images['large']}}"
                                         onclick="product_gallery(this);" class="flex-active" draggable="false">
                                </li>
                                @endforeach

                            </ol>
                        </div>

                    </div>

                    <div class="product-single-content">


                        <div class="vc_row wpb_row vc_row-fluid">
                            <div class="wpb_column vc_column_container vc_col-sm-12">
                                <div class="vc_column-inner">
                                    <div class="wpb_wrapper">
                                        {!! $product['description'] !!}

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="product-single-aside sticky-sidebar"
                     style="position: relative; overflow: visible; box-sizing: border-box; min-height: 1px;">


                    <div class="theiaStickySidebar"
                         style="padding-top: 0px; padding-bottom: 1px; position: static; transform: none; top: 0px; left: 1041.1px;">

                        <div class="product-info-box">

                            <div class="sell_course">
                                <strong>قیمت :</strong>
                                <p class="price">

                                    <ins><span class="woocommerce-Price-amount amount">{{number_format($product['price']/10)}}&nbsp;<span
                                                    class="woocommerce-Price-currencySymbol">تومان</span></span>
                                    </ins>
                                </p>
                            </div>


                            <form class="cart" action="{{route('global.c_store_add_to_card')}}" method="post" >
                                <div class="quantity hidden">
                                    @csrf
                                    <input type="hidden" class="qty" name="product"
                                           value="{{$product['id']}}">
                                </div>

                                <button type="submit" name="add-to-cart" value="302"
                                        class="single_add_to_cart_button button alt">
                                    <i class="fa fa-cart-plus"></i>
                                    ثبت سفارش
                                </button>

                            </form>


                        </div>
                        <div class="product-info-box">
                            <div class="product-meta-info-list">

                                @if($product['dimension'])
                                <div class="meta-info-unit">
                                    <div class="icon"><i class="fa fa-expand"></i></div>
                                    <div class="value">ابعاد: {{ $product['dimension'] }}</div>
                                </div>
                                @endif

                                @if($product['weight'])
                                <div class="meta-info-unit">
                                    <div class="icon"><i class="fa fa-dashboard"></i></div>
                                    <div class="value"> وزن :  {{$product['weight']}}</div>
                                </div>
                                @endif

                                    @if($product['delivery_delay'])
                                <div class="meta-info-unit">
                                    <div class="icon"><i class="fa fa-clock-o"></i></div>
                                    <div class="value">
                                        حداقل فاصله زمانی ثبت سفارش:
                                        {{$product['delivery_delay']}}
                                        @switch($product['delivery_delay_type'])
                                            @case('working_day')
                                                روز کاری
                                            @break

                                            @case('actual_day')
                                            روز
                                            @break
                                        @endswitch
                                    </div>
                                </div>
                                    @endif

                                @if($product['allowed_provinces'])
                                <div class="meta-info-unit">
                                    <div class="icon"><i class="fa fa-truck"></i></div>
                                    <div class="value">استان های دارای پوشش تحویل:
                                        @foreach(explode(',',$product['allowed_provinces']) as $province)
                                            <span class="badge badge-info">{{get_provinces($province)['name']}}</span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                @if($product['allowed_cities'])
                                <div class="meta-info-unit">
                                    <div class="icon"><i class="fa fa-truck "></i></div>
                                    <div class="value">شهر های قابل ارائه:
                                        @foreach(explode(',',$product['allowed_cities']) as $city)
                                            <span class="badge badge-info">{{get_cites($city)['name']}}</span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif



                                <div class="meta-info-unit">
                                    <div class="icon"><i class="fa fa-warning"></i></div>
                                    <div class="value">ملاحظات حمل:
                                    {{$product['delivery_description']? $product['delivery_description'] : "ندارد"}}
                                    </div>
                                </div>



                            </div>


                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
