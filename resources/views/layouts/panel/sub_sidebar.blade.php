@foreach($sub_sidebars as $child_key => $child)
    <?php
    $can = false;
    ?>
    @if(!empty($child['permission']))
        @permission($child['permission'])
        <?php $can = true;?>
        @endpermission
    @endif
    @if(!empty($child['permission']) == $can)
    <li class="nav-item
    {{!empty($child['child'])?"nav-item-submenu":""}}
    {{($child['child'] and in_array($child_key, $active_sidbare)) ? ' nav-item-open' : '' }}
    {{(!$child['child'] and in_array($child_key, $active_sidbare)) ? ' active' : '' }}">
        <a href="{{isset($child['url']) ? $child['url'] : ($child['link']? route($child['link']):"#")}}"
           class="nav-link {{in_array($child_key, $active_sidbare) ? 'active' : '' }}">
            <span>{{trans($child['title'])}}</span>

        @if(!empty($child['badge']) and ${$child['badge']}>0)}
            <span class="badge badge-danger align-self-center ml-auto">{{${$child['badge']} }}</span>
        @endif
        </a>
            @if(count($child['child'])>0)
        <ul class="nav nav-group-sub"
            style="display:{{in_array($child_key, $active_sidbare) ? 'block' : 'none' }}">
            @include('layouts.panel.sub_sidebar',['sub_sidebars'=>$child['child']])
        </ul>
        @endif
    </li>
    @endif
@endforeach