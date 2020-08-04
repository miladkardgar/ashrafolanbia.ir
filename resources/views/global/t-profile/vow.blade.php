@extends('global.t-profile.frame')
<?php $active_sidebar = ['vow'];
$type = -1;
$active_type = -1;
$same_type = false;
$routine_types = config('charity.routine_types');
$current_routine = null;

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
if (isset($routine_types[$type])){
    $current_routine = $routine_types[$type];
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
                @foreach($routine_types as $key => $routine_type)
                    <li class="mrn-card {{$type == $key ? "mrn-card-active":""}}"><a href="{{route('t_routine_vow',['type'=>$key])}}"><p> @if($active_type==$key)<i class="fa fa-check-circle text-success"></i>  @endif  {{$routine_type['title']}}  </p></a></li>
                @endforeach
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

            <div class="mrn-notifications-box">
                <ul class="list-unstyled">
                    <li class="announce-read">
                        <div class="notifications-content">
                            <p>
                                {!!  $pattern->description!!}
                            </p>

                        </div>
                    </li>
                </ul>

            </div>

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
                                    <span class="text-black-50">{{jdate('l',strtotime($routine['next_date']))}} | {{miladi_to_shamsi_date($routine['next_date'])}} </span>
                                </h3>
                                </div>
                        </li>
                    </ul>
                </div>
            @endif
            @if($type > -1)
            @if(array_key_exists($type,$routine_types))
                    <div class="mrn-notifications-box-green">
                        <h4 class="notifications"><i class="fa fa-bell-o"></i>  </h4>

                        <ul class="list-unstyled">
                            <li class="announce-read">
                                <div class="notifications-content">
                                    <p>
                                        {{$routine_types[$type]['description']}}
                                    </p>

                                </div>
                            </li>
                        </ul>
                    </div>
            @endif
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
                    @if($current_routine and !in_array($current_routine['week_day'],[0,1,2,3,4,5,6]))

                    <h4 class="notifications">روز شروع </h4>
                    @php
                        if($same_type){
                            $day = latin_num(jdate("d",strtotime($routine['start_date'])));
                        }
                        else{
                            $day = latin_num(jdate("d",time()));
                        }
                    @endphp
                    <div class="row">
                        <br>
                        <div class="form-group col-md-6">
                            <label for="Day" class="">روز:</label>
                            <select name="day" id="Day" class="form-control">
                                <option value="" disabled  class="">روز ماه:</option>
                                @for($d=1 ; $d<=29;$d++)
                                    <option {{$day == $d ? "selected":""}} value="{{$d}}" class="">{{$d}}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    @endif
                    <div align="" class="row">
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