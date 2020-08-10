@extends('layouts.panel.panel_layout')
@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('js')
    <script src="{{ URL::asset('/public/assets/panel/global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script
            src="{{ URL::asset('/public/assets/panel/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js') }}"></script>
@endsection
@section('css')

@stop
@php
    $active_sidbare = ['charity', 'charity_period','charity_period_list']
@endphp
@section('content')
    <section>
        <div class="content">
            <section>
                <!-- Search field -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">جستجو پیشرفته</h5>

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
                                    <button type="button" id="t_search" class="btn btn-primary btn-lg">جستجو</button>
                                </div>
                            </div>

                            <div class="d-md-flex align-items-md-center flex-md-wrap text-center text-md-left">
                                <ul class="list-inline list-inline-condensed mb-0">
                                    <li class="list-inline-item ">
                                        <a href="#" id="t_search_reset" class=" btn btn-link text-default "
                                           >
                                            <i class="icon-reset mr-2"></i>
                                            حذف فیلتر ها
                                        </a>
                                    </li>
                                    <li class="list-inline-item dropdown">
                                        <a href="#" class="btn btn-link text-default dropdown-toggle"
                                           data-toggle="dropdown">
                                            <i class="icon-stack2 mr-2"></i>
                                            مرتب سازی:
                                        </a>

                                        <div class="dropdown-menu">
                                            <a href="#" data-param="sort"
                                               data-value="date-a" class="dropdown-item t_filter">نزدیکترین تاریخ</a>
                                            <a href="#" data-param="sort"
                                               data-value="date-d" class="dropdown-item t_filter">دور ترین تاریخ</a>
                                            <a href="#" data-param="sort"
                                               data-value="count-a" class="dropdown-item t_filter">کمترین پرداخت
                                                نشده</a>
                                            <a href="#" data-param="sort"
                                               data-value="count-d" class="dropdown-item t_filter">بیشترین پرداخت
                                                نشده</a>
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
                                            <a href="#" data-param="status" data-value=""
                                               class="dropdown-item t_filter">همه</a>
                                            <a href="#" data-param="status"
                                               data-value="active" class="dropdown-item t_filter">فعال ها</a>
                                            <a href="#" data-param="status"
                                               data-value="inactive" class="dropdown-item t_filter">غیر فعال ها</a>
                                        </div>
                                    </li>

                                </ul>

                                <ul class="list-inline mb-0 ml-md-auto">
                                    <li class="list-inline-item">
                                        <a href="{{route('charity_period_list')."?excel=download"}}" class="btn btn-link text-default"><i
                                                    class="icon-file-excel mr-2"></i> دریافت گزارش کامل</a>
                                    </li>
                                </ul>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /search field -->
                <div class="card">

                    <div class="card-body table-responsive">
                        @include('panel.charity.period.table')
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
