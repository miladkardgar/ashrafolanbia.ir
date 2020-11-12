@php
    $active_sidbare = ['charity','charity_reports','collapse']
@endphp
@extends('layouts.panel.panel_layout')
@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('js')
    <script
            src="{{ URL::asset('/node_modules/md.bootstrappersiandatetimepicker/src/jquery.md.bootstrap.datetimepicker.js') }}"></script>
    <script src="{{ URL::asset('/public/assets/panel/global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ URL::asset('/public/assets/panel/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script>
        $(document).ready(function () {
            $("body").addClass('sidebar-xs')
            $('#start_date').MdPersianDateTimePicker({
                targetTextSelector: '#start_date',
                fromDate: true,
                groupId: 'dateRangeSelector1',
                enableTimePicker: false,
            });
            $('#end_date').MdPersianDateTimePicker({
                targetTextSelector: '#end_date',
                toDate: true,
                groupId: 'dateRangeSelector1',
                enableTimePicker: false,

            });
            $('.multiselect-nonselected-text').multiselect({
                nonSelectedText: 'بابت پرداخت را انتخاب کنید'
            });
            $('.form-check-input-styled').uniform();
        });
    </script>
@endsection
@section('css')
    <link
            href="{{ URL::asset('/node_modules/md.bootstrappersiandatetimepicker/src/jquery.md.bootstrap.datetimepicker.style.css') }}"
            rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{URL::asset('/public/assets/panel/css/iranBanks/ibl.css')}}">
    <style>
        .ibl64{
            border-radius: 50%;
        }
    </style>
