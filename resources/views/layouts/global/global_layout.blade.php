<!DOCTYPE html>
<html lang="{{ trim(str_replace('_', '-', app()->getLocale())) }}"
      dir="{{trim(str_replace('_', '-', app()->getLocale())) == 'fa'? 'rtl':'ltr'}}">
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <meta name="description" content="{{trans('site_info.site_name')}}"/>
    <meta name="keywords" content="{{trans('site_info.keyword')}}"/>
    <meta name="author" content="Mehran Marandi - m.marandi@gmail.com"/>
    <meta name="author" content="Milad Kardgar - mk.kardgar@gmail.com"/>
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://ashrafolanbia.ir"/>
    <meta name="copyright" content="{{trans('site_info.copy_right')}}">
    <meta name="language" content="fa">
    <meta name="google" content="notranslate">
    <meta name="robots" content="index follow">
    <meta name="googlebot" content="index follow">
    <!--        <meta name="samandehi" content="712692104"/>-->
    <meta property="og:title" content="{{trans('site_info.site_name')}}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="{{config('site_setting.site_url')}}"/>
    <meta property="og:image" content="{{asset(url('/public/assets/global/images/logoImage.png'))}}"/>
    <meta property="og:site_name" content="{{__('site_info.web_title')}}"/>
    <meta property="og:description" content="{{trans('site_info.site_description')}}"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="twitter:card" content="summary"/>
    <meta name="twitter:description" content="{{trans('site_info.site_description')}}"/>
    <meta name="twitter:title"
          content="{{__('site_info.web_title')}}"/>

    <script type="application/lds+json">{
                   "@context": "http://schema.org",
                    "@type": "Blog",
                    "url": "{{config('site_setting.site_url')}}/blog"
                    }

    </script>
    <meta name="viewport" content="width=device-width">


    <meta name="description" content="@yield('meta_description')">
@yield('meta')

<!-- Page Title -->
    <title>@yield('title') {{trans('site_info.web_title')}}</title>

    <!-- Favicon and Touch Icons -->
    <link href="{{ URL::asset('/public/assets/global/images/favicon.png') }}" rel="shortcut icon" type="image/png">
    <link href="{{ URL::asset('/public/assets/global/images/apple-touch-icon.png') }}" rel="apple-touch-icon">
    <link href="{{ URL::asset('/public/assets/global/images/apple-touch-icon-72x72.png') }}" rel="apple-touch-icon"
          sizes="72x72">
    <link href="{{ URL::asset('/public/assets/global/images/apple-touch-icon-114x114.png') }}" rel="apple-touch-icon"
          sizes="114x114">
    <link href="{{ URL::asset('/public/assets/global/images/apple-touch-icon-144x144.png') }}" rel="apple-touch-icon"
          sizes="144x144">

    <!-- Stylesheet -->
    <link href="{{ URL::asset('/public/assets/global/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('/public/assets/global/css/jquery-ui.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('/public/assets/global/css/animate.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('/public/assets/global/css/css-plugin-collections.css') }}?v=4" rel="stylesheet"/>
    <!-- CSS | menuzord megamenu skins -->
    <link id="menuzord-menu-skins"
          href="{{ URL::asset('/public/assets/global/css/menuzord-skins/menuzord-rounded-boxed.css') }}?v=3"
          rel="stylesheet"/>
    <!-- CSS | Main style file -->
    <link href="{{ URL::asset('/public/assets/global/css/style-main.css') }}?v=12" rel="stylesheet" type="text/css">
    <!-- CSS | Preloader Styles -->
{{--    <link href="{{ URL::asset('/public/assets/global/css/preloader.css') }}" rel="stylesheet" type="text/css">--}}
<!-- CSS | Custom Margin Padding Collection -->
    <link href="{{ URL::asset('/public/assets/global/css/custom-bootstrap-margin-padding.css') }}" rel="stylesheet"
          type="text/css">
    <!-- CSS | Responsive media queries -->
    <link href="{{ URL::asset('/public/assets/global/css/responsive.css') }}" rel="stylesheet" type="text/css">
    <!-- CSS | RTL Layout -->
    @if(App()->getLocale()=="fa")
    <link href="{{ URL::asset('/public/assets/global/css/bootstrap-rtl.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('/public/assets/global/css/style-main-rtl.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('/public/css/mmenu-light.css') }}?i=4" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('/public/assets/global/css/style-main-rtl-extra.css') }}" rel="stylesheet"
          type="text/css">
    @endif

