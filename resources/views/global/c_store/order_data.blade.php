@extends('layouts.global.global_layout')
@section('js')
    <script
            src="{{ URL::asset('/public/assets/panel/global_assets/js/plugins/forms/validation/validate.min.js') }}"></script>
    <script src="{{ URL::asset('/public/assets/global/js/localization/messages_fa.js') }}"></script>
    <script src="{{ URL::asset('/node_modules/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script src="{{asset('public/assets/global/js/leatflat/leaflet.js')}}"></script>
    <script
            src="{{ URL::asset('/node_modules/md.bootstrappersiandatetimepicker/src/jquery.md.bootstrap.datetimepicker.js') }}"></script>
    <script src="{{URL::asset('/public/js/bootstrap-clockpicker.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            var map = L.map('mapid').setView([35.700, 51.400], 11);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                accessToken: 'sk.eyJ1IjoibWlsYWRrYXJkZ2FyIiwiYSI6ImNqenU2cjIweDAxeGozY283eGF0NXgxamwifQ.Zf18DPBuHLhHR8FIONTtWg'
            }).addTo(map);
            map.on('click', function (e) {
                $(".leaflet-marker-pane").html("");
                $(".leaflet-shadow-pane").html("");
                var marker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(map);
                marker.bindPopup("<span>{{__('messages.your_location')}}: </span>" + e.latlng.lat + " | " + e.latlng.lng + "<br>").openPopup();
                $("#lat").val(e.latlng.lat);
                $("#lon").val(e.latlng.lng);
            });
            $("#frm_add_address").validate({
                lang: "fa",
                rules: {
                    province: {
                        required: true,
                    },
                    cities: {
                        required: true,
                    },
                    address: {
                        required: true,
                        minlength: 3
                    },
                    receiver: {
                        required: true,
                        minlength: 5,
                        maxlength: 100,
                    },
                    mobile: {
                        minlength: 11,
                        maxlength: 11,
                        number: true
                    },
                    phone: {
                        minlength: 11,
                        maxlength: 11,
                        number: true
                    }
                },
            });

            $('#meeting_date').MdPersianDateTimePicker({
                targetTextSelector: '#meeting_date',
                enableTimePicker: false,
                disableBeforeDate: new Date({{intval(date('Y',$firstDate))}},{{intval(date('m',$firstDate))-1}},{{intval(date('d',$firstDate))}}),
                englishNumber: false,
                // disabledDays:[6],
            });

            $(document).on('change', '#province', function () {
                var pro = $(this).val();
                $.ajax({
                    url: "{{route('get_city_list')}}",
                    type: "post",
                    data: {proID: pro},
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
                    },
                    success: function (response) {
                        $("#cities").html("");
                        $.each(response, function (i, item) {
                            $("#cities").append("<option value='" + response[i].id + "'>" + response[i].name + "</option>");
                        });
                    }, error: function (error) {
                        $.each(error.responseJSON.errors, function (i, item) {
                            PNotify.error({
                                text: item,
                                delay: 3000,
                            });
                        });
                    }
                });
            })
            $('.clockpicker').clockpicker();


        })

    </script>
@stop
@section('css')
    <link
            href="{{ URL::asset('/node_modules/md.bootstrappersiandatetimepicker/src/jquery.md.bootstrap.datetimepicker.style.css') }}"
            rel="stylesheet" type="text/css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.min.css"/>
    <link rel="stylesheet" href="{{ URL::asset('public/assets/global/js/leatflat/leaflet.css')}}"/>
    <style>
        .border {
            border: 2px solid #88e0a1 !important;
        }

        #mapid {
            height: 400px;
        }
    </style>
