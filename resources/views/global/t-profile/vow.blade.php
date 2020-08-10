@extends('global.t-profile.frame')
<?php $active_sidebar = ['vow'];?>


@section('mrn-content')

    @if($routine)
        <div class="mrn-notifications-box">
            <h2 class="notifications"> تعهد فعال است </h2>

            <ul class="list-unstyled">
                <li class="announce-read">
                    <div class="notifications-content">
                    <span class="notif-date">
                        تاریخ شروع:
                        <span>{{miladi_to_shamsi_date($routine['start_date'])}}</span>
                    </span>
                        <br>
                        <span class="notif-date">
                        دوره:
                        <span>{{__("words.monthly_".$routine['period'])}}</span>
                    </span>
                        <br>
                        <span class="notif-date">
                        مبلغ:
                        </span>
                        <span>{{number_format($routine['amount'])}}</span>
                            <br>
                        <span class="text-theme-colored">

                        <a href="#routine-form-area" class="text-info">ویرایش <i class="fa fa-pencil"></i> </a>
                    </span>
                        <h3>نوبت بعدی پرداخت:
                            <span class="text-black-50">{{jdate('l',strtotime($routine['next_date']))}}  {{miladi_to_shamsi_date($routine['next_date'])}} </span>
                        </h3>
                    </div>
                </li>
            </ul>
        </div>
    @else

        <div class="mrn-notifications-box">
            <h2 class="notifications">شما تعهد فعال ندارید</h2>
            <ul class="list-unstyled">
                <li class="announce-read">
                    <div class="notifications-content">
                        <p>
                            {!! $pattern->description !!}
                        </p>
                    </div>
                </li>
            </ul>
        </div>
    @endif
    <div class="mrn-notifications-box" id="routine-form-area">
        <h2 class="notifications">{{$routine ? "ویرایش تعهد":"ایجاد تعهد"}}</h2>
        <h4>نوع تعهد را انتخاب کنید:</h4>

        <form method="post" id="routine-form" action="{{route('add_charity_period')}}"
              class="clearfix">
        <div class="">
            <div class="mrn-status-user-widget mt-2">
                <ul class="radio-tabs">
                    @foreach($routine_types as $key => $routine_type)
                        <li class="all_bills ">
                        <input type="radio"
                               {{($routine and $routine['period']==$key) ?"checked":""}}
                               id="radio-{{$key}}"
                               data-notice="notice-{{$key}}"
                               data-day="{{$routine_type['week_day']}}"
                               class="radio-type"
                               value="{{$key}}"
                               name="type" />
                        <label for="radio-{{$key}}">
                            <h4 style="margin-top: 1.5em">
                            @if($routine and $routine['period'] == $key)
                            <i class="fa fa-star text-theme-colored"></i>
                            @endif
                            {{$routine_type['title']}}
                            </h4>
                        </label>
                        </li>



                    @endforeach
                </ul>
            </div>
        </div>
        <div class="">
            @foreach($routine_types as $key => $routine_type)
                    <div class="mrn-notifications-box-green vow-notice" style=" {{($routine and $routine['period']==$key) ?"display: block":"display:none"}}" id="notice-{{$key}}">
                        <h4 class="notifications"><i class="fa fa-bell-o"></i>  {{$routine_type['title']}} </h4>

                        <ul class="list-unstyled">
                            <li class="announce-read">
                                <div class="notifications-content">
                                    <p>
                                        {{$routine_type['description']}}
                                    </p>

                                </div>
                            </li>
                        </ul>
                    </div>
            @endforeach

                @csrf
                <div class="row" >
                    <div class="form-group col-md-6 ">
                        <label for="amount" class="">مبلغ:</label>
                        <input id="amount" name="amount" class="form-control amount left"
                               value="{{$routine ? number_format($routine['amount']):number_format($pattern['min'])}}"
                               type="text" required="required" placeholder="مبلغ">
                    </div>
                </div>


                <div class="row" id="day-of-month" style=" {{($routine and ($routine_types[$routine['period']]['week_day']<7)) ?"display: none":"display: block"}}">
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

                        <button class="button mrn-button" name="submit-btn" type="submit"
                        >ثبت و ذخیره
                        </button>


                    </div>
                </div>

        </div>
        </form>

    </div>

    @if($routine)
        <div class="mrn-notifications-box-danger">
            <h4 class="notifications">غیر فعال کردن تعهد</h4>

            <ul class="list-unstyled">
                <li class="announce-read">
                    <div class="notifications-content">
                        <div class="row">
                            <p class="">
                                شما میتوانید تعهد خود را غیر فعال کنید و هر زمان که تمایل داشتید مجددا آن را فعال کنید.
                            </p>
                            <button class="button mrn-button-danger pull-left btn-delete mrn-button-sm" type="button"
                            >غیر فعال کردن تعهد
                            </button>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    @endif

@endsection
@section('js2')
    <script src="{{ URL::asset('/node_modules/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $(document).on('click', '.btn-delete', function () {
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
                            url: "{{route('global_profile_delete_period')}}",
                            type: "post",

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



            var vow_types = document.getElementsByClassName('radio-type');

            for (var i=0, len=vow_types.length; i<len; i++) {
                vow_types[i].onclick = function() {
                    let week_day = $(this).data('day');
                    let notice_id = $(this).data('notice');
                    $('.vow-notice').hide();
                    $('#'+notice_id).show();
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
@stop