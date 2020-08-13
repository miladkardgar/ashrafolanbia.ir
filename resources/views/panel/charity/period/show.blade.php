@extends('layouts.panel.panel_layout')
@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('js')
    <script src="{{ URL::asset('/public/assets/panel/global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/public/assets/panel/global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            var DatatableBasic = function () {
                var _componentDatatableBasic = function () {
                    if (!$().DataTable) {
                        console.warn('Warning - datatables.min.js is not loaded.');
                        return;
                    }
                    $.extend($.fn.dataTable.defaults, {
                        autoWidth: false,
                        columnDefs: [{
                            orderable: false,
                            width: 100,
                            targets: [5]
                        }],
                        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
                        language: {
                            search: '<span>Filter:</span> _INPUT_',
                            searchPlaceholder: 'Type to filter...',
                            lengthMenu: '<span>Show:</span> _MENU_',
                            paginate: {
                                'first': 'First',
                                'last': 'Last',
                                'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;',
                                'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;'
                            }
                        }
                    });
                    $('.datatable-basic').DataTable({
                        rowCallback: function (row, data, index) {
                            if (data[4] === 'active') {
                                $(row).find('td:eq(4)').addClass('text-center bg-success')
                            } else if (data[4] === 'inactive') {
                                $(row).find('td:eq(4)').addClass('text-center bg-danger')
                            }
                        }
                    });
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
            }
        })

    </script>
    <script>
        function selectAllPayment(source) {
            let checkboxes = document.getElementsByClassName('payment');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked;
            }
        }

        $(document).on('submit', '#remove_transaction_form', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'حذف معوقه',
                text: "آیا از حذف مورد/موارد انتخاب شده اطمینان دارید؟ حذف غیر قابل بازگشت میباشد!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff2e3c',
                cancelButtonColor: '#00ccff',
                confirmButtonText: 'بله حذف شود شود',
                cancelButtonText: 'خیر'
            }).then((result) => {
                if (result.value) {
                    document.getElementById("remove_transaction_form").submit();

                }
            })
        })
        $(document).on('submit', '#routine-form', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'تغییر وضعیت تعهد',
                text: "آیا از ثبت اطلاعات اطمینان دارید؟",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#00ccff',
                cancelButtonColor: '#ff2e3c',
                confirmButtonText: 'بله ذخیره شود',
                cancelButtonText: 'خیر'
            }).then((result) => {
                if (result.value) {
                    document.getElementById("routine-form").submit();

                }
            })
        })
        $(document).on('click', '#remove_routine', function () {
            Swal.fire({
                title: 'غیر فعال کردن تعهد',
                text: "آیا از غیر فعال کردن تعهد اطمینان دارید؟",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff2e3c',
                cancelButtonColor: '#00ccff',
                confirmButtonText: 'بله غیرفعال شود',
                cancelButtonText: 'خیر'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: "{{route('users_routine_delete')}}",
                        type: "post",
                        data: {
                            user_id : {{$userInfo['id']}}
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('input[name="_token"]').val()
                        },
                        success: function (response) {
                            $.each(response, function (index, value) {
                                PNotify.success({
                                    text: value.message,
                                    delay: 3000,
                                });
                            })
                            setTimeout(function () {
                                location.reload();
                            }, 3000);
                        }, error: function (response) {
                            var errors = response.responseJSON.errors;
                            $.each(errors, function (index, value) {
                                PNotify.error({
                                    delay: 3000,
                                    title: index,
                                    text: value,
                                });
                            });
                        }
                    });
                }
            })
        })


        $(document).ready(function () {
            var vow_types = document.getElementsByClassName('radio-type');

            for (var i=0, len=vow_types.length; i<len; i++) {

                vow_types[i].onclick = function() {
                    let week_day = $(this).data('day');
                    if (week_day < 7){
                        $('#day-of-month').hide();
                    }
                    else {
                        $('#day-of-month').show();
                    }
                }
            };
        });



    </script>
@endsection
@section('css')
@stop
@php
    $avatar = $userInfo->profile_image->last();
    $active_sidbare = ['charity', 'charity_period','charity_period_list','collapse'];
    $sumPay=0;
    $unPaid=0;