<!-- CSS | Style css. This is the file where you can place your own custom css code. Just uncomment it and use it. -->
    <!-- <link href="css/style.css" rel="stylesheet" type="text/css"> -->


    <!-- CSS | Theme Color -->
    <link href="{{ URL::asset('/public/assets/global/css/colors/theme-skin-blue.css') }}" rel="stylesheet"
          type="text/css">
    <link href="{{ URL::asset('/public/assets/global/css/style.css') }}?v=7" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('/public/assets/panel/css/fonts.css') }}" rel="stylesheet" type="text/css">
    <link href="{{URL::asset('node_modules/pnotify/dist/PNotifyBrightTheme.css')}}?i=2" rel="stylesheet"
          type="text/css"/>


    @yield('css')
    <style>
        #mySidenav .row {
            z-index: 999;
            position: fixed;
            left: -230px;
            transition: 0.3s;
            padding: 15px 15px 15px 0;
            width: 300px;
            text-decoration: none;
            font-size: 14px;
            color: white;
            border-radius: 0 5px 5px 0;
        }

        #mySidenav :hover {
            left: 0;
        }

    </style>


</head>

<noscript>
    <!-- Error title -->
    <span class=" text-center content-group">
        <h1 class="error-title offline-title">Unreachable</h1>
        <h5>Sorry, our website needs javascript to be eanable</h5>
    </span>
    <!-- /error title -->
    <style>
        div {
            display: none;
        }

    </style>
</noscript>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-170091333-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-170091333-1');
</script>

<body class=" {{App()->getLocale()=="en"?'ltr':'rtl'}} dark">
<!-- external javascripts -->
<script src="{{ URL::asset('/public/assets/global/js/jquery-2.2.4.min.js') }}"></script>
<script src="{{ URL::asset('/public/assets/global/js/bootstrap.min.js') }}"></script>

{{--    <script src="{{ URL::asset('/public/assets/global/js/jquery-ui.min.js') }}"></script>--}}
<script src="{{ URL::asset('/public/js/mmenu-light.js') }}"></script>

<div id="wrapper" class="clearfix">
    <!-- preloader -->
{{--    <div id="preloader">--}}
{{--        <div id="spinner">--}}
{{--            <img class="floating align-self-center"  style="float: inherit" src="{{ URL::asset('/public/assets/global/images/preloaders/13.png') }}?i=0" alt="">--}}
{{--            <br>--}}
{{--            <h5 class="line-height-50 font-30 ml-15">{{trans('messages.Loading...')}}</h5>--}}
{{--        </div>--}}
{{--        <div id="disable-preloader" class="btn btn-default btn-sm">{{trans('messages.Disable_Preloader')}}</div>--}}
{{--    </div>--}}
<!-- header -->


    @include('layouts.global.navbar')
    @yield('content')
    @include('layouts.global.footer')
    @include('panel.materials.form_notification')

    @php
        $unpaidExist = \App\charity_periods_transaction::where( [
                    ['status', '=', 'unpaid'],
                    ['user_id', '=', \Illuminate\Support\Facades\Auth::id()],
                ])->exists();
    @endphp
    @if($unpaidExist)
        <div id="mySidenav" class="sidenav">

                <div class="row" style="bottom: 30px; background-color: #ff2e6c">
                    <div class="align-middle col-xs-3 col-sm-3 p-0" ><i
                                style="font-size: 40px" class="fa fa-bell animated swing infinite"></i></div>

                    <div class="col-xs-9 col-sm-9 float-left">
                        <a href="{{route('global_profile')}}" class="text-white" >
                        {{__('messages.you_have_unpaid_period')}}
                        </a>

                    </div>
                </div>

        </div>
    @endif

</div>



<!-- JS | jquery plugin collection for this theme -->
<script src="{{ URL::asset('/public/assets/global/js/jquery-plugin-collection.js') }}?i=2"></script>
@if(App()->getLocale()=="fa")
<script src="{{ URL::asset('/public/assets/global/js/localization/messages_fa.js') }}"></script>
@endif

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

@yield('js')


<!-- Footer Scripts -->

<script src="{{ URL::asset('node_modules/pnotify/dist/iife/PNotify.js') }}"></script>

<!-- JS | Custom script for all pages -->
<script src="{{ URL::asset('/public/assets/global/js/custom.js')}}?1=3"></script>
@yield('footer_js')

<script>
    $(document).ready(function () {
        @isset($errors)
        @foreach ($errors->all() as $key => $error)
        PNotify.error({
            title: '{{$key}}',
            text: '{{ $error }}',
            delay: 6000,
        });
        @endforeach
        @endif
        @if ($message = Session::get('message'))
        PNotify.success({
            text: '{{$message}}',
            delay: 6000,
        });
        @endif
        {{Session::forget('message')}}
    });
</script>
</body>
</html>