@stop
@section('content')

    <section>
        <div class="content">
            <div class="row">
                <div class="col-md-9">
                    <div class="row text-center">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">

                                    <form action="" method="get">
                                        <div class="row ">
                                            <div class="col-md-9 mb-2">
                                                <!-- Custom empty text -->
                                                <div class="input-group">
                                                    <select name="titles[]" class="form-control multiselect-nonselected-text" multiple="multiple" data-fouc>
                                                        @foreach($charity_titles as $charity_title)
                                                            <option {{in_array($charity_title["id"],$selected_titles)?"selected":""}} value="{{$charity_title['id']}}">{{$charity_title['title']}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                                <!-- /custom empty -->

                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group m-1">
                                                    <div class="custom-control custom-checkbox">
                                                        <input name="with_fails" type="checkbox" class="custom-control-input" id="custom_checkbox_stacked_unchecked"
                                                                {{$with_fails ? "checked":""}}>
                                                        <label class="custom-control-label" for="custom_checkbox_stacked_unchecked">نمایش تراکنش های ناموفق</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control" readonly="readonly"
                                                       id="start_date"
                                                       name="start_date"
                                                       required="required"
                                                       value="{{miladi_to_shamsi_date($start_date)}}">

                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control" readonly="readonly"
                                                       id="end_date"
                                                       name="end_date"
                                                       required="required"
                                                       value="{{miladi_to_shamsi_date($end_date)}}">

                                            </div>
                                            <div class="col-md-3">
                                                <button type="submit" class="form-control btn bg-teal" >
                                                    مشاهده
                                                </button>

                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row text-center">

                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-body bg-teal-400 has-bg-image">
                                <div class="media">
                                    <div class="media-body text-left">
                                        <h3 class="mb-0">{{number_format(array_sum($sum_data))}}  </h3>
                                        <span class="text-uppercase font-size-xs">مجموع (ریال)</span>
                                    </div>
                                    <div class="mr-3 align-self-center">
                                        <i class="icon-database icon-3x opacity-75"></i>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-body bg-pink-400 has-bg-image">
                                <div class="media">
                                    <div class="media-body text-left">
                                        <h3 class="mb-0">{{number_format($sum_data['routine_vow'])}}  </h3>
                                        <span class="text-uppercase font-size-xs">کمک ماهانه</span>
                                    </div>
                                    <div class="mr-3 align-self-center">
                                        <i class="icon-heart6 icon-3x opacity-75"></i>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-body bg-blue-400 has-bg-image">
                                <div class="media">
                                    <div class="media-body text-left">
                                        <h3 class="mb-0">{{number_format($sum_data['system_vow'])}}  </h3>
                                        <span class="text-uppercase font-size-xs">کمک آنی</span>
                                    </div>
                                    <div class="mr-3 align-self-center">
                                        <i class="icon-quill2 icon-3x opacity-75"></i>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-body bg-orange-400 has-bg-image">
                                <div class="media">
                                    <div class="media-body text-left">
                                        <h3 class="mb-0">{{number_format($sum_data['other_vow'])}}  </h3>
                                        <span class="text-uppercase font-size-xs">سایر عناوین پرداخت</span>
                                    </div>
                                    <div class="mr-3 align-self-center">
                                        <i class="icon-bubble-dots3 icon-3x opacity-75"></i>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col-md-12">
                            <div class="card">
                                <table class="table datatable-basic">
                                    <thead>
                                    <tr>
                                        <th>{{__('messages.id')}}</th>
                                        <th>{{__('messages.gateway')}}</th>
                                        <th>{{__('messages.status')}}</th>
                                        <th>{{__('messages.date')}}</th>
                                        <th>{{__('messages.amount')}}</th>
                                        <th>{{__('بابت')}}</th>
                                        <th>{{__('سرفصل پرداخت')}}</th>
                                        <th>{{__('messages.description')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($other_vows as $row)
                                        @if(isset($row['id']))
                                            <tr>
                                                <td>{{$row['id']}}</td>
                                                <td>{{$row['gateway']}}</td>
                                                <td class="{{$row['status'] != "موفق"?"text-danger":""}}">{{$row['status']}}</td>
                                                <td><span dir="ltr">{{$row['payDate']}}</span></td>
                                                <td>{{number_format($row['amount'])}}</td>
                                                <td>{{$row['title']['title']}}</td>
                                                <td>{{$row['patern']['title']}}</td>
                                                <td>{{$row['description']}}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                                {{$other_vows->appends(request()->except('page'))->links()}}

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-white">
                        <div class="card-header">
                            <h3>ورودی هر درگاه</h3>

                        </div>
                        <div class="card-body">

                            <ul class="media-list">
                                @foreach($bank_balances as $bank_balance)
                                <li class="media">
                                    <div class="mr-3">
                                        <a href="#"
                                           class="btn bg-transparent border-pink-400 text-pink rounded-round border-2 btn-icon">
                                            {!!$bank_balance->logo!!}
                                        </a>
                                    </div>

                                    <div class="media-body">
                                        <span class="font-weight-semibold">{{number_format($bank_balance->balance)}}</span> ریال
                                        <div class="text-muted">{{$bank_balance->title}}</div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header header-elements-inline">
                            <h6 class="card-title">پرداختی بابت:</h6>
                            <div class="header-elements">
                                <span class="font-weight-bold text-danger-600 ml-2">{{number_format($sum_data['other_vow'])}}</span>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table text-nowrap">
                                <thead>
                                <tr >
                                    <th  class="w-100 pl-1 pr-0">عنوان</th>
                                    <th class="pr-1 pl-0">مبلغ دریافتی (ریال)</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($charity_titles as $charity_title)
                                <tr >
                                    <td class="pl-1 pr-0">
                                        <div class="d-flex align-items-center">
                                            <div class="mr-3">
                                                <a href="#" class="btn bg-primary-400 rounded-round btn-icon btn-sm">
                                                    <span class="letter-icon">{{substr(trim($charity_title['title']),0,2)}}</span>
                                                </a>
                                            </div>
                                            <div>
                                                <a href="#" class="text-default font-weight-semibold letter-icon-title">{{$charity_title['title']}}</a>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="pr-1 pl-0">
                                        <h6 class="font-weight-semibold mb-0">{{number_format($charity_title['balance'])}}</h6>
                                    </td>
                                </tr>
                                @endforeach


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection