@extends('layouts.panel.panel_layout')
@section('js')
    <script src="{{ URL::asset('/public/assets/panel/global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ URL::asset('/node_modules/md.bootstrappersiandatetimepicker/src/jquery.md.bootstrap.datetimepicker.js') }}"></script>
    <script src="http://www.openlayers.org/api/OpenLayers.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places"></script>
    <script src="{{ URL::asset('/public/assets/panel/js/location-picker.js') }}"></script>

@endsection
@section('css')
    <link href="{{ URL::asset('/node_modules/md.bootstrappersiandatetimepicker/src/jquery.md.bootstrap.datetimepicker.style.css') }}"
          rel="stylesheet" type="text/css">
    <style>
        #map {
            height: 400px;
            width: 100%;
        }

        .map-container {
            margin-top: 10px;
        }


        textarea {
            width: 100%
        }


    </style>
@endsection

@section('content')
    <?php
    $active_sidbare = ['building', 'building_dashboard']
    ?>
    <div class="content">
        <div class="row">
            <div class="col-md-3" >
                @include('panel.building.materials.tree_view');
            </div>
            <div class="col-md-9">
                <div class="card">

                    <div class="card-header bg-light">
                        <div class="header-elements-inline">

                        <button type="button" class="btn btn-outline-info btn-lg modal-ajax-load"
                                data-ajax-link="{{route('load_new_building_form')}}" data-toggle="modal"
                                data-modal-title="{{trans('messages.add_new_building')}}"
                                data-modal-size="modal-lg"
                                data-target="#general_modal"><i
                                    class="icon-home8 mr-2"></i> {{trans('messages.add_new_building')}}
                        </button>
                        <div class="header-elements font-weight-bold text-danger">
                            {{\App\city::find($selected_city)['name']}}
                        </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @foreach($projects->chunk(4) as $chunk)
                            <div class="row">
                                @foreach($chunk as $province)
                                    <div class="col-md-3">

                                        <div class="card border-2px border-info alpha-info mb-0">
                                            <div class="card-img-actions px-1 pt-1 pb-1">
                                                <img class="card-img img-fluid img-absolute "
                                                     src="{{$lvl=='project' ? asset('/public/assets/panel/images/building.jpg') : asset('/public/assets/panel/images/3.png')}}" alt="">
                                                <div class="card-img-actions-overlay  card-img bg-dark-alpha">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="card border-2px border-info alpha-info mt-0">

                                            <div class="card-body border-2px border-info  p-0 m-0">
                                                <div class="row p-0 m-0">


                                                    <div class="m-0  col-sm-12 ">
                                                        @if($lvl == 'project')
                                                            <a href={{route('building_project',['project_id'=>$province['id']])}}>
                                                                <h3>
                                                                    <b class="text-info">{{$province['title']}}</b>

                                                                </h3>
                                                            </a>

                                                            @else

                                                        <a href="{{route('building_dashboard')}}/?city={{$province[$lvl]}}">
                                                            <h3>
                                                                <b class="text-info">{{get_cites($province[$lvl])['name']}}</b>
                                                                <span class="badge-info badge badge-pill" data-popup="tooltip" title="پروژه ها">{{$lvl=='project' ? "":$province['total'] }} </span>
                                                            </h3>
                                                        </a>
                                                            @endif
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
