@extends('layouts.global.global_layout')
@section('title',__('messages.my_profile'). " |")

@section('css')
    <link href="{{ URL::asset('/public/assets/global/css/mrn/style.css') }}?i=2" rel="stylesheet" type="text/css">

@endsection
@section('content')
    <?php $user = \Illuminate\Support\Facades\Auth::user()->people;  ?>
    <div class="mrn-main-page-content" >

        <div class="mrn-content-inner mrn-container" role="main">
            <article class="">
                <div class="mrn-entry-content">
                    <div class="mrn-account">


                        <nav class="mrn-account-navigation">
                            <div class="mrn-user-info-account-header">

                                <img alt=""
                                     src="/public/assets/global/images/unknown-avatar.png"
                                     class="avatar avatar-80 photo" height="80" width="80">
                                <div class="mrn-user-info-name">نیکوکار عزیز <strong>{{$user->name ." ".$user->family}} </strong>
                                    خوش آمدید!
                                </div>
                            </div>

                            <ul>
                                <li class="mrn-account-navigation-link {{in_array('dashboard',$active_sidebar)?"is-active":""}}">
                                    <i class="fa fa-dashboard"> </i>
                                    <a href="https://iranaviator.com/my-account/"> پیشخوان </a>
                                </li>
                                <li class="mrn-account-navigation-link {{in_array('payment-history',$active_sidebar)?"is-active":""}}">
                                    <i class="fa fa-history"> </i>
                                    <a href="https://iranaviator.com/my-account/orders/">سوابق پرداخت</a>
                                </li>
                                <li class="mrn-account-navigation-link {{in_array('orders',$active_sidebar)?"is-active":""}}">
                                    <i class="fa fa-shopping-basket"> </i>
                                    <a href="https://iranaviator.com/my-account/purchased-products/">سفارش ها</a>
                                </li>
                                <li class="mrn-account-navigation-link {{in_array('addresses',$active_sidebar)?"is-active":""}}">
                                    <i class="fa fa-map"> </i>
                                    <a href="https://iranaviator.com/my-account/mywishlist/">آدرس ها</a>
                                </li>
                                <li class="mrn-account-navigation-link {{in_array('edit-periodic',$active_sidebar)?"is-active":""}}">
                                    <i class="fa fa-google-wallet"> </i>
                                    <a href="https://iranaviator.com/my-account/downloads/">ویرایش کمک ماهانه</a>
                                </li>
                                <li class="mrn-account-navigation-link {{in_array('edit-account',$active_sidebar)?"is-active":""}}">
                                    <i class="fa fa-user"> </i>
                                    <a href="https://iranaviator.com/my-account/edit-address/">ویرایش حساب کاربری</a>
                                </li>
                                <li class="mrn-account-navigation-link">
                                    <i class="fa fa-sign-out"> </i>
                                    <a href="{{route('logout')}}">خروج</a>
                                </li>
                            </ul>
                        </nav>


                        <div class="mrn-account-content">

                            @yield('mrn-content')


                        </div>


                    </div>
                </div>
            </article><!-- #post -->
        </div>

    </div>
@endsection
