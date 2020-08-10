@extends('layouts.global.global_layout')
@section('title',__('messages.my_profile'). " |")

@section('css')
    <link href="{{ URL::asset('/public/assets/global/css/mrn/style.css') }}?i=2" rel="stylesheet" type="text/css">
    @yield('css2')
@endsection
@section('content')
    <?php $user = \Illuminate\Support\Facades\Auth::user();
    $avatar = $user->profile_image->last()
    ?>
    <div class="mrn-main-page-content" >

        <div class="mrn-content-inner mrn-container" role="main">
            <article class="">
                <div class="mrn-entry-content">
                    <div class="mrn-account">


                        <nav class="mrn-account-navigation">
                            <div class="mrn-user-info-account-header">

                                <img alt=""
                                     src="{{$avatar ? "/".$avatar->url:"/public/assets/global/images/unknown-avatar.png"}}"
                                     class="avatar avatar-80 photo" height="120" width="120">
                                <div class="mrn-user-info-name">نیکوکار عزیز
                                    <br>
                                    <strong>{{get_name($user->id)}} </strong>
                                    <br>
                                    خوش آمدید.

                                </div>
                            </div>

                            <ul>
                                <li class="mrn-account-navigation-link {{in_array('dashboard',$active_sidebar)?"is-active":""}}">
                                    <i class="fa fa-heart"> </i>
                                    <a href="{{route('global_profile')}}"> پیشخوان نیکوکار </a>
                                </li>
                                <li class="mrn-account-navigation-link {{in_array('payment_history',$active_sidebar)?"is-active":""}}">
                                    <i class="fa fa-bar-chart"> </i>
                                    <a href="{{route('t_payment_history')}}">سوابق پرداخت</a>
                                </li>

{{--                                <li class="mrn-account-navigation-link {{in_array('addresses',$active_sidebar)?"is-active":""}}">--}}
{{--                                    <i class="fa fa-map"> </i>--}}
{{--                                    <a href="{{route('t_addresses')}}">آدرس ها</a>--}}
{{--                                </li>--}}
                                <li class="mrn-account-navigation-link {{in_array('vow',$active_sidebar)?"is-active":""}}">
                                    <i class="fa fa-calendar"> </i>
                                    <a href="{{route('t_routine_vow')}}">موعد کمک ماهانه یا هفتگی</a>
                                </li>

                                <li class="mrn-account-navigation-link ">
                                    <i class="fa fa-star"> </i>
                                    <a href="{{route('vow_donate')}}">کمک موردی</a>
                                </li>

                                <li class="mrn-account-navigation-link {{in_array('edit_profile',$active_sidebar)?"is-active":""}}">
                                    <i class="fa fa-user"> </i>
                                    <a href="{{route('global_profile_completion')}}">مشخصات نیکوکار</a>
                                </li>

                                <li class="mrn-account-navigation-link">
                                    <i class="fa fa-sign-out"> </i>
                                    <a href="{{route('logout')}}">خروج</a>
                                </li>
                            </ul>
                            <img class="img img-responsive sm-text-center xs-text-center side-logo-center" src="https://ashrafolanbia.ir/public/assets/global/images/logo-wide@2x.png?i=4" alt="" >
                        </nav>


                        <div class="mrn-account-content">
                            <div class="mrn-notices-wrapper"></div>

                            @yield('mrn-content')


                        </div>


                    </div>
                </div>
            </article><!-- #post -->
        </div>

    </div>
@endsection
@section('js')
    <script >
        $(document).ready(function () {
            $(document).on("keyup", '.amount', function (event) {
                $(this).val(function (index, value) {
                    return value
                        .replace(/\D/g, "")
                        .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                });
            });
        });
        function selectAllPayment(source) {
            let checkboxes = document.getElementsByClassName('payment');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = source.checked;
            }
        }
    </script>

    @yield('js2')
@endsection
