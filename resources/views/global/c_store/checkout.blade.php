@extends('global.c_store.frame')

@section('mrn-content')
    <div class="mrn-main-page-content">
        <div class="mrn-content-inner mrn-container" role="main">
            @if(!$card)
                <div class="empty-cart-wraper">
                    <p class="return-to-shop">
                        <i class="fa fa-dropbox" style="font-size: 9rem"></i>
                    </p>
                    <div class="woocommerce-notices-wrapper"></div>
                    <p class="cart-empty woocommerce-info">سبد خرید شما در حال حاضر خالی است.</p>
                    <p class="return-to-shop">
                        <a class="btn btn-theme-colored" href="{{route('global.c_store')}}">
                            بازگشت به فروشگاه </a>
                    </p>
                </div>
            @else
                <div class="mrn-row has-sidebar sidebar-right" style="transform: none;">

                    <div class="product-single-main">
                        <div class="product-single-top-part">


                            <form class="mrn-cart-form" action="{{route('global.c_store_update_card')}}" method="post">
                                @csrf
                                <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents"
                                       cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th class="product-remove">&nbsp;</th>
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

                                            <td class="product-remove">
                                                <a href="{{route('global.c_store_remove_from_card',[$key])}}"
                                                   class="remove" aria-label="حذف این آیتم" data-product_id="506"
                                                   data-product_sku="">×</a></td>

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
                                                <div class="quantity">
                                                    <input type="number"
                                                           class="input-text qty text" step="1" min="0" max=""
                                                           name="quantity[{{$key}}]"
                                                           value="{{$value['quantity']}}" title="تعداد" size="4"
                                                           placeholder="" inputmode="numeric">
                                                </div>
                                            </td>

                                            <td class="product-subtotal" data-title="جمع">
                                                <span class="woocommerce-Price-amount amount">{{number_format($value['price']*$value['quantity']/10)}}&nbsp;<span
                                                            class="woocommerce-Price-currencySymbol">تومان</span></span>
                                            </td>
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <td colspan="6" class="mrn-card-item-actions">

                                            <button type="submit"
                                                    class="button btn btn-theme-colored button_update_cart"
                                                    name="update_cart" value="بروز رسانی سبدخرید" >بروز رسانی
                                                سبدخرید
                                            </button>

                                        </td>
                                    </tr>

                                    </tbody>
                                </table>
                            </form>
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


                                <form class="cart" method="get" action="{{route('global.c_store_card_completion_phone')}}">

                                    <button type="submit" name="add-to-cart" value="302"
                                            class="single_add_to_cart_button button alt">
                                        <i class="fa fa-cart-plus"></i>
                                        نهایی سازی سفارش
                                    </button>

                                </form>


                            </div>

                            <div class="product-info-box">
                                <a type="button" href="{{route('global.c_store')}}"
                                   class="single_add_to_cart_button btn-block btn btn-warning">
                                    <i class="fa fa-shopping-basket"></i>
                                    سفارش موارد بیشتر
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            @endif
        </div>
    </div>

@endsection
