@extends('global.t-profile.frame')
<?php $active_sidebar = ['vow'];
$type = -1;
$active_type = -1;
$same_type = false;
if (isset($routine)){
    $type = $routine['period'];
    $active_type = $routine['period'];
}
if (isset($_GET['type'])){
    $type = $_GET['type'];
    if ($active_type == $type and isset($routine)){
        $same_type = true;
    }
}
?>
@section('mrn-content')
    <div class="mrn-vow-container">
        <div class="mrn-vow-sidebar">
            @if($active_type>-1)
                <h4 class="mrn-vow-sidebar-heading"><a href="##">تعهد فعال است</a></h4>

            @else
                <h4 class="mrn-vow-sidebar-heading"><a href="##">شما تعهد فعال ندارید</a></h4>
                <span class="text-info text-sm-center">نوع تعهدی که میخواهید را انتخاب کنید.</span>
            @endif
            <ul>
                <li class="mrn-card {{$type == 0 ? "mrn-card-active":""}}"><a href="{{route('t_routine_vow',['type'=>0])}}"><p> @if($active_type==0)<i class="fa fa-check-circle text-success"></i>  @endif  تعهد روزانه  </p></a></li>
                <li class="mrn-card {{$type == 1 ? "mrn-card-active":""}}"><a href="{{route('t_routine_vow',['type'=>1])}}"><p> @if($active_type==1)<i class="fa fa-check-circle text-success"></i>  @endif  تعهد ماهیانه </p></a></li>
                <li class="mrn-card {{$type == 2 ? "mrn-card-active":""}}"><a href="{{route('t_routine_vow',['type'=>2])}}"><p> @if($active_type==2)<i class="fa fa-check-circle text-success"></i>  @endif  تعهد سه ماهه </p></a></li>
                <li class="mrn-card {{$type == 3 ? "mrn-card-active":""}}"><a href="{{route('t_routine_vow',['type'=>3])}}"><p> @if($active_type==3)<i class="fa fa-check-circle text-success"></i>  @endif  تعهد شش ماهه </p></a></li>
                <li class="mrn-card {{$type == 4 ? "mrn-card-active":""}}"><a href="{{route('t_routine_vow',['type'=>4])}}"><p> @if($active_type==4)<i class="fa fa-check-circle text-success"></i>  @endif  تعهد سالیانه </p></a></li>
            </ul>

        </div>
        <div class="mrn-vow-content">
            <div class="mrn-vow-content-heading">
                @if($active_type>-1)
                <button class="button mrn-button-danger pull-left btn-delete mrn-button-sm" type="button"
                >غیر فعال کردن تعهد</button>
                @endif
            </div>
            <div style="clear: both"></div>
            <hr>
            @if($active_type == -1)
                <div class="mrn-notifications-box-green">

                    <ul class="list-unstyled">
                        <li class="announce-read">
                            <div class="notifications-content">
                                <p>
                                    در حال حاظر شما تعهد فعالی ندارید، شما میتوانید با ثبت تعهد پرداخت مستمر ما را در برنامه ریزی برای کمک موثرتر به خانواده های نیازمند یاری کنید.
                                </p>

                            </div>
                        </li>
                    </ul>
                </div>

            @endif
            @if($routine)
                <div class="mrn-notifications-box">
                    <h2 class="notifications">تعهد فعلی</h2>

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
                                <h3>نوبت بعدی پرداخت:
                                    <span class="text-black-50">{{miladi_to_shamsi_date($routine['next_date'])}}</span>
                                </h3>
                                </div>
                        </li>
                    </ul>
                </div>


            @endif
            @if($type > -1)
            <div class="mrn-notifications-box">
                <h4 class="notifications"> انتخاب مبلغ (ریال)</h4>
                <br>
                <form id="change_password_form" method="post" action="{{route('add_charity_period')}}" class="clearfix">
                    @csrf
                    <input type="hidden" name="type" value="{{$type}}" >
                    <div class="row">
                        <div class="form-group col-md-6 ">
                            <input id="amount" name="amount" class="form-control amount left"
                                    value="{{$same_type ? number_format($routine['amount']):""}}"
                                   type="text" required="required" placeholder="مبلغ">
                        </div>
                    </div>
                    <h4 class="notifications">تاریخ شروع </h4>

                    <div class="row">
                        <br>
                        <div class="form-group col-md-6">
                            <label for="month" class="">ماه:</label>
                            <select name="month" id="month" class="form-control">
                                <option value="" disabled selected class="">ماه</option>
                                @php
                                if($same_type){
                                    $month = latin_num(jdate("m",strtotime($routine['start_date'])));
                                    $day = latin_num(jdate("d",strtotime($routine['start_date'])));
                                }
                                else{
                                    $month = latin_num(jdate("m",time()));
                                    $day = latin_num(jdate("d",time()));
                                }
                                @endphp
                                @for($m=1 ; $m<=12;$m++)
                                    <option {{$month == $m ? "selected":""}} value="{{$m}}" class="">{{jdate("F",jmktime(1,1,1,$m,1,1390))}}</option>
                                @endfor

                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="Day" class="">روز:</label>
                            <select name="day" id="Day" class="form-control">
                                <option value="" disabled  class="">روز</option>
                                @for($d=1 ; $d<=29;$d++)
                                    <option {{$day == $d ? "selected":""}} value="{{$d}}" class="">{{$d}}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">

                        <button class="button mrn-button" type="submit"
                        >ثبت و ذخیره</button>


                    </div>
                    </div>
                </form>

            </div>
            @endif

        </div>
    </div>
@endsection
@section('js2')
    <script src="{{ URL::asset('/node_modules/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $(document).on('click', '.btn-delete', function () {
                Swal.fire({
                    title: '{{__('messages.change_status')}}',
                    text: "{{__('messages.are_you_sure')}}",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{__('messages.yes_i_sure')}}',
                    cancelButtonText: '{{__('messages.cancel')}}'
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
        })
    </script>
@stop