@extends('layouts.panel.panel_layout')
@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('js')
    <script
        src="{{ URL::asset('/public/assets/panel/global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script
        src="{{ URL::asset('/public/assets/panel/global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script
        src="{{ URL::asset('/public/assets/panel/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js') }}"></script>
    <script>
        var DatatableBasic = function () {
            var _componentDatatableBasic = function () {
                if (!$().DataTable) {
                    console.warn('Warning - datatables.min.js is not loaded.');
                    return;
                }
                $('.datatable-payments2').DataTable({
                    autoWidth: false,
                    columnDefs: [{
                        orderable: false,
                        width: 100,
                        targets: [8]
                    }],
                    "order": [[ 0, "desc" ]],
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'csv',
                            charset: 'utf-8',
                            extension: '.xls',
                            bom: true,
                        }
                    ],                    language: {
                        search: '<span>{{__('messages.filter')}}:</span> _INPUT_',
                        searchPlaceholder: '{{__('messages.search')}}...',
                        lengthMenu: '<span>{{__('messages.show')}}:</span> _MENU_',
                        paginate: {
                            'first': '{{__('messages.first')}}',
                            'last': '{{__('messages.last')}}',
                            'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;',
                            'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;'
                        }
                    }
                });
                $('.sidebar-control').on('click', function () {
                    table.columns.adjust().draw();
                });
            }
            var _componentSelect2 = function () {
                if (!$().select2) {
                    console.warn('Warning - select2.min.js is not loaded.');
                    return;
                }
                $('.dataTables_length select').select2({
                    minimumResultsForSearch: Infinity,
                    dropdownAutoWidth: true,
                    width: 'auto'
                });
            };
            return {
                init: function () {
                    _componentDatatableBasic();
                    _componentSelect2();
                }
            }
        }();
        document.addEventListener('DOMContentLoaded', function () {
            DatatableBasic.init();
        });

    </script>
@endsection
@section('css')
@stop
@php
    $active_sidbare = ['charity', 'charity_list']
@endphp
@section('content')
    <section>
        <div class="content">
            <section>
                <div class="row text-center">
                    <div class="col-sm-6 col-xl-3">
                        <div class="card card-body bg-success-400 has-bg-image">
                            <div class="media">
                                <div class="media-body text-left">
                                    <h3 class="mb-0">{{number_format($last_30)}}  </h3>
                                    <span class="text-uppercase font-size-xs">تعداد سی روز اخیر</span>
                                </div>
                                <div class="mr-3 align-self-center">
                                    <i class="icon-users2 icon-3x opacity-75"></i>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">

                        <div class="card card-body bg-indigo-400 has-bg-image">
                            <div class="media">
                                <div class="media-body text-left">
                                    <h3 class="mb-0">{{number_format($avg_30)}}  </h3>
                                    <span class="text-uppercase font-size-xs">میانگین ماهانه سال</span>
                                </div>
                                <div class="mr-3 align-self-center">
                                    <i class="icon-users4 icon-3x opacity-75"></i>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">

                        <div class="card card-body bg-info-400 has-bg-image">
                            <div class="media">
                                <div class="media-body text-left">
                                    <h3 class="mb-0">{{number_format($price_30)}}  </h3>
                                    <span class="text-uppercase font-size-xs">مبلغ یک ماه اخیر (ریال)</span>
                                </div>
                                <div class="mr-3 align-self-center">
                                    <i class="icon-wallet icon-3x opacity-75"></i>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">

                        <div class="card card-body bg-danger-400 has-bg-image">
                            <div class="media">
                                <div class="media-body text-left">
                                    <h3 class="mb-0">{{number_format($faild_30)}}  </h3>
                                    <span class="text-uppercase font-size-xs">تعداد ناموفق یک ماه</span>
                                </div>
                                <div class="mr-3 align-self-center">
                                    <i class="icon-cancel-circle2 icon-3x opacity-75"></i>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search field -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">جستجو پیشرفته</h5>

                        <form action="#">
                            <div class="input-group mb-3">
                                <div class="form-group-feedback form-group-feedback-left">
                                    <input type="text" id="t_search_q" class="form-control form-control-lg alpha-grey"
                                           placeholder="نام یا شماره">
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
                                               data-value="success" class="dropdown-item t_filter">موفق</a>
                                            <a href="#" data-param="status"
                                               data-value="pending" class="dropdown-item t_filter">نامشخص</a>
                                            <a href="#" data-param="status"
                                               data-value="fail" class="dropdown-item t_filter">ناموفق</a>
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
                                            وضعیت پرداخت:
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
                                        <a href="{{route('charity_payment_list')."?excel=download"}}" class="btn btn-link text-default"><i
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
                    <div class="card-header">
                        <h6 class="card-title text-black">{{__('messages.Charity')}}
                            | {{__('messages.other_payments')}}</h6>
                        <hr>
                    </div>

                    <div class="card-body table-responsive">
                        @include('panel.charity.other_payment.table')
                        {{$otherPayments->appends(request()->except('page'))->links()}}

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
