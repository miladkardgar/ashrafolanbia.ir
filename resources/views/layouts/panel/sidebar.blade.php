<?php
if (!isset($active_sidbare)) {
    $active_sidbare = [];
}
$my_sidebar = config('sidebar_admin');

?>
<!-- Main sidebar -->
<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">

    <!-- Sidebar mobile toggler -->
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="icon-arrow-right8"></i>
        </a>
        تمام صفحه
        <a href="#" class="sidebar-mobile-expand">
            <i class="icon-screen-full"></i>
            <i class="icon-screen-normal"></i>
        </a>
    </div>
    <!-- /sidebar mobile toggler -->

    <!-- Sidebar content -->
    <div class="sidebar-content">

        <!-- User menu -->
        <div class="sidebar-user">
            <div class="card-body">
                <div class="media">
                    <div class="mr-3">
                        <a href="#"><img src="{{ URL::asset(user_information('avatar')) }}" width="38" height="38"
                                         class="rounded-circle" alt=""></a>
                    </div>


                    <div class="media-body">
                        <div class="media-title font-weight-semibold">{{user_information('full')}}</div>

                    </div>

                    <div class="ml-3 align-self-center">
                        <a href="{{route('global_profile',user_information('id'))}}" class="text-white"><i
                                    class="icon-cog3"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /user menu -->

        <!-- Main navigation -->
        <div class="card card-sidebar-mobile">
            <ul class="nav nav-sidebar" data-nav-type="accordion">

                <!-- Main -->
                <li class="nav-item-header">
                    <div class="text-uppercase font-size-xs line-height-xs"></div>
                    <i class="icon-menu" title="Main"></i>
                </li>
                @foreach($my_sidebar as $key => $sidebar)
                    <?php
                    $can = false;
                    ?>
                    @if(!empty($sidebar['permission']))
                        @permission($sidebar['permission'])
                        <?php $can = true;?>
                        @endpermission
                    @endif
                    @if(!empty($sidebar['permission']) == $can)
                            <li class="nav-item
                                {{!empty($sidebar['child'])?"nav-item-submenu":""}}
                                {{($sidebar['child'] and in_array($key, $active_sidbare)) ? ' nav-item-open' : '' }}
                                {{(!$sidebar['child'] and in_array($key, $active_sidbare)) ? ' active' : '' }}">
                                <a href="{{isset($sidebar['url']) ? $sidebar['url'] : ($sidebar['link']? route($sidebar['link']):"#")}}" class=" nav-link"><i class="{{$sidebar['icon']}}"></i>
                                    <span>{{trans($sidebar['title'])}}</span>
                                    @if(!empty($sidebar['badge']) and ${$sidebar['badge']} >0)
                                        <span class="badge badge-danger align-self-center ml-auto">{{${$sidebar['badge']} }}</span>
                                    @endif
                                </a>
                                    @if(!empty($sidebar['child']))
                                    <ul class="nav nav-group-sub" data-submenu-title="{{trans($sidebar['title'])}}"
                                        style="display:{{in_array($key, $active_sidbare) ? 'block' : 'none' }}">
                                        @include('layouts.panel.sub_sidebar',['sub_sidebars'=>$sidebar['child']])
                                    </ul>
                                    @endif
                            </li>
                    @endif
                @endforeach

            </ul>
        </div>
        <!-- /main navigation -->

    </div>
    <!-- /sidebar content -->
</div>
<!-- /main sidebar -->
