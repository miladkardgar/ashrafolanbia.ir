@extends('global.t-profile.frame')
<?php $active_sidebar = ['edit_profile'] ?>
@section('js2')
    <script src="{{URL::asset('/public/assets/panel/global_assets/js/plugins/uploaders/dropzone.min.js')}}"></script>
    <script src="{{ URL::asset('/public/assets/panel/global_assets/js/plugins/forms/validation/validate.min.js') }}"></script>
    <script src="{{ URL::asset('/public/assets/global/js/localization/messages_fa.js') }}"></script>
    <script src="{{ URL::asset('/node_modules/md.bootstrappersiandatetimepicker/src/jquery.md.bootstrap.datetimepicker.js') }}"></script>

    <script>
        var DropzoneUploader = function () {


            // Dropzone file uploader
            var _componentDropzone = function () {
                if (typeof Dropzone == 'undefined') {
                    console.warn('Warning - dropzone.min.js is not loaded.');
                    return;
                }

                // Removable thumbnails
                Dropzone.options.dropzoneRemove = {
                    url: "{{route('global_profile_completion_upload_image')}}", // The name that will be used to transfer the file
                    paramName: "file", // The name that will be used to transfer the file
                    dictDefaultMessage: '{{__('messages.please_click_for_change_profile_picture')}}',
                    maxFilesize: 4, // MB
                    acceptedFiles: ".jpeg,.jpg,.png",
                    maxFiles: 1,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    addRemoveLinks: true,
                    init: function () {
                        this.on("success", function (file, response) {
                            var org_name = file.name;
                            var new_name = org_name.replace(".", "_");
                            $("#file_names").append(
                                '<input class="' + new_name + '" name="doc_id[]" type="hidden" value="' + response + '" />'
                            );
                        });
                        this.on("complete", function (file) {
                            $("input").remove(".dz-hidden-input");
                            $('.dz-hidden-input').hide();
                        });
                        this.on("removedfile", function (file) {
                            var org_name = file.name;
                            var new_name = org_name.replace(".", "_");
                            $('.' + new_name).remove();

                        });
                    }
                };

            };
            return {
                init: function () {
                    _componentDropzone();

                }
            }
        }();
        DropzoneUploader.init();

        $(document).ready(function () {
            $(document).on('submit', '#contact_form', function (e) {
                e.preventDefault();

                var form_btn = $(this).find('button[type="submit"]');
                var form_btn_old_msg = form_btn.html();
                form_btn.html(form_btn.prop('disabled', true).data("loading-text"));

                if ($(this).valid()) {
                    $.ajax({
                        url: "{{route('global_profile_completion_submit')}}",
                        type: "post",
                        data: $(this).serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (data) {

                            if (data.message.status === 200) {
                                PNotify.success({
                                    text: data.message.message,
                                    delay: 3000,
                                });
                            }
                            form_btn.prop('disabled', false).html(form_btn_old_msg);
                            setTimeout(function () {
                                window.location.href = "{{route('global_profile_completion')}}";
                            }, 1000)
                        }, error: function (error) {
                            console.log(error)
                            $.each(error.responseJSON.errors, function (i, item) {
                                PNotify.error({
                                    text: item,
                                    delay: 3000,
                                });
                            });
                            form_btn.prop('disabled', false).html(form_btn_old_msg);
                        }
                    });
                }
                // form_btn.prop('disabled', false).html(form_btn_old_msg);

            })

            $('#birthday').MdPersianDateTimePicker({
                targetTextSelector: '#birthday',
                disableAfterToday: true
            });
        })
    </script>
    <script>
        $("#change_password_form").validate({
            lang: "fa",
            rules: {
                now_password: {
                    required: true,
                    minlength: 3
                },
                password: {
                    required: true,
                    minlength: 5,
                    maxlength: 100,
                },
                password_confirmation: {
                    minlength: 5,
                    equalTo: "#password"
                },
            },
            submitHandler: function (form) {
                var form_btn = $(form).find('button[type="submit"]');
                var form_result_div = '#form-result';
                $(form_result_div).remove();
                form_btn.before('<div id="form-result" class="alert alert-success" role="alert" style="display: none;"></div>');
                var form_btn_old_msg = form_btn.html();
                form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
                $(form).ajaxSubmit({
                    dataType: '',
                    success: function (data) {
                        PNotify.success({
                            text: data.message,
                            delay: 3000,
                        });
                        setTimeout(function () {
                            location.reload();
                        }, 3000);
                        $(form).find('.form-control').val('');
                        $(form_btn).html(form_btn_old_msg);
                        $(form_result_div).html(data.message).fadeIn('slow');
                        setTimeout(function () {
                            $(form_result_div).fadeOut('slow')
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
                        setTimeout(function () {
                            $('[type="submit"]').prop('disabled', false);
                        }, 2500);
                        $(form_btn).html(form_btn_old_msg);

                    }
                });
            }
        });
    </script>

@stop
@section('css2')
    <link rel="stylesheet" href="{{ URL::asset('/public/vendor/laravel-filemanager/css/dropzone.min.css') }}">
    <link href="{{ URL::asset('/node_modules/md.bootstrappersiandatetimepicker/src/jquery.md.bootstrap.datetimepicker.style.css') }}"
          rel="stylesheet" type="text/css">

@stop

@section('mrn-content')
    @if(!$userInfo['phone_verified_at'])
    <div class="mrn-notifications-box-danger">
        <h4 class="notifications"><i class="fa fa-mobile-phone"></i> تایید شماره موبایل </h4>
        <!-- new post form -->
        <form method="GET" class="form" action="{{route('global_profile_send_sms')}}">
            <div class="row">
                <div class="form-group col-md-6 ">
                    <input id="phone" name="mobile" value="{{$userInfo['phone']? $userInfo['phone'] :""}}"
                           {{$userInfo['phone']? 'disabled' :""}} required="required" type="text" class="form-control "
                           style="margin-top: 1em"
                           placeholder="{{__('messages.mobile')}}">


                </div>
                <div class="form-group col-md-6 ">

                    <button class="button mrn-button mt-1" type="submit"
                    >{{__('ارسال کد تایید')}}</button>
                </div>
            </div>


        </form>
        @if($userInfo['code_phone_send'] and (time()-strtotime($userInfo['code_phone_send']))<120 )
            <form method="Post" class="form" action="{{route('global_profile_verify_mobile')}}">
                @csrf
                <div class="row">
                    <div class="form-group col-md-6 ">
                        <input id="activation_code" required="required" type="text" class="form-control "
                               style="margin-top: 1em" name="code"
                               placeholder="{{__('کد فعال سازی')}}">

                    </div>
                    <div class="form-group col-md-6 ">

                        <button class="button mrn-button-success mt-1" type="submit"
                        >{{__('تایید')}}</button>
                    </div>
                </div>


            </form>
        @endif
    </div>
    @endif
    @if(!$userInfo['email_verified_at'])
    <div class="mrn-notifications-box-warning">
        <h4 class="notifications"><i class="fa fa-mobile-phone"></i> تایید ایمیل </h4>
        <!-- new post form -->
        <form method="GET" class="form" action="{{route('global_profile_send_email')}}">
            <div class="row">
                <p class="">ایمیل شما تایید نشده است لطفا آن را تایید کنید.</p>
                <div class="form-group col-md-6 ">
                    <input id="phone" name="email" value="{{$userInfo['email']? $userInfo['email'] :""}}"
                           {{$userInfo['email']? 'disabled' :""}} required="required" type="email" class="form-control "
                           style="margin-top: 1em"
                           placeholder="{{__('messages.email')}}">

                </div>
                <div class="form-group col-md-6 ">

                    <button class="button mrn-button mt-1" type="submit"
                    >{{__('تایید آدرس ایمیل')}}</button>
                </div>
            </div>


        </form>
        @if($userInfo['code_email_send'] and (time()-strtotime($userInfo['code_email_send']))<1800 )
            <form method="Post" class="form" action="{{route('global_profile_verify_email')}}">
                @csrf
                <div class="row">
                    <div class="form-group col-md-6 ">
                        <input id="activation_code" required="required" type="text" class="form-control "
                               style="margin-top: 1em" name="code"
                               placeholder="{{__('کد فعال سازی')}}">

                    </div>
                    <div class="form-group col-md-6 ">

                        <button class="button mrn-button-success mt-1" type="submit"
                        >{{__('تایید')}}</button>
                    </div>
                </div>


            </form>
        @endif
    </div>
    @endif
    <div class="mrn-notifications-box">
        <h4 class="notifications"><i class="fa fa-key"></i> تغییر رمز عبور </h4>
        <form id="change_password_form" method="post" action="{{route('global_update_password')}}" class="clearfix">
            @csrf
            <div class="row">
                <div class="form-group col-md-6 ">
                    <label for="old_password" class="pull-right">{{__('messages.now_password')}}</label>
                    <input id="old_password" name="old_password" class="form-control left"
                           type="password" required="required" placeholder="{{__('messages.now_password')}}">
                </div>
            </div>
            <div class="row">

                <div class="form-group col-md-6">
                    <label for="password" class="pull-right">{{__('messages.new_password')}}</label>
                    <input id="password" required="required" name="password" class="form-control" type="password"
                           placeholder="{{__('messages.new_password')}}">
                </div>
                <div class="form-group col-md-6">
                    <label for="password_confirmation" class="pull-right">{{__('messages.repeat_new_password')}}</label>
                    <input id="password_confirmation" name="password_confirmation" class="form-control"
                           type="password" required="required" placeholder="{{__('messages.repeat_new_password')}}">
                </div>
            </div>
            <div class="">
                <button class="button mrn-button" type="submit"
                >{{__('messages.change_password')}}</button>
            </div>
        </form>

    </div>

    <div class="mrn-notifications-box">
        <h4 class="notifications"><i class="fa fa-edit"></i> ویرایش اطلاعات</h4>

        <form id="contact_form" name="contact_form" class="" novalidate
              method="post">
            <div class="row">
                <div class=" col-md-6">
                    <div class="form-group">
                        <label for="form_name">{{__('نام')}}
                            <small>*</small>
                        </label>
                        <input id="form_name" name="name" class="form-control" type="text"
                               placeholder="{{__('messages.enter_name')}}" required=""
                               minlength="2" maxlength="100" value="{{$userInfo['people']['name']}}">
                    </div>
                </div>
                <div class=" col-md-6">
                    <div class="form-group">
                        <label for="form_family">{{__('messages.family')}}
                            <small>*</small>
                        </label>
                        <input id="form_family" name="family" class="form-control" type="text"
                               placeholder="{{__('messages.enter_family')}}" required='required'
                               minlength="2" maxlength="100" value="{{$userInfo['people']['family']}}">
                    </div>
                </div>
                <div class=" col-md-6">
                    <div class="form-group">
                        <label for="form_national_code">{{__('messages.national_code')}} </label>
                        <input id="form_national_code" name="national_code" class="form-control"
                               type="tel" placeholder="{{__('messages.enter_national_code')}}"
                               minlength="10" maxlength="10"
                               value="{{$userInfo['people']['national_code']}}">
                    </div>
                </div>

                <div class=" col-md-6">
                    <div class="form-group">
                        <label for="form_email">{{__('messages.email')}}
                            @if($userInfo['email_verified_at'])
                                <span class="fa fa-check-circle-o" title="ایمیل تایید شده" style="color: #21bf26"> ایمیل تایید شده </span>
                            @endif
                        </label>
                        <input id="form_email" name="email" class="form-control email"
                               type="email" placeholder="{{__('messages.enter_email')}}"
                               value="{{$userInfo['email']}}">
                    </div>
                </div>
                <div class=" col-md-6">
                    <div class="form-group">
                        <label for="form_phone">{{__('messages.phone')}}

                        </label>
                        <input id="form_phone" name="phone" class="form-control" type="number"
                               placeholder=""
                               maxlength="11"
                               value="{{$userInfo['people']['phone']}}">
                    </div>
                </div>
                <div class=" col-md-6">
                    <div class="form-group">
                        <label for="form_mobile">{{__('شماره موبایل')}}
                            @if($userInfo['phone_verified_at'])
                            <span class="fa fa-check-circle-o" title="شماره تایید شده" style="color: #21bf26"> شماره تایید شده </span>
                            @endif
                        </label>
                        <input id="form_mobile" name="mobile" class="form-control" type="number"
                               placeholder="" maxlength="11"
                               value="{{$userInfo['phone']}}">
                    </div>
                </div>
                <div class=" col-md-6">
                    <div class="form-group">
                        <label for="amount">{{__('messages.birth_date')}}</label>
                        <input id="birthday" type="text" class="form-control"
                               name="birthday"
                               value="@if($userInfo['people']['birth_date']){{jdate("Y-m-d",strtotime($userInfo['people']['birth_date']))}}@endif"
                               autocomplete="capacity">
                    </div>
                </div>
                <div class=" col-md-6">
                    <div class="form-group">
                        <label for="">{{__('messages.gender')}}</label>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input value="1" type="radio" class="custom-control-input" name="gender"
                                   id="custom_radio_inline_g1"
                                    {{$userInfo['people']['gender']==1?'checked="checked"':''}}>
                            <label class="custom-control-label"
                                   for="custom_radio_inline_g1">{{__('messages.male')}}</label>

                            <input value="2" type="radio" class="custom-control-input" name="gender"
                                   id="custom_radio_inline_g2"
                                    {{$userInfo['people']['gender']==2?'checked="checked"':''}}>
                            <label class="custom-control-label"
                                   for="custom_radio_inline_g2">{{__('messages.female')}}</label>
                        </div>
                    </div>
                </div>
                <div class=" col-md-12">
                    <div class="form-group">
                        <label for="">{{__('messages.address')}}</label>
                        <textarea name="address" id="Address" cols="30" rows="3" class="form-control">{{$userInfo['address']}}</textarea>

                    </div>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="button mrn-button"
                        data-loading-text="Please wait...">{{__('messages.save')}}
                </button>

            </div>
        </form>

    </div>


    <div class="mrn-notifications-box">
        <h4 class="notifications"><i class="fa fa-camera"></i> تصویر </h4>
        <div class="media text-center">
            @if($userInfo['profile_image']->last())
                <?php
                $image = $userInfo['profile_image']->last();
                ?>
                <img src="/{{$image['path']}}/300/{{$image['name']}}" width="200"
                     alt="{{$userInfo['people']['name']}} {{$userInfo['people']['family']}} - {{__('messages.ashraf')}}">
            @else
                <img src="{{asset(url('/public/assets/global/images/unknown-avatar.png'))}}" width="200"
                     alt="{{__('messages.ashraf')}}">
            @endif
            <div class="form-group pt-20">
                <div class="dropzone" id="dropzone_remove">
                    <div class="fallback">
                        <input name="file" type="file"/>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection