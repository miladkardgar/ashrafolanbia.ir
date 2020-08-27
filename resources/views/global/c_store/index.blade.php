@extends('global.c_store.frame')
@section('mrn-content')
    <div class="mrn-main-page-content">
        <div class="mrn-content-inner mrn-container" role="main">


            <div class="mrn-course-main-wrapper mrn-has-sidebar mrn-shop-sidebar-left" style="transform: none;">
                <div class="mrn-course-wrapper-inner" style="transform: none;">

                    <div class="mrn-courses-holder">
                        <div class="mrn-notices-wrapper"></div>
                        <div class="mrn-products grid-view courses-2-columns">
                            @foreach($products as $product)
                            <div class="course-item product ">

                                <div class="course-item-inner">

                                    <div class="course-thumbnail-holder">
                                        <a href="{{route('global.c_store_show',['slug'=>$product['slug']])}}"
                                           class="woocommerce-LoopProduct-link woocommerce-loop-product__link">                    <span
                                                    class="image-item">
						                        <img width="400" height="229"
                                                     src="{{$product['image']}}"
                                                     class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail"
                                                     alt="">                    </span>


                                        </a>

                                        <form class="cart" action="{{route('global.c_store_add_to_card')}}" method="post" >
                                            <div class="quantity hidden">
                                                @csrf
                                                <input type="hidden" class="qty" name="product"
                                                       value="{{$product['id']}}">
                                            </div>



                                        <div class="sale-perc-badge">
                                            <button href="/" type="submit" class="text-white" style="border: 0;
    background-color: #ff000000;
    padding: 0;">
                                            <div class="sale-perc">سفارش</div>
                                            <div class="sale-badge-text">فوری</div>
                                            </button>
                                        </div>


                                        </form>
                                    </div>

                                    <div class="course-content-holder">


                                        <div class="course-content-main">
                                            <h3 class="course-title">
                                                <a href="{{route('global.c_store_show',['slug'=>$product['slug']])}}">
                                                {{$product['title']}}
                                                </a>
                                            </h3>


                                            <div class="course-description">
                                                <p>{{$product['description']}}</p>
                                            </div>
                                        </div>

                                        <div class="course-content-bottom">

                                            <div class="course-students">
                                                <a href="{{route('global.c_store_show',['slug'=>$product['slug']])}}" class="text-white">
                                                <i class="fa fa-expand"></i><span>مشاهده</span>
                                                </a>
                                            </div>

                                            <div class="course-price">

                                                <span class="price">
                                                    <del><span class=" amount">تومان<span
                                                                    class="mrn-Price-currencySymbol"></span></span></del>

<ins><span
            class=" amount">{{number_format($product['price']/10)}}&nbsp;<span
                class=""></span></span></ins>
                                                </span>
                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>
                            @endforeach
                        </div>

                    </div>

                    <div class="mrn-main-sidebar-holder mrn-sticky-sidebar"
                         style="position: relative; overflow: visible; box-sizing: border-box; min-height: 1px;">
                        <div class="theiaStickySidebar"
                             style="padding-top: 0px; padding-bottom: 1px; position: static; transform: none; top: 0px; left: 1041.1px;">
                            <div class="sidebar-widgets-wrapper">
                                <div id="text-11" class="widget widget_text"><h5 class="widget-title">چرا تاج گل امانی؟</h5>
                                    <div class="textwidget"><p>
                                            <img
                                                        class="aligncenter wp-image-1106 size-full"
                                                        src="https://www.rajeoon.com/wp-content/uploads/2012/10/flower_product_image_50-flower_product_image_50-500.gif"
                                                        alt="" width="259" height="258">
                                            * مشارکت در حمایت و توانمندی ایتام و محرومین * مشارکت در ساخت مسکن روستایی * پرهیز از اسراف در هزینه گل طبیعی * خدمت و خیراتی ماندگار برای عزیز متوفی

                                        </p>
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
