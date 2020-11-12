@extends('layouts.global.global_layout')
@section('title',$charity['title']. " |")
@section('css')
    <link rel="stylesheet" href="{{URL::asset('/public/assets/panel/css/iranBanks/ibl.css')}}">
    <link
            href="{{ URL::asset('/node_modules/md.bootstrappersiandatetimepicker/src/jquery.md.bootstrap.datetimepicker.style.css') }}"
            rel="stylesheet" type="text/css">

    <style>
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
@stop
@section('footer_js')
    <script>

        @foreach($charity['fields'] as $fi)
        $('#p_datepicker_{{$fi["id"]}}').MdPersianDateTimePicker({
            targetTextSelector: '#p_datepicker_{{$fi["id"]}}',
            enableTimePicker: false,
            disableBeforeToday: true,
            englishNumber: true,
            disabledDays: [6],
        });
        @endforeach
    </script>
@stop

@section('js')
    <script
            src="{{ URL::asset('/node_modules/md.bootstrappersiandatetimepicker/src/jquery.md.bootstrap.datetimepicker.js') }}"></script>
    <script>
        $(document).ready(function () {
            $(document).on("change keyup", '.amount', function (event) {
                if (event.which >= 37 && event.which <= 40) return;
                $(this).val(function (index, value) {
                    return value
                        .replace(/\D/g, "")
                        .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                });
            });

            $(document).on("submit", '#frm_add_charity', function (e) {
                e.preventDefault();
                var submit = $(this).find("button[type=submit]");
                submit.attr('disabled', 'disabled');
                submit.html("لطفاً منتظر بمانید...");
                $.ajax({
                    url: "{{route('add_charity_transaction')}}",
                    type: "post",
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
                    },
                    success: function (response) {
                        if (response.message.code === 200) {
                            PNotify.success({
                                text: response.message.message,
                                delay: 3000,
                            });
                            setTimeout(function () {
                                window.location.replace("/payment/charity_vow/" + response.message.id);

                                // window.location.replace("/payment?id=" + response.message.id+"&type=charity_vow");
                            }, 2000);
                        } else {
                            PNotify.success({
                                text: response.message.message,
                                delay: 3000,
                            });
                            submit.removeAttr("disabled");
                            submit.html("{{__('messages.pay')}}")
                        }
                    }, fail: function (response) {
                        // var errors = response.responseJSON.errors;
                        // $.each(errors, function (index, value) {

                            PNotify.error({
                                delay: 3000,
                                text: 'عملیات با خطا مواجه شد، لطفا درگاه دیگری را انتخاب کنید.',
                            });
                        // });
                        submit.removeAttr("disabled");
                        submit.html("{{__('messages.pay')}}")
                    }
                });
            })
        })
    </script>
@stop

@section('content')
    <section>
        <div class="container">
            <div class="section-content">
                <div class="row">
                    <div class="col-sm-8 col-md-8">
                        <h3 class="mt-0 line-bottom">{{$charity['title']}}<span class="font-weight-300"></span></h3>
                        <form action="" method="post" id="frm_add_charity">
                            @csrf
                            <input type="hidden" name="charity_id" value="{{$charity['id']}}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-12 ">
                                        <div class="form-group">
                                            <label>{{__('messages.name_op')}}</label>
                                            <input type="text" class="form-control" value="{{isset($user['name'])?$user['name']:""}}" name="name">
                                        </div>
                                    </div>
                                    <div class="col-md-12 ">
                                        <div class="form-group">
                                            <label>{{__('messages.phone_op')}}</label>
                                            <input type="tel" pattern="09[0-9]{9}" class="form-control" value="{{isset($user['phone'])?$user['phone']:""}}" name="phone">
                                        </div>
                                    </div>
                                    <div class="col-md-12 ">
                                        <div class="form-group">
                                            <label>{{__('messages.email_op')}}</label>
                                            <input type="email" class="form-control" value="{{isset($user['email'])?$user['email']:""}}" name="email">
                                        </div>
                                    </div>
                                    @if(isset($charity['fields']))
                                    @foreach($charity['fields'] as $fi)
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>{{$fi['label']}}<span class="text-danger">{{$fi['require'] ?"*":""}}</span></label>
                                                @switch($fi['type'])
                                                    @case(0)
                                                    <input type="text" class="form-control" {{$fi['require'] ?"required":""}} name="field[{{$fi['id']}}]">
                                                    @break
                                                    @case(1)
                                                    <textarea name="field[{{$fi['id']}}]" {{$fi['require'] ?"required":""}} class="form-control"
                                                              id="field[{{$fi['id']}}]" cols="30" rows="3"></textarea>
                                                    @break
                                                    @case(2)
                                                    <input type="number" {{$fi['require'] ?"required":""}} class="form-control"
                                                           name="field[{{$fi['id']}}]">
                                                    @break
                                                    @case(3)
                                                    <input type="text" id="p_datepicker_{{$fi['id']}}" {{$fi['require'] ?"required":""}} class="form-control" name="field[{{$fi['id']}}]">
                                                    @break
                                                    @case(4)
                                                    <input type="time" {{$fi['require'] ?"required":""}} class="form-control" name="field[{{$fi['id']}}]">
                                                    @break
                                                    @case(5)
                                                    <input type="tel" pattern="09[0-9]{9}" {{$fi['require'] ?"required":""}} class="form-control" name="field[{{$fi['id']}}]">
                                                    @break
                                                @endswitch
                                            </div>
                                        </div>
                                    @endforeach
                                    @endif
                                </div>
                                <div class="col-md-6">
                                <div class="row">

                                    @if(count($charity->titles)>0)

                                        @foreach($charity->titles as $key => $title)
                                            <div class="col-md-6 col-xs-6" >
                                                <div class="radio" style="text-align: right;">
                                                    <label>
                                                        <input type="radio" name="title"
                                                               id="title_{{$title['id']}}"
                                                               value="{{$title['id']}}"
                                                              {{$key == 0 ?"checked='checked'" :""}} >
                                                        <strong><small>{{$title['title']}}</small></strong>
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach

                                    @endif

                                    <br>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="amount">{{__('messages.amount')}} <small>({{__('messages.rial')}})</small></label>
                                            <input type="text" min="{{$charity['min']}}" max="{{$charity['max']}}" class="form-control amount" name="amount">
                                        </div>
                                    </div>
                                    @foreach($gateways as $gateway)
                                        <div class="col-md-4 col-xs-4" >
                                            <div class="radio text-center">
                                                <label>
                                                    <strong>{!! $gateway['logo'] !!}</strong><br>
                                                    <input type="radio" name="gateway"
                                                           id="gateway_{{$gateway['id']}}"
                                                           value="{{$gateway['id']}}"
                                                           checked="checked">
                                                    <strong><small>{{$gateway['bank']['name']}}</small></strong>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="col-md-12 ">
                                        <div class="form-group pt-20">
                                            <button type="submit" class="btn btn-success btn-block p-20 pull-left btn-theme-colored"><strong>{{__("messages.pay")}}</strong></button>
                                        </div>
                                    </div>

                                </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-4 col-md-4">
                        <h3 class="mt-0 line-bottom">{{__('messages.cooperation')}}</h3>
                        <div class="testimonial style1 ">
                            <div class="item">

                                <div class="icon-box iconbox-border iconbox-theme-colored p-10">
                                    <p>
                                    <div class="m-30 text-justify">{!! $charity['description']!!}</div>

                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
