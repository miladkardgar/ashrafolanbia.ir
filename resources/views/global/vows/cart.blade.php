@extends('layouts.global.global_layout')
@section('css')
    <link rel="stylesheet" href="{{URL::asset('/public/assets/panel/css/iranBanks/ibl.css')}}">
@stop

@section('js')
    <script>
        $(document).ready(function () {
            $(document).on("submit", '#frm_payment', function (e) {
                e.preventDefault();
                var submit = $(this).find("button[type=submit]");
                submit.attr('disabled', 'disabled');
                submit.html("{{__('messages.please_waite')}}");
                $.ajax({
                    url: "{{route('payment2',['type2'=>"charity_period",'id2'=>$charityIn['id']])}}",
                    type: "POST",
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
                    },
                    success: function (response) {
                        PNotify.success({
                            text: "{{__('messages.is_going_connect_to_gateway')}}",
                            delay: 3000,
                        });

                        setTimeout(function () {
                            $("#res").html(response);
                            submit.removeAttr("disabled");
                            submit.html("{{__('messages.pay')}}")
                        }, 2000);

                    }, error: function (response) {
                        console.log(response)
                        // var errors = response.responseJSON.errors;
                        // $.each(errors, function (index, value) {
                            PNotify.error({
                                delay: 3000,
                                text: 'خطا در انجام عملیات، لطفا درگاه دیگری انتخاب کنید.',
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
        <div class="content">
            <div class="row pt-50 pb-50">
                <div class="col-md-6 col-md-push-3">
                    <h4 class="mt-0 pt-5"> {{$charityIn['period']['description']}} </h4>
                    <hr>
                    <form action="" method="get" id="frm_payment">
                        @csrf
                        <input type="hidden" value="{{$charityIn['id']}}" name="id">
                        <input type="hidden" value="charity_period" name="type">
                        <div class="row" style="margin: 3px">
                            <div class=" col-md-7">
                                <div class="row">
                                    <div class="col-md-4  pt-20">
                                        <strong>{{__('messages.description')}}</strong>
                                    </div>
                                    <div class="col-md-8  pt-20 text-center">
                                        <h4>{{$charityIn['description']}}</h4>
                                    </div>


                                    <div class="col-md-4  pt-20">
                                        <strong>{{__('messages.name')}}</strong>
                                    </div>
                                    <div class="col-md-8  pt-20 text-center">
                                        <h4>{{$name}}</h4>
                                    </div>
                                    <div class="col-md-4  pt-20">
                                        <strong>{{__('messages.price')}}:</strong>
                                    </div>
                                    <div class="col-md-8  pt-20 text-center">
                                        <h4> {{number_format($charityIn['amount'])}}
                                            <small>{{__('messages.rial')}}</small>
                                        </h4>
                                    </div>
                                    <div class="col-md-4  pt-20">
                                        <strong>{{__("messages.payment_date")}}:</strong>
                                    </div>
                                    <div class="col-md-8  pt-20 text-center">
                                        <h4>{{jdate("Y-m-d",strtotime($charityIn['payment_date']))}}</h4>
                                    </div>
                                    <?php  $count_ids = count(explode(',',str_replace(['[',']'],'',$charityIn['group_ids']))); ?>
                                    @if($count_ids >1)
                                        <div class="col-md-12  pt-20 text-center">
                                            <h4>پرداخت {{$count_ids}} موعد تعهد </h4>
                                        </div>

                                    @endif
                                </div>
                            </div>
                            <div class=" col-md-5">
                                <div class="col-md-12  text-center form-group">
                                    <strong>{{__('messages.payment_gateway')}}</strong>
                                </div>
                                <div class="col-md-12  text-center form-group row">
                                    @foreach($gateways as $gateway)
                                        <div class="col-md-6 col-xs-6" >
                                            <div class="radio m-1">
                                                <label>
                                                    <strong>{!! $gateway['logo'] !!}</strong><br>

                                                    <input type="radio" name="gateway_id"
                                                           id="gateway_{{$gateway['id']}}"
                                                           value="{{$gateway['id']}}"
                                                           checked="checked">
                                                    <strong>{{$gateway['bank']['name']}}</strong>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-md-12  text-center form-group">
                                    <button class="btn btn-block btn-theme-colored"
                                            type="submit">{{__("messages.pay")}}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div id="res"></div>
            </div>
        </div>
    </section>
@stop
