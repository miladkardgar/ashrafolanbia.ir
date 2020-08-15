<!-- Header -->
<style>
    .menuzord-menu > li {
        padding: 4px 0 !important;
    }

    #banner a {
        position:fixed;
        display:block;
        right:5px;
        bottom:5px;

        width:150px;
        height:150px;
        background:url('') transparent no-repeat scroll center center;
    }
</style>
<?php $locals = get_all_locals(); ?>

<div class="top-bar top-bar-color-light">
    <div class="container">
        <div class="row top-bar-row">
            <div class="pt-10 pr-10 top-bar-half  "  >


                @if(Auth::check())

                    <a class="text-white"
                       href="{{route('logout')}}">{{trans('messages.logout')}}</a>
                    <span class="text-white">|</span>
                @endif
                @foreach($locals as $local)
                    @if($local != App()->getLocale())

                        <a class="text-white"
                           href="/{{$local}}">{{trans("words.$local")}}</a>
                    @endif
                @endforeach


            </div>
            <div class="top-bar-half">
                <a href="#" data-target="#searchModal" data-toggle="modal" class=" top-bar-btn " style="font-size: 1.25rem;padding: 0 14px ;"><i class="fa fa-search text-white-f6"></i></a>

                @if(Auth::check())
                    <a class="text-white top-bar-btn hidden-md hidden-lg pl-2 pr-2"
                       href="{{route('global_profile')}}" > <span class="fa fa-heart"> </span> &nbsp; {{trans('messages.account')}}  </a>

                @else
                    <a class="text-white top-bar-btn hidden-md hidden-lg"
                       href="{{route('global_register_page')}}" > {{trans('messages.register')}}</a>
                    <a class="text-white top-bar-btn hidden-md hidden-lg"
                       href="{{route('global_login_page')}}" > {{trans('messages.login')}}</a>

                @endif



            </div>
        </div>
    </div>
</div>
<header id="header" class="header">

    <div class="header-top p-0 text-black bg-silver-light xs-text-center"
         data-bg-img="{{ URL::asset('/public/assets/global/images/footer-bg.png') }}">

        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="widget no-border m-0">
                        <a class="menuzord-brand pull-right sm-text-center xs-text-center xs-pull-center mb-5"
                            href="/">
                            <img class="img img-responsive sm-text-center xs-text-center" style="height: 5.5rem"
                                 src="{{ URL::asset('/public/assets/global/images/logo-wide@2x.png')}}?i=4" alt=""></a>

                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="widget no-border clearfix m-0 float-left  hidden-xs hidden-sm " style="text-align: left;padding-top: 2em">
                        @if(Auth::check())
                            <a class="btn btn-lg btn-theme-colored"
                               href="{{route('global_profile')}}"><span class="fa fa-heart"></span> {{trans('messages.account')}}  </a>
                            <span class="text-white">|</span>

                        @else
                            <a class="btn btn-theme-colored"
                               href="{{route('global_login_page')}}"> {{trans('messages.login')}}</a>
                            <span class="text-white">|</span>
                            <a class="btn btn-warning"
                               href="{{route('global_register_page')}}"><span class=" fa fa-user-plus"></span> {{trans('messages.register')}}</a>
                        @endif


                    </div>

            </div>
        </div>
    </div>
    <div class="header-nav ">
        <div class="header-nav-wrapper navbar-scrolltofixed bg-theme-colored-darker4">
            <div class="container">
                <nav id="menuzord" class="menuzord default bg-theme-colored-darker4">

                    <a href="#menu" class="hidden-sm hidden-md hidden-lg mmenu-btn" style="display: inline;"><em></em><em></em><em></em></a>



                    <ul class="menuzord-menu {{App()->getLocale()=="en"?'pull-left':'pull-right'}}">
                        @foreach($menu as $item)
                            <li><a class="text-white-f6" href="{{$item['url']}}">{{$item['name']}}</a>
                                @if($item->subMenu()->exists())
                                    @include('layouts.global.nested_menu',['sub_menu'=>$item->subMenu])
                                @endif
                            </li>
                        @endforeach

                        @if(session()->get('cart'))
                            <li><a href="{{route('store_cart')}}">{{__('messages.buy_basket')}}</a></li>
                        @endif
                        @if(has_caravan())
                            <li><a href="{{route('global_caravan')}}">{{__('messages.caravan')}}</a></li>
                        @endif

                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <nav id="menu">

        <ul>

            @foreach($menu as $item)
                <li><a class="" href="{{$item['url']}}">{{$item['name']}}</a>
                <br>
                    @if($item->subMenu()->exists())
                        @include('layouts.global.nested_menu',['sub_menu'=>$item->subMenu->sortBy('order')])
                    @endif
                </li>
            @endforeach
        </ul>
    </nav>

</header>


<script>
    var menu = new MmenuLight( document.querySelector( '#menu' ), {
         title: '{{trans('site_info.site_name')}}',
         theme: 'light',// 'dark'
        // slidingSubmenus: true,// false
        // selected: 'Selected'
    });
    menu.enable( 'all' ); // '(max-width: 900px)'
    menu.offcanvas({
         position: 'right',// 'right'
        // move: true,// false
        // blockPage: true,// false / 'modal'
    });

    //	Open the menu.
    document.querySelector( 'a[href="#menu"]' )
        .addEventListener( 'click', ( evnt ) => {
            menu.open();

            //	Don't forget to "preventDefault" and to "stopPropagation".
            evnt.preventDefault();
            evnt.stopPropagation();
        });

</script>



