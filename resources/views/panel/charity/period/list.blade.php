@extends('layouts.panel.panel_layout')
@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('js')
    <script src="{{ URL::asset('/public/assets/panel/global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script
            src="{{ URL::asset('/public/assets/panel/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js') }}"></script>
    <script src="{{ URL::asset('/node_modules/md.bootstrappersiandatetimepicker/src/jquery.md.bootstrap.datetimepicker.js') }}"></script>
    <script>
        $(document).ready(function () {

            $('#from-date_btn').MdPersianDateTimePicker({
                targetTextSelector: '#from-date',
                enableTimePicker: false,
                englishNumber: false,
            });
            $('#to-date_btn').MdPersianDateTimePicker({
                targetTextSelector: '#to-date',
                enableTimePicker: false,
                englishNumber: false,
            });
        });
    </script>
@endsection
@section('css')
    <link href="{{ URL::asset('/node_modules/md.bootstrappersiandatetimepicker/src/jquery.md.bootstrap.datetimepicker.style.css') }}"
          rel="stylesheet" type="text/css">
    <style >
        .tableFixHead          { overflow-y: auto; height: 700px; }
        .tableFixHead thead th { position: sticky; top: 0; }

        /* Just common table stuff. Really. */
        table  { border-collapse: collapse; width: 100%; }
        th, td { padding: 8px 16px; }
        th     { background: #29f0e4; }
    </style>
@stop
@php
    $active_sidbare = ['charity', 'charity_period','charity_period_list']
@endphp
@section('content')
    <section>
        <div class="content">
            <section>
                <div class="row text-center">
                    @permission('charity_periodic_view_active_users')

                    <div class="col-sm-6 col-xl-3">
                        <div class="card card-body bg-success-400 has-bg-image">
                            <div class="media">
                                <div class="media-body text-left">
                                    <h3 class="mb-0">{{number_format($active_users)}}  </h3>
                                    <span class="text-uppercase font-size-xs">نیکوکار فعال کمک ماهانه</span>
                                </div>
                                <div class="mr-3 align-self-center">
                                    <i class="icon-users icon-3x opacity-75"></i>
                                </div>

                            </div>
                        </div>
                    </div>
                    @endpermission
                    @permission('charity_periodic_view_inactive_users')

                    <div class="col-sm-6 col-xl-3">

                        <div class="card card-body bg-danger-400 has-bg-image">
                            <div class="media">
                                <div class="media-body text-left">
                                    <h3 class="mb-0">{{number_format($inactive_users)}}  </h3>
                                    <span class="text-uppercase font-size-xs">نیکوکار بدون کمک ماهانه</span>
                                </div>
                                <div class="mr-3 align-self-center">
                                    <i class="icon-user-cancel icon-3x opacity-75"></i>
                                </div>

                            </div>
                        </div>
                    </div>
                    @endpermission
                    @permission('charity_periodic_view_paid_count')
                    <div class="col-sm-6 col-xl-3">

                        <div class="card card-body bg-info-400 has-bg-image">
                            <div class="media">
                                <div class="media-body text-left">
                                    <h3 class="mb-0">{{number_format($paid_routine)}}  </h3>
                                    <span class="text-uppercase font-size-xs"> تعهد پرداخت شده</span>
                                </div>
                                <div class="mr-3 align-self-center">
                                    <i class="icon-checkmark-circle icon-3x opacity-75"></i>
                                </div>

                            </div>
                        </div>
                    </div>
                    @endpermission
                    @permission('charity_periodic_view_unpaid_count')
                    <div class="col-sm-6 col-xl-3">

                        <div class="card card-body bg-indigo-400 has-bg-image">
                            <div class="media">
                                <div class="media-body text-left">
                                    <h3 class="mb-0">{{number_format($unpaid_routine)}}  </h3>
                                    <span class="text-uppercase font-size-xs"> تعهد پرداخت نشده</span>
                                </div>
                                <div class="mr-3 align-self-center">
                                    <i class="icon-hour-glass icon-3x opacity-75"></i>
                                </div>

                            </div>
                        </div>
                    </div>
                    @endpermission

                </div>

                <!-- Search field -->
                <div class="card">
                    <div class="card-body">

                        <form action="#">
                            <div class="input-group mb-3">
                                <div class="form-group-feedback form-group-feedback-left">
                                    <input type="text" id="t_search_q" class="form-control form-control-lg alpha-grey"
                                           placeholder="ایمیل یا شماره موبایل">
                                    <div class="form-control-feedback form-control-feedback-lg">
                                        <i class="icon-search4 text-muted"></i>
                                    </div>
                                </div>

                                <div class="input-group-append">
                                    <button type="button" id="t_search" class="btn btn-primary btn-lg">جستجو جامع</button>
                                </div>
                            </div>

                            <div class="d-md-flex align-items-md-center flex-md-wrap text-center text-md-left">
                                <ul class="list-inline list-inline-condensed mb-0">
{{--                                    <li class="list-inline-item font-weight-bold">--}}
{{--                                        <a href="#" id="t_search_reset" class="text-green-700 btn btn-link text-default "--}}
{{--                                        >--}}
{{--                                            <i class="icon-reset mr-2"></i>--}}
{{--                                            حذف فیلتر ها--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
                                    <li class="list-inline-item dropdown">
                                        <a href="#" class="btn btn-link text-default dropdown-toggle"
                                           data-toggle="dropdown">
                                            <i class="icon-stack2 mr-2"></i>
                                            مرتب سازی:
                                        </a>

                                        <div class="dropdown-menu">
                                            <a href="#" data-param="sort"
                                               data-value="date-a" class="dropdown-item t_filter">نزدیکترین تاریخ پرداخت</a>
                                            <a href="#" data-param="sort"
                                               data-value="date-d" class="dropdown-item t_filter">دور ترین تاریخ پرداخت</a>
                                            <a href="#" data-param="sort"
                                               data-value="date-r-a" class="dropdown-item t_filter">نزدیکترین تاریخ عضویت</a>
                                            <a href="#" data-param="sort"
                                               data-value="date-r-d" class="dropdown-item t_filter">دور ترین تاریخ عضویت</a>
                                            <a href="#" data-param="sort"
                                               data-value="count-d" class="dropdown-item t_filter"> بیشترین در انتظار پرداخت</a>
                                            <a href="#" data-param="sort"
                                               data-value="count-a" class="dropdown-item t_filter">  کمترین در انتظار پرداخت</a>
                                            <a href="#" data-param="sort"
                                               data-value="count-p-a" class="dropdown-item t_filter">  کمترین پرداخت شده</a>
                                            <a href="#" data-param="sort"
                                               data-value="count-p-d" class="dropdown-item t_filter">بیشترین پرداخت شده</a>
                                            <a href="#" data-param="sort"
                                               data-value="amount-a" class="dropdown-item t_filter">بیشترین مبلغ</a>
                                            <a href="#" data-param="sort"
                                               data-value="amount-d" class="dropdown-item t_filter">کمترین مبلغ</a>
                                        </div>
                                    </li>
                                    <li class="list-inline-item dropdown">
                                        <a href="#" class="btn btn-link text-default dropdown-toggle"
                                           data-toggle="dropdown">
                                            <i class="icon-warning mr-2"></i>
                                            وضعیت:
                                        </a>

                                        <div class="dropdown-menu">
                                            <a href="#" data-param="status" data-value="all"
                                               class="dropdown-item t_filter">همه</a>
                                            <a href="#" data-param="status"
                                               data-value="active" class="dropdown-item t_filter">فعال ها</a>
                                            <a href="#" data-param="status"
                                               data-value="inactive" class="dropdown-item t_filter">غیر فعال ها</a>
                                        </div>
                                    </li>

                                    @if($query)
                                        <li class="list-inline-item">
                                            نتایج جستجو:
                                            <span class="badge badge-light">
                                        {{$query}}
                                        </span>
                                            <p class="text-info">
                                                {{number_format($count)}}
                                                مورد یافت شد
                                            </p>
                                        </li>
                                    @endif
                                    @if($status)
                                        <li class="list-inline-item ">
                                            وضعیت:
                                            <span class="badge badge-info">
                                        {{$status}}
                                        </span>
                                        </li>
                                    @endif
                                    @if($sort)
                                        <li class="list-inline-item ">
                                            مرتب بر اساس:
                                            <span class="badge badge-danger">
                                        {{$sort}}
                                        </span>
                                        </li>
                                    @endif
                                    @if($sort or $status or $query)

                                        <li class="list-inline-item text-danger">
                                            <a href="#" id="t_search_reset" class=" btn btn-link text-default text-danger"
                                            >
                                                <i class="icon-reset mr-2 "></i>
                                                حذف فیلتر ها
                                            </a>
                                        </li>
                                    @endif

                                </ul>
                                @permission('charity_export_routine')
                                <ul class="list-inline mb-0 ml-md-auto">
                                    <li class="list-inline-item">
                                        <a href="{{route('charity_period_list')."?excel=download"}}"
                                           class="btn btn-link text-default"><i
                                                    class="icon-file-excel mr-2"></i> دریافت گزارش کامل</a>
                                    </li>
                                </ul>
                                @endpermission
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /search field -->
                <div class="card">

                    <div class="card-body table-responsive tableFixHead">
                        @include('panel.charity.period.table')

                    </div>
                    <div class="card-footer">
                    {{$users->appends(request()->except('page'))->links()}}
                    </div>
                </div>
            </section>
        </div>
    </section>
@stop

@section('footer_js')
    <script>
        $(document).on('click', '.t_filter', function () {
            var url = new URL(window.location.href);

            var search_params = url.searchParams;
            let param = $(this).attr('data-param');
            let value = $(this).attr('data-value');

            search_params.set(param, value);
            url.search = search_params.toString();
            var new_url = url.toString();
            window.location.replace(new_url);

        });
        $(document).on('change', '.date_filter', function () {
            var url = new URL(window.location.href);

            var search_params = url.searchParams;
            let param = $(this).attr('data-param');
            let value = $(this).val();

            search_params.set(param, value);
            url.search = search_params.toString();
            var new_url = url.toString();
            window.location.replace(new_url);

        });
        $(document).on('click', '#t_search', function () {
            var url = new URL(window.location.href);

            var search_params = url.searchParams;
            let param = 'q';
            let value = document.getElementById('t_search_q').value;
            search_params.set(param, value);
            url.search = search_params.toString();
            var new_url = url.toString();
            window.location.replace(new_url);
        });
        $(document).on('click', '#t_search_reset', function () {
            var url = new URL(window.location.href);
            url.search = '';
            var new_url = url.toString();
            window.location.replace(new_url);
        });

    </script>
@stop