@stop
@section('content')
    <div class="main-content">
        {{@csrf_field()}}
        <section class="">
            <div class="container mt-30 mb-30 p-30">

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered border">
                            <tbody>
                            <tr>
                                <td  colspan="1" class="col-md-1 success"><i
                                            class="fa fa-check-square-o fa-3x text-success mr-20 mt-10"></i></td>
                                <td >
                                    <h4 class="">
                                    <label class="text-secondary">شماره موبایل سفارش دهنده:</label>
                                    <span>{{$user['phone']}}</span>
                                    </h4>
                                </td>


                            </tr>
                            </tbody>
                        </table>
                        <div class="clearfix"></div>
                        <strong><i class="fa fa-angle-left"></i> {{__('اطلاعات مراسم')}}</strong>
                        <form action="{{route('global.c_store_card_completion_order_save')}}" autocomplete="off" id="frm_add_address"
                              method="post"
                              class="border">
                            @csrf
                            <div class="row add-address m-20">
                                <div class="col-md-6 col-xs-12 form-group">
                                    <div class="col-md-6 col-xs-12 form-group">
                                        <label for="province">{{__('messages.province')}}</label>
                                        <select name="province" required="required" id="province" class="form-control">
                                            <option value="">{{__('messages.please_select')}}</option>
                                            @foreach($allowed_provinces as $province)
                                                <option value="{{$province['id']}}"
                                                        <?php
                                                            if (isset($order['province'])){
                                                                $selected_pro =$order['province'];
                                                            }else{
                                                                $selected_pro = old('province');
                                                            }
                                                                  ?>
                                                {{ $selected_pro == $province['id'] ? "select":"" }}
                                                >{{$province['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-xs-12 form-group">
                                        <label for="cities">{{__('messages.city')}}</label>
                                        <select name="cities" required="required" id="cities" class="form-control">

                                        </select>
                                    </div>
                                    <div class="col-md-6 col-xs-12 form-group">
                                        <label for="receiver">{{__('messages.receiver_name')}}</label>
                                        <input type="text" class="form-control" required="required" name="receiver"
                                            value="{{isset($order['receiver']) ? $order['receiver'] : old('receiver')}}">
                                    </div>
                                    <div class="col-md-6 col-xs-12 form-group">
                                        <label for="zip_code">{{__('messages.zip_code')}}</label>
                                        <input type="text" class="form-control" name="zip_code"
                                               value="{{isset($order['zip_code']) ? $order['zip_code'] : old('zip_code')}}">
                                    </div>
                                    <div class="col-md-6 col-xs-12 form-group">
                                        <label for="phone">{{__('messages.phone')}}</label>
                                        <input type="text" class="form-control input-sm" dir="ltr" name="phone"
                                               value="{{isset($order['phone']) ? $order['phone'] : old('phone')}}">
                                    </div>
                                    <div class="col-md-6 col-xs-12 form-group">
                                        <label for="mobile">{{__('messages.mobile')}}</label>
                                        <input type="text" class="form-control" dir="ltr" name="mobile" value="{{$user['phone']}}">
                                    </div>
                                    <div class="col-md-12 col-xs-12 form-group">
                                        <label for="description">{{__('messages.descriptions')}}</label>
                                        <textarea name="description" id="description" class="form-control" cols="30"
                                                  rows="4">{{isset($order['description']) ? $order['description'] : old('description')}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12 form-group ">
                                    <div class="col-md-12 col-xs-12 form-group">
                                        <label for="receiver">{{__('messages.condolences_to')}}</label>
                                        <input type="text" class="form-control" required="required"
                                               name="condolences_to" value="{{isset($order['condolences_to']) ? $order['condolences_to'] : old('condolences_to')}}">
                                    </div>
                                    <div class="col-md-6 col-xs-12 form-group">
                                        <label for="receiver">{{__('messages.on_behalf_of')}}</label>
                                        <input type="text" class="form-control" required="required" name="from_as"
                                               value="{{isset($order['from_as']) ? $order['from_as'] : old('from_as')}}">
                                    </div>
                                    <div class="col-md-6 col-xs-12 form-group">
                                        <label for="receiver">{{__('messages.late_name')}}</label>
                                        <input type="text" class="form-control" required="required" name="late_name"
                                               value="{{isset($order['late_name']) ? $order['late_name'] : old('late_name')}}">
                                    </div>
                                    <div class="col-md-6 col-xs-12 form-group">
                                        <label for="receiver">{{__('messages.meeting_date')}}</label>
                                        <input type="text" class="form-control" required="required" id="meeting_date"
                                               name="meeting_date" >
                                    </div>
                                    <div class="col-md-6 col-xs-12 form-group">
                                        <label for="receiver">{{__('messages.meeting_time')}}</label>
                                        <input type="text" class="form-control clockpicker" required="required"
                                               value="09:30" name="meeting_time">
                                    </div>
                                    <div class="col-md-12 col-xs-12 form-group">
                                        <label for="meeting_address">{{__('messages.meeting_address')}}</label>
                                        <textarea cols="30" rows="4" class="form-control" required="required"
                                                  name="meeting_address" id="meeting_address">{{isset($order['meeting_address']) ? $order['meeting_address'] : old('meeting_address')}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12 form-group">
                                    <label for="mapid">{{__('messages.map_position')}}</label>
                                    <input type="hidden" name="lat" id="lat" value="{{isset($order['lat']) ? $order['lat'] : old('lat')}}">
                                    <input type="hidden" name="lon" id="lon" value="{{isset($order['lon']) ? $order['lon'] : old('lon')}}">
                                    <div id="mapid"></div>
                                </div>
                                <div class="col-md-12 col-xs-12 form-group">
                                    <button type="submit"
                                            class="btn btn-success pull-left p-10 pr-20 pl-20">{{__('messages.continue_shopping')}}
                                        <i class="fa fa-caret-left pr-10"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
