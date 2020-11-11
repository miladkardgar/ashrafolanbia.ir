@extends('layouts.panel.panel_layout')
@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('js')
    <script
        src="{{ URL::asset('/node_modules/md.bootstrappersiandatetimepicker/src/jquery.md.bootstrap.datetimepicker.js') }}"></script>
    <script
        src="{{ URL::asset('/public/assets/panel/global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ URL::asset('/public/assets/panel/global_assets/js/plugins/visualization/d3/d3.min.js') }}"></script>
    <script
        src="{{ URL::asset('/public/assets/panel/global_assets/js/plugins/visualization/d3/d3_tooltip.js') }}"></script>

    <script
        src="{{ URL::asset('/public/assets/panel/global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script
        src="{{ URL::asset('/public/assets/panel/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js') }}"></script>
    <script
        src="{{ URL::asset('/public/assets/panel/global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            $("body").addClass('sidebar-xs')
            $('#start_date_btn').MdPersianDateTimePicker({
                targetTextSelector: '#start_date',
                fromDate: true,
                groupId: 'dateRangeSelector1',
                enableTimePicker: true,
                englishNumber: true
            });
            $('#end_date_btn').MdPersianDateTimePicker({
                targetTextSelector: '#end_date',
                toDate: true,
                groupId: 'dateRangeSelector1',
                enableTimePicker: true,
                englishNumber: true

            });
            $('.form-check-input-styled').uniform();

            $(document).on("submit", '#frm_report', function (e) {
                e.preventDefault();
                var btn = $('button[type=submit]');
                btn.attr('disabled', true)
                btn.html('لطفا منتظر بمانید...<i class="fa fa-spin fa-spinner"></i>')
                $.ajax({
                    url: '{{route('charity_reports_ajax')}}',
                    type: 'post',
                    data: $(this).serialize(),
                    success: function (response) {
                        $("#res").html(response)
                        btn.attr('disabled', false)
                        btn.html('دریافت گزارش')
                    },
                    error: function (error) {
                        console.log(error)
                        btn.attr('disabled', false)
                        btn.html('دریافت گزارش')
                    }
                });
            })

            $('#charity_donate').change(function () {
                if (this.checked) {
                    $(".type").css('display', 'block');
                } else {
                    $(".type").css('display', 'none');
                }
            });
            $('input[name=checkAll]').change(function () {
                if (this.checked) {
                    $('.typeCh').parent().addClass('checked');
                } else {
                    $('.typeCh').parent().removeClass('checked');
                }
            });
        });

    </script>
@endsection
@section('css')
    <link
        href="{{ URL::asset('/node_modules/md.bootstrappersiandatetimepicker/src/jquery.md.bootstrap.datetimepicker.style.css') }}"
        rel="stylesheet" type="text/css">
@stop
@php
    $active_sidbare = ['charity', 'charity_reports']
@endphp
@section('content')
    <section>
        <div class="content">
            <section>
                <div class="card">
                    <div class="card-header pb-0">
                        <h6 class="card-title text-black">{{__('messages.Charity')}} | {{__('messages.reports')}}</h6>
                        <hr>
                    </div>
                    <div class="card-body">
                        <form action="" id="frm_report">
                            @csrf
                            <div class="d-flex justify-content-center">
                                <div class="m-2">
                                    <div class="form-group">
                                        <label for="from">{{__('messages.from')}}</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" readonly="readonly" id="start_date"
                                                   name="start_date"
                                                   required="required"
                                                   value="{{jdate("Y-m-d 00:00:01",time())}}">
                                            <button class="btn btn-outline-dark btn-sm" type="button"
                                                    id="start_date_btn"><i
                                                    class="icon-calendar"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-2">
                                    <div class="form-group">
                                        <label for="to">{{__('messages.to')}}</label>
                                        <div class="input-group">
                                        <input type="text" class="form-control" readonly="readonly" id="end_date"
                                               name="end_date"
                                               required="required"
                                               value="{{jdate("Y-m-d 23:59:59",time())}}">
                                        <button class="btn btn-outline-dark btn-sm" type="button" id="end_date_btn">
                                            <i class="icon-calendar"></i>
                                        </button>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <div class="m-2">
                                    <label for="">{{__('messages.gateway')}}</label>
                                    <div class="form-group mb-3 mb-md-2">
                                        @foreach($gateway as $g)
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input-styled"
                                                           name="gateway[]" value="{{$g['port']}}" checked
                                                           data-fouc>
                                                    {{$g['port']}}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="m-2">
                                    <label for="">{{__('messages.payment_type')}}</label>
                                    <div class="form-group mb-3 mb-md-2">
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input-styled" name="type[]"
                                                       value="charity_vow" checked
                                                       data-fouc>
                                                {{__('messages.charity_vow')}}
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input-styled" name="type[]"
                                                       value="charity_donate" id="charity_donate"
                                                       data-fouc>
                                                {{__('messages.charity_donate')}}
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input-styled" name="type[]"
                                                       value="charity_period" checked
                                                       data-fouc>
                                                {{__('messages.charity_period')}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-2">
                                    <label for="">{{__('messages.payment_type')}}</label>
                                    <div class="form-group mb-3 mb-md-2">
                                        @foreach($pat as $pa)
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input-styled" name="pat[]"
                                                           value="{{$pa['id']}}" checked
                                                           data-fouc>
                                                    {{$pa['title']}}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="type" style="display: none">
                                <label for="">{{__('messages.type')}}</label>
                                <div class="row">
                                    <div class="col-12">
                                        {{--                                        <div class="form-check form-check-inline">--}}
                                        {{--                                            <label class="form-check-label">--}}
                                        {{--                                                <input type="checkbox" class="form-check-input-styled"--}}
                                        {{--                                                       name="checkAll"--}}
                                        {{--                                                       data-fouc><strong>علامت گزاری همه موارد</strong>--}}
                                        {{--                                            </label>--}}
                                        {{--                                        </div>--}}
                                        {{--                                        <hr>--}}

                                    </div>
                                    @foreach($titles as $title)
                                        <div class="col-3">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input-styled typeCh"
                                                           name="chType[]" value="{{$title['id']}}"
                                                           data-fouc>
                                                    {{$title['title']}}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="status">
                                <label for="">{{__('messages.status')}}</label>
                                <div class="row">
                                    <div class="col-3">
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input-styled status"
                                                       name="status[]" value="SUCCEED"
                                                       data-fouc>
                                                پرداخت موفق
                                            </label>
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input-styled status"
                                                       name="status[]" value="FAILED"
                                                       data-fouc>
                                                پرداخت ناموفق
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-center">
                                <div class="m-2">
                                    <button type="submit" class="btn btn-success">{{__('messages.reports')}}</button>
                                </div>
                            </div>
                        </form>
                        <div id="res"></div>
                    </div>
                </div>
            </section>
        </div>
    </section>
@stop
