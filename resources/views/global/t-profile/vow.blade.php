@extends('global.t-profile.frame')
<?php $active_sidebar = ['vow'];?>


@section('mrn-content')

    @if($routine)
        <div class="mrn-notifications-box">
            <h2 class="notifications"> کمک ماهانه یا هفتگی شما فعال است </h2>

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
                        ریال
                        <br>
                        <span class="notif-date">
                        بابت:
                        </span>
                        <span>{{$routine['title']['title']}}</span>
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
            <h2 class="notifications">شما کمک ماهانه یا هفتگی فعال ندارید</h2>
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
        @include('global.t-profile.activate_routine')
    </div>

    @if($routine)
        <div class="mrn-notifications-box-danger">
            <h4 class="notifications">غیر فعال کردن کمک ماهانه یا هفتگی</h4>

            <ul class="list-unstyled">
                <li class="announce-read">
                    <div class="notifications-content">
                        <div class="row">
                            <p>
                                شما میتوانید کمک ماهانه یا هفتگی خود را غیر فعال کنید و هر زمان که تمایل داشتید مجددا آن
                                را فعال کنید.
                            </p>
                            <button class="button mrn-button-danger pull-left btn-delete mrn-button-sm" type="button">
                                غیر فعال کردن
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
                    title: 'غیر فعال کردن کمک ماهانه یا هفتگی',
                    text: "آیا از غیر فعال کردن کمک ماهانه یا هفتگی اطمینان دارید؟",
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
            $(document).on('submit', '.routine-form', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'تغییر وضعیت کمک ماهانه یا هفتگی',
                    text: "آیا از ثبت اطلاعات اطمینان دارید؟",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#00ccff',
                    cancelButtonColor: '#ff2e3c',
                    confirmButtonText: 'بله ذخیره شود',
                    cancelButtonText: 'خیر'
                }).then((result) => {
                    if (result.value) {
                        // document.getElementById("routine-form").submit();
                        this.submit();
                    }
                })
            })


            var vow_types = document.getElementsByClassName('radio-type');

            // for (var i = 0, len = vow_types.length; i < len; i++) {
            //     vow_types[i].onclick = function () {
            //         let week_day = $(this).data('day');
            //         let notice_id = $(this).data('notice');
            //         $('.vow-notice').hide();
            //         $('#' + notice_id).show();
            //         if (week_day < 7) {
            //             $('#day-of-month').hide();
            //         } else {
            //             $('#day-of-month').show();
            //         }
            //     }
            // }
            ;


        });
    </script>
@stop