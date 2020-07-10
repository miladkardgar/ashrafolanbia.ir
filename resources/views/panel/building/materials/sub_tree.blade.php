    <ul>
        @foreach($cities as $child)
            <li class="li-tree ">
                <input class="parent_{{$child['parent']}}" data-parent="{{$child['parent']}}" type="checkbox"
                       id="city_{{ $child['id'] }}" name="city[]" value="{{ $child['id'] }}">
                <a href="{{route('building_dashboard')}}/?city={{$child['id'] }}"
                   class="{{$selected_city == $child['id'] ? "text-danger":"text-black-50"}} font-weight-bold"
                > {{ $child['name'] }} </a>
                <span class="badge badge-success " data-popup="tooltip"
                      title="پروژه های باز">{{$child['openProjects']}}</span>
                <span class="badge badge-info " ata-popup="tooltip"
                      title="پروژه های تمام شده">{{$child['archivedProjects']}}</span>
                @if(count($child['cities']))
                    @include('panel.building.materials.sub_tree',['cities' => $child['cities'],'parent_id'=>$province['id']])
                @endif
            </li>
        @endforeach
    </ul>
