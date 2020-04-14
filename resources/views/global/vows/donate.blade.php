@extends('layouts.global.global_layout')
@section('title',__('messages.donate'). " |")
@section('css')
    <link rel="stylesheet" href="{{URL::asset('/public/assets/panel/css/iranBanks/ibl.css')}}">
@stop
@section('js')
    <script>
        $(document).ready(function () {

            $(document).on("change keyup", '.amount', function (event) {
                if (event.which >= 37 && event.which <= 40) return;
                $(this).val(function (index, value) {
                    return value
                        .replace(/\D/g, "")
                        .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                        ;
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
                                window.location.replace("/payment/charity_donate/" + response.message.id);
                                // submit.removeAttr("disabled");
                                {{--submit.html("{{__('messages.pay')}}");--}}
                            }, 2000);

                        } else {
                            PNotify.success({
                                text: response.message.message,
                                delay: 3000,
                            });
                            submit.removeAttr("disabled");
                            submit.html("{{__('messages.pay')}}");
                        }

                    }, error: function (response) {
                        var errors = response.responseJSON.errors;
                        $.each(errors, function (index, value) {
                            PNotify.error({
                                delay: 3000,
                                title: '',
                                text: value,
                            });
                        });
                        submit.removeAttr("disabled");
                        submit.html("{{__('messages.pay')}}")
                    }
                });
            })
        })
    </script>
@stop
@section('css')
    <style>
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
@stop
@section('content')
    <section>
        <div class="container">
            <div class="section-content">
                <div class="row">
                    <div class="col-xs-12 col-sm-4 col-md-4 pull-left">
                        <h3 class="mt-0 line-bottom">{{__('messages.cooperation')}}</h3>
                        <div class="testimonial style1 ">
                            <div class="item">

                                <div class="icon-box iconbox-border iconbox-theme-colored p-10">
                                    <p>
                                    <div class="m-30 text-justify">{!! $patern['description']!!}</div>

                                    </p>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="col-xs-12 col-sm-8 col-md-8">
                        <h3 class="mt-0 line-bottom">{{$patern['title']}}<span class="font-weight-300"></span></h3>
                        <form action="" method="post" id="frm_add_charity">
                            @csrf
                            <input type="hidden" name="charity_id" value="{{$patern['id']}}">
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <div class="col-md-12 col-xs-12">
                                        <div class="form-group">
                                            <label>{{__('messages.name_op')}}</label>
                                            <input type="text" class="form-control" name="name">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xs-12">
                                        <div class="form-group">
                                            <label>{{__('messages.phone_op')}}</label>
                                            <input type="number" class="form-control" name="phone">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xs-12">
                                        <div class="form-group">
                                            <label>{{__('messages.email_op')}}</label>
                                            <input type="email" class="form-control" name="email">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="amount">{{__('messages.amount')}}
                                                <small>({{__('messages.rial')}})</small>
                                            </label>
                                            <input type="text" min="{{$patern['min']}}" max="{{$patern['max']}}"
                                                   class="form-control amount" name="amount"
                                                   placeholder="{{__('messages.amount_rial')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="col-md-12 col-xs-12">
                                        <div class="form-group">
                                            <label>{{__('messages.for')}}</label>
                                            <select name="title" class="form-control" id="title">
                                                @foreach($title as $titl)
                                                    <option value="{{$titl['id']}}">{{$titl['title']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xs-12">
                                        <div class="form-group">
                                            <label>{{__('messages.description')}}</label>
                                            <textarea name="description" class="form-control" id="description" cols="30"
                                                      rows="5"></textarea>
                                        </div>
                                    </div>
{{--                                    <div class="col-md-12 col-xs-12">--}}
{{--                                        <label for="">{{__('messages.payment_gateway')}}</label>--}}
{{--                                        <select name="gateway" id="gateway" class="form-control">--}}
{{--                                            @foreach($gateways as $gateway)--}}
{{--                                                <option value="{{$gateway['id']}}">{{$gateway['bank']['name']}}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
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
                                    <div class="col-md-12 col-xs-12">
                                        <div class="form-group pt-20">
                                            <button type="submit" class="btn btn-success btn-block p-20 pull-left btn-theme-colored"><strong>{{__("messages.pay")}}</strong></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
