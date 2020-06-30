<div class=" card " >
    <div class="card-header bg-info">

    </div>
<div class="province-tree card card-body border-left-info border-left-2 " >
    <ul class="mb-0 p-0">
        @foreach($provinces as $province)
            <li class="li-tree ">
                <input class="parent_{{$province['parent']}}" data-parent="{{$province['parent']}}" type="checkbox"
                       id="city_{{ $province['id'] }}">
                <a href="{{route('building_dashboard')}}/?city={{$province['id'] }}"
                   class="{{$selected_city==$province['id']? "text-danger":"text-black-50"}} font-weight-bold ">
                    {{ $province['name'] }}
                </a>
                <span class="badge badge-success" data-popup="tooltip"
                      title="پروژه های باز">{{$province['openProjects']}}</span>
                <span class="badge badge-info" data-popup="tooltip"
                      title="پروژه های تمام شده">{{$province['archivedProjects']}}</span>

                @if(count($province['cities']))
                    @include('panel.building.materials.sub_tree',['cities' => $province['cities'] ,'parent_id'=>$province['id']])
                @endif
            </li>
        @endforeach
    </ul>
</div>
</div>

<script>

    $(document).on('change', '[class^="parent_"]', function (e) {
        var id = $(this).attr("id");
        var city_id = id.replace("city_", '');
        var parent_id = $(this).attr("data-parent");
        checker(city_id);
        indetermindChecker(parent_id);

    });

    function checker(city_id) {

        if ($('#city_' + city_id + ':checkbox:checked').length > 0) {
            $('.parent_' + city_id).prop('checked', true);
        } else {
            $('.parent_' + city_id).prop('checked', false);
        }
        $('.parent_' + city_id).each(function (index) {
            var sub_id = $(this).attr("id");
            var sub_city_id = sub_id.replace("city_", '');
            checker(sub_city_id);
        });
    };

    function indetermindChecker(parent_id) {
        var checkboxes = $('input.parent_' + parent_id).length;
        var checkedCount = $('input.parent_' + parent_id + ':checked').length;
        $("#city_" + parent_id).checked = checkedCount > 0;
        $("#city_" + parent_id).indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;

    }


</script>