@endphp
@section('content')
    <section>
        <!-- Content area -->
        <div class="content">

            <!-- Inner container -->
            <div class="d-md-flex align-items-md-start">

                <!-- Left sidebar component -->
                <div class=" sidebar-light bg-transparent sidebar-component sidebar-component-left wmin-300 border-0 shadow-0 sidebar-expand-md">

                    <!-- Sidebar content -->
                    <div class="sidebar-content">

                        <!-- Navigation -->
                        <div class="card">
                            <div class="card-body bg-indigo-400 text-center card-img-top"
                                 style="background-image: url(http://demo.interface.club/limitless/assets/images/bg.png); background-size: contain;">
                                <div class="card-img-actions d-inline-block mb-3">
                                    <img class="img-fluid rounded-circle"
                                         src="{{$avatar ? "/".$avatar->url:"/public/assets/global/images/unknown-avatar.png"}}"
                                         width="170" height="170" alt="">

                                </div>

                                <h6 class="font-weight-semibold mb-0">{{get_name($userInfo['id'])}}</h6>
                                 <span class=" d-block opac{-y-75">

                                    <a class="text-white-50" href="{{route('users_list_info_edit',[$userInfo['id']])}}" >
                                        ویرایش حساب کاربری
                                    <i class="icon-pencil5 text-white-50"></i>
                                    </a>

                                    </span>


                            </div>

                            <div class="card-body p-0">
                                <ul class="nav nav-sidebar mb-2">
                                    <li class="nav-item">
                                        <a href="#profile" class="nav-link active" data-toggle="tab">
                                            <i class="icon-user"></i>
                                            مشخصات
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#routine" class="nav-link" data-toggle="tab">
                                            <i class="icon-heart5 animated animated-hover"></i>
                                            ویرایش تعهد
                                            <span class="font-size-sm font-weight-normal opacity-75 ml-auto">{{$routine? "فعال":"غیرفعال"}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#waiting" class="nav-link" data-toggle="tab">
                                            <i class="icon-hour-glass"></i>
                                            در انتظار پرداخت
                                            <span class="badge bg-danger badge-pill ml-auto">{{$unpaidRoutineCount}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#history" class="nav-link" data-toggle="tab">
                                            <i class="icon-graph"></i>
                                            سوابق پرداخت تعهد
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#history-other" class="nav-link" data-toggle="tab">
                                            <i class="icon-chart"></i>
                                            سوابق سایر پرداخت ها
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#loh" class="nav-link" data-toggle="tab">
                                            <i class="icon-medal"></i>
                                            لوح تقدیر
                                        </a>
                                    </li>


                                </ul>
                            </div>
                        </div>
                        <!-- /navigation -->


                    </div>
                    <!-- /sidebar content -->

                </div>
                <!-- /left sidebar component -->


                <!-- Right content -->
                <div class="tab-content w-100 ">
                    <div class="card">
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-sm-6 col-xl-3">
                                    <div class="card card-body bg-success-400 has-bg-image">
                                        <div class="media">
                                            <div class="media-body text-left">
                                                <h3 class="mb-0">{{number_format($paidRoutineAmount)}}  </h3>
                                                <span class="text-uppercase font-size-xs">{{__('messages.paid')}} (ریال)</span>
                                            </div>
                                            <div class="mr-3 align-self-center">
                                                <i class="icon-cash4 icon-3x opacity-75"></i>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xl-3">
                                    <div class="card card-body bg-indigo-400 has-bg-image">
                                        <div class="media">
                                            <div class="media-body text-left">
                                                <h3 class="mb-0">{{number_format($paidRoutineCount)}}</h3>
                                                <span class="text-uppercase font-size-xs">{{__('تعداد پرداختی')}}</span>
                                            </div>
                                            <div class="mr-3 align-self-center">
                                                <i class="icon-check icon-3x opacity-75"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xl-3">
                                    <div class="card card-body bg-teal-400 has-bg-image">
                                        <div class="media">
                                            <div class="media-body text-left">
                                                <h3 class="mb-0">{{number_format($unpaidRoutineCount)}}</h3>
                                                <span class="text-uppercase font-size-xs">{{__("در انتظار پرداخت")}}</span>
                                            </div>
                                            <div class="ml-3 align-self-center">
                                                <i class="icon-hour-glass icon-3x opacity-75"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xl-3">
                                    <div class="card card-body {{$userInfo['loh']? 'bg-info-300' : 'bg-warning-600' }}  has-bg-image">
                                        <div class="media">
                                            <div class="media-body text-left">
                                                <span class="text-uppercase font-size-xs">{{$userInfo['loh']? __('messages.awardReceived'):__('messages.awardNotReceived')}}</span>
                                            </div>
                                            <div class="ml-3 align-self-center">
                                                <i class="icon-medal2 icon-3x opacity-75"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade active show" id="profile">

                        <!-- Sales stats -->
                        <div class="card">

                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="d-sm-flex flex-sm-wrap mb-3">
                                            <div class="font-weight-semibold">{{__('messages.name')}}:</div>
                                            <div class="pl-2 mt-2 mt-sm-0 text-left"><a
                                                        href="#">{{$userInfo->people ? $userInfo->people['name']:""}}</a>
                                            </div>
                                        </div>
                                        <div class="d-sm-flex flex-sm-wrap mb-3">
                                            <div class="font-weight-semibold">{{__('messages.family')}}:</div>
                                            <div class="pl-2 mt-2 mt-sm-0 text-left"><a
                                                        href="#">{{$userInfo->people ? $userInfo->people['family']:""}}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-sm-flex flex-sm-wrap mb-3">
                                            <div class="font-weight-semibold">{{__('messages.phone')}}:</div>
                                            <div class="pl-2 mt-2 mt-sm-0 text-left"><a
                                                        href="#">{{$userInfo->phone}}</a>
                                                @if($userInfo->phone_verified_at)
                                                    <span class="icon-checkmark-circle text-success"></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="d-sm-flex flex-sm-wrap mb-3">
                                            <div class="font-weight-semibold">{{__('messages.email')}}:</div>
                                            <div class="pl-2 mt-2 mt-sm-0 text-left"><a
                                                        href="#">{{$userInfo->email}}</a>
                                                @if($userInfo->email_verified_at)
                                                    <span class="icon-checkmark-circle text-success"></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-sm-flex flex-sm-wrap mb-3">
                                            <div class="font-weight-semibold">{{__('تاریخ عضویت')}}:</div>
                                            <div class="pl-2 mt-2 mt-sm-0 text-left"><a
                                                        href="#">{{miladi_to_shamsi_date($userInfo->created_at)}}</a>
                                            </div>
                                        </div>
                                        <div class="d-sm-flex flex-sm-wrap mb-3">
                                            <div class="font-weight-semibold">{{__('تاریخ آخرین پرداخت')}}:</div>
                                            <div class="pl-2 mt-2 mt-sm-0 text-left"><a
                                                        href="#">{{$last_paid ? miladi_to_shamsi_date($last_paid->created_at):""}}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card">

                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="d-sm-flex flex-sm-wrap mb-3">
                                            <div class="font-weight-semibold">{{__('وضعیت تعهد')}}:</div>
                                            <div class="pl-2 mt-2 mt-sm-0 font-weight-bold text-left"><a
                                                        href="#">{{$routine? "فعال":"غیرفعال"}}</a>
                                            </div>
                                        </div>
                                        @if($routine)
                                        <div class="d-sm-flex flex-sm-wrap mb-3">
                                            <div class="font-weight-semibold">{{__('نوع تعهد')}}:</div>
                                            <div class="pl-2 mt-2 mt-sm-0 text-left"><a
                                                        href="#">{{__("words.monthly_".$routine['period'])}}</a>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    @if($routine)

                                    <div class="col-md-4">
                                        <div class="d-sm-flex flex-sm-wrap mb-3">
                                            <div class="font-weight-semibold">{{__('تاریخ شروع')}}:</div>
                                            <div class="pl-2 mt-2 mt-sm-0 text-left"><a
                                                        href="#">{{miladi_to_shamsi_date($routine['start_date'])}}</a>
                                            </div>
                                        </div>
                                        <div class="d-sm-flex flex-sm-wrap mb-3">
                                            <div class="font-weight-semibold">{{__('نوبت بعدی پرداخت')}}:</div>
                                            <div class="pl-2 mt-2 mt-sm-0 text-left"><a
                                                        href="#">{{jdate('l',strtotime($routine['next_date']))}} {{miladi_to_shamsi_date($routine['next_date']) }}</a>
                                            </div>
                                        </div>
                                    </div>
                                        <div class="col-md-4">
                                            <div class="d-sm-flex flex-sm-wrap mb-3">
                                                <div class="font-weight-semibold">{{__('مبلغ (ریال)')}}:</div>
                                                <div class="pl-2 mt-2 mt-sm-0 text-left"><a
                                                            href="#">{{number_format($routine['amount'])}}</a>
                                                </div>
                                            </div>

                                        </div>

                                    @endif

                                </div>

                            </div>
                        </div>
                        <!-- /sales stats -->


                    </div>

                    <div class="tab-pane fade" id="routine">

                        <!-- Available hours -->
                        <div class="card">

                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="d-sm-flex flex-sm-wrap mb-3">
                                            <div class="font-weight-semibold">{{__('وضعیت تعهد')}}:</div>
                                            <div class="pl-2 mt-2 mt-sm-0 font-weight-bold text-left"><a
                                                        href="#">{{$routine? "فعال":"غیرفعال"}}</a>
                                            </div>
                                        </div>
                                        @if($routine)
                                            <div class="d-sm-flex flex-sm-wrap mb-3">
                                                <div class="font-weight-semibold">{{__('نوع تعهد')}}:</div>
                                                <div class="pl-2 mt-2 mt-sm-0 text-left"><a
                                                            href="#">{{__("words.monthly_".$routine['period'])}}</a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    @if($routine)

                                        <div class="col-md-4">
                                            <div class="d-sm-flex flex-sm-wrap mb-3">
                                                <div class="font-weight-semibold">{{__('تاریخ شروع')}}:</div>
                                                <div class="pl-2 mt-2 mt-sm-0 text-left"><a
                                                            href="#">{{miladi_to_shamsi_date($routine['start_date'])}}</a>
                                                </div>
                                            </div>
                                            <div class="d-sm-flex flex-sm-wrap mb-3">
                                                <div class="font-weight-semibold">{{__('نوبت بعدی پرداخت')}}:</div>
                                                <div class="pl-2 mt-2 mt-sm-0 text-left"><a
                                                            href="#">{{jdate('l',strtotime($routine['next_date']))}} {{miladi_to_shamsi_date($routine['next_date']) }}</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-sm-flex flex-sm-wrap mb-3">
                                                <div class="font-weight-semibold">{{__('مبلغ (ریال)')}}:</div>
                                                <div class="pl-2 mt-2 mt-sm-0 text-left"><a
                                                            href="#">{{number_format($routine['amount'])}}</a>
                                                </div>
                                            </div>

                                        </div>

                                    @endif

                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">

                                    <div class="card-body">

                                        <h4>تغییر در وضعیت تعهد کاربر:</h4>

                                        <form method="post" id="routine-form" action="{{route('users_routine_update')}}"
                                              class="clearfix">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{$userInfo['id']}}">
                                            <div class="">
                                                <div class="form-group mb-3 mb-md-2 mt-2">
                                                    @foreach(config('charity.routine_types') as $key => $routine_type)
                                                        <div class="form-check form-check-inline ">
                                                            <label class="form-check-label">
                                                                <input value="{{$key}}" type="radio" data-day="{{$routine_type['week_day']}}" class="radio-type form-check-input" name="type" {{($routine and $routine['period'] == $key) ? "checked":""}}>
                                                                {{$routine_type['title']}}
                                                            </label>
                                                        </div>

                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="">

                                                @csrf
                                                <div class="row" >
                                                    <div class="form-group col-md-6 ">
                                                        <label for="amount" class="">مبلغ:</label>
                                                        <input id="amount" name="amount" class="form-control amount left"
                                                               value="{{$routine ? number_format($routine['amount']):number_format($pattern['min'])}}"
                                                               type="text" required="required" placeholder="مبلغ">
                                                    </div>
                                                </div>


                                                <div class="row" id="day-of-month" style=" {{($routine and (config('charity.routine_types')[$routine['period']]['week_day']<7)) ?"display: none":"display: block"}}">
                                                    @php
                                                        if ($routine){
                                                            $day = latin_num(jdate('d',strtotime($routine['start_date'])));
                                                        }else{
                                                            $day = latin_num(jdate("d",time()));
                                                        }

                                                    @endphp

                                                    <div class="form-group col-md-6">
                                                        <label for="Day" class="">روز:</label>
                                                        <select name="day" id="Day" class="form-control">
                                                            <option value="" disabled class="">روز ماه:</option>
                                                            @for($d=1 ; $d<=29;$d++)
                                                                <option {{$day == $d ? "selected":""}} value="{{$d}}"
                                                                        class="">{{$d}}</option>
                                                            @endfor
                                                        </select>
                                                    </div>

                                                </div>

                                                <div align="" class="row">
                                                    <div class="form-group col-md-12">

                                                        <button class="btn btn-success" name="submit-btn" type="submit"
                                                        >ثبت و ذخیره
                                                        </button>


                                                    </div>
                                                </div>

                                            </div>
                                        </form>

                                    </div>
                                </div>

                            </div>
                            @if($routine)

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="notifications">غیر فعال کردن تعهد</h4>

                                        <ul class="list-unstyled">
                                            <li class="announce-read">
                                                <div class="notifications-content">
                                                    <div class="row">
                                                        <p class="">
                                                            با غیر فعال شدن تعهد پرداخت کاربر، دیگر تعهد جدید ایجاد نمیگردد.
                                                        </p>
                                                        <br>

                                                    </div>
                                                    <button id="remove_routine" class="btn btn-danger" type="button"
                                                    >غیر فعال کردن تعهد
                                                    </button>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                            @endif
                        </div>
                        <!-- /available hours -->


                    </div>

                    <div class="tab-pane fade" id="waiting">

                        <!-- My inbox -->
                        <div class="card">
                            <div class="card-body">

                                <div class="row table-responsive">
                                    <div class="col-md-12">
                                        @include('panel.charity.period.payment_list',['historyItems'=>$unpaidHistory,'unpaid'=>true])
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- /my inbox -->

                    </div>

                    <div class="tab-pane fade" id="history">

                        <!-- Orders history -->
                        <div class="card">

                            <div class="card-body">
                                @include('panel.charity.period.payment_list',['historyItems'=>$paidHistory,'unpaid'=>false])

                            </div>

                        </div>
                        <!-- /orders history -->

                    </div>

                    <div class="tab-pane fade" id="history-other">

                        <!-- Orders history -->
                        <div class="card">

                            <div class="card-body">
                                @include('panel.charity.period.payment_other_list',['historyItems'=>$otherPaidHistory])

                            </div>

                        </div>
                        <!-- /orders history -->

                    </div>

                    <div class="tab-pane fade" id="loh">

                        <!-- Orders history -->
                        <div class="card">

                            <div class="card-body">
                                <h3 class="text-info-800">
                                    {{$userInfo['loh']? __('messages.awardReceived'):__('messages.awardNotReceived')}}
                                </h3>
                                <p class="text-info-800">
                                    @if($userInfo['loh'])
                                    در صورتی که لوح تقدیر به نیکوکار تقدیم شده است بر روی دکمه زیر کلیک نمایید.
                                    @else
                                    برای تغییر وضعیت دریافت لوح نیکوکار بر روی دکمه زیر کلیک کنید.
                                    @endif
                                </p>
                                <hr>
                                <form method="post" action="{{route('charity.periodic.award',$userInfo['id'])}}">
                                    {{csrf_field()}}
                                    <input type="hidden" name="user_id" value="{{$userInfo['id']}}">
                                    <button type="submit"
                                            class="btn  btn-lg bg-warning">
                                        <i class="icon-medal2 text-white"></i>
                                        تغییر وضعیت دریافت لوح
                                    </button>

                                </form>

                            </div>

                        </div>
                        <!-- /orders history -->

                    </div>
                </div>
                <!-- /right content -->

            </div>
            <!-- /inner container -->

        </div>
        <!-- /content area -->



    </section>
@stop