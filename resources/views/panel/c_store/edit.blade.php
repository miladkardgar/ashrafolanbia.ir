<?php
$active_sidbare = ['c_store', 'c_store_list']
?>

@extends('layouts.panel.panel_layout')
@section('js')
    <script src="{{URL::asset('/public/assets/panel/js/ckeditor/ckeditor.js')}}"></script>
    <script src="{{ URL::asset('/public/assets/panel/global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ URL::asset('/public/assets/panel/global_assets/js/plugins/forms/tags/tokenfield.min.js') }}"></script>
    <script src="{{URL::asset('/public/assets/panel/global_assets/js/plugins/uploaders/dropzone.min.js')}}"></script>
    <script src="{{URL::asset('/public/assets/global/js/fancybox/dist/jquery.fancybox.min.js')}}"></script>
    <script src="{{ URL::asset('node_modules/pnotify/dist/iife/PNotify.js') }}"></script>

    <script>


        var DropzoneUploader = function () {
            var _componentDropzone = function () {
                if (typeof Dropzone == 'undefined') {
                    console.warn('Warning - dropzone.min.js is not loaded.');
                    return;
                }
                var token = '{{csrf_token()}}';
                Dropzone.options.dropzoneRemove = {
                    url: "{{route('c_store.upload_product_image')}}",
                    paramName: "file",
                    dictDefaultMessage: 'Drop files to upload <span>or CLICK</span>',
                    maxFilesize: 5,
                    maxFiles: 30,
                    acceptedFiles: ".jpeg,.jpg,.png,.gif",
                    autoProcessQueue: false,
                    addRemoveLinks: true,
                    parallelUploads: 2,
                    sending: function (file, xhr, formData) {
                        formData.append("_token", token);
                        formData.append("CSP_id", {{$product['id']}});
                    },
                    init: function () {
                        var myDropzone = this;
                        var submit = $("#frm_add_image").find("button[type=submit]");
                        $("#frm_add_image").on('submit', function (e) {
                            e.preventDefault();
                            submit.attr('disabled', 'disabled');
                            submit.html("{{__('messages.please_waite')}}");
                            startUpload();
                        });

                        function startUpload() {
                            for (var i = 0; i < myDropzone.getAcceptedFiles().length; i++) {
                                myDropzone.processFile(myDropzone.getAcceptedFiles()[i]);
                            }
                        }

                        this.on('sending', function (file, xhr, formData) {
                            var data = $('#frm_add_image').serializeArray();
                            $.each(data, function (key, el) {
                                formData.append(el.name, el.value);
                            });
                        });

                        this.on("success", function (file, response) {
                            var org_name = file.name;
                            var new_name = org_name.replace(".", "_");
                            $("#file_names").append(
                                '<input class="' + new_name + '" name="file_name[]" type="hidden" value="' + response + '" />'
                            );
                            new PNotify({
                                title: '',
                                text: response.message,
                                type: 'success'
                            });
                            if (myDropzone.getQueuedFiles().length === 0 && myDropzone.getUploadingFiles().length === 0) {
                                submit.removeAttr("disabled");
                                submit.html("{{__('messages.add')}}");
                                setTimeout(function () {
                                    location.reload();
                                }, 1000)
                            }

                        });
                        this.on("complete", function (file, response) {
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
    </script>
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

            CKEDITOR.replace('Description', {
                language: 'fa',
                uiColor: '#9AB8F3',
                extraPlugins: 'filebrowser',
                filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
                filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token={{csrf_token()}}',
                filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
                filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token={{csrf_token()}}'
            });

        });

        var TagInputs = function () {

            var _componentTokenfield = function () {
                if (!$().tokenfield) {
                    console.warn('Warning - tokenfield.min.js is not loaded.');
                    return;
                }
                $('.tokenfield').tokenfield();
            };
            return {
                init: function () {
                    _componentTokenfield();
                }
            }
        }();
        document.addEventListener('DOMContentLoaded', function () {
            TagInputs.init();
        });

    </script>
    <script>
        var Select2Selects = function () {
            var _componentSelect2 = function () {
                if (!$().select2) {
                    console.warn('Warning - select2.min.js is not loaded.');
                    return;
                }
                $('.select').select2({
                    minimumResultsForSearch: Infinity
                });
            };


            return {
                init: function () {
                    _componentSelect2();
                }
            }
        }();

        document.addEventListener('DOMContentLoaded', function () {
            Select2Selects.init();
        });
    </script>
@endsection
@section('css')
    <link rel="stylesheet" href="{{URL::asset('/public/assets/global/js/fancybox/dist/jquery.fancybox.min.css')}}"
          type="text/css" media="screen"/>
@endsection
@section('content')

    <div class="content">

        <div class="card">
            <div class="card-header">
                <div class="header-elements p-0 pull-left">
                    <button class="btn btn-primary m-2 py-2 px-3"
                            data-toggle="modal"
                            data-modal-title="{{trans('messages.add_category',['item'=>trans('messages.category')])}}"
                            data-target="#general_modal"
                    >
                        افزودن تصویر
                        <i class="icon-image5"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($product->images as $media)

                            <div class="col-sm-6 col-lg-3">
                                <div class="card  {{$media['main_img']? "border-2 border-success":""}}">
                                    <div class="card-img-actions m-1">
                                        <img class="card-img img-responsive" width="267" height="178"
                                             src="{{$media['medium']}}"
                                             alt="">
                                        <div class="card-img-actions-overlay card-img">
                                            <a href="{{$media['large']}}"
                                               class="btn btn-outline fancybox-thumb bg-white text-white border-white border-2 btn-icon rounded-round "
                                               data-fancybox="images"
                                               data-caption="">
                                                <i class="icon-eye"></i>
                                            </a>


                                            <a href="javascript:;"
                                               class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round swal-alert m-1"
                                               data-ajax-link="{{route('c_store.remove_product_image',['id'=>$media['id']])}}"
                                               data-method="DELETE"
                                               data-csrf="{{csrf_token()}}"
                                               data-title="{{trans('messages.delete_item',['item'=>trans('messages.file')])}}"
                                               data-text="{{trans('messages.delete_item_text',['item'=>trans('messages.file')])}}"
                                               data-type="warning"
                                               data-cancel="true"
                                               data-confirm-text="{{trans('messages.delete')}}"
                                               data-cancel-text="{{trans('messages.cancel')}}"><i
                                                        class="icon-trash"></i>
                                            </a>

                                            <a href="javascript:;"
                                               class="btn btn-outline
                                                        {{false?' border-success bg-success ':' border-white bg-white '}}
                                                       text-white border-2 btn-icon rounded-round swal-alert m-1"
                                               data-ajax-link="{{route('c_store.set_main_product_image',['CSP_id'=>$media['CSP_id'],'media_id'=>$media['id'],'status'=>'main'])}}"
                                               data-method="post"
                                               data-csrf="{{csrf_token()}}"
                                               data-title="{{trans('messages.approve',['item'=>trans('messages.image')])}}"
                                               data-text="{{trans('messages.approve_image_text')}}"
                                               data-type="warning"
                                               data-cancel="true"
                                               data-confirm-text="{{trans('messages.approve')}}"
                                               data-cancel-text="{{trans('messages.cancel')}}">
                                                تصویر اصلی
                                            </a>

                                        </div>

                                    </div>
                                </div>
                            </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{route('c_store.update_product',['slug'=>$product['slug']])}}">
                    @csrf
                    <input type="hidden" name="CSP_id" value="{{$product['id']}}">
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label for="Title" class="label-default">عنوان</label>
                            <input type="text" name="title" value="{{$product['title']}}" class="form-control"
                                   id="Title">
                        </div>
                        <div class="col-md-3">
                            <label for="Price" class="label-default text-info font-weight-bold">قیمت</label>
                            <input type="text" name="price" value="{{number_format($product['price'])}}" class="form-control amount"
                                   id="Price">
                        </div>
                        <div class="col-md-3">
                            <label for="Count" class="label-default">تعداد موجودی</label>
                            <input type="text" name="count" value="{{$product['count']}}" class="form-control amount"
                                   id="Count">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <label for="Description" class="label-default">توضیحات</label>
                            <textarea name="description" id="Description" cols="30"
                                      class="form-control">{!! $product['description'] !!}</textarea>

                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label for="Dimension" class="label-default">ابعاد</label>
                            <input type="text" name="dimension" value="{{$product['dimension']}}" class="form-control"
                                   id="Dimension">
                        </div>
                        <div class="col-md-6">
                            <label for="Weight" class="label-default">وزن</label>
                            <input type="text" name="weight" value="{{$product['weight']}}" class="form-control"
                                   id="Weight">
                        </div>
                        <div class="col-md-6">
                            <label for="Keywords" class="label-default">تگ ها (سئو)</label>
                            <div class="input-group" id="Keywords">
										<span class="input-group-prepend">
											<span class="input-group-text"><i class="icon-price-tags"></i></span>
										</span>
                                <input type="text" name="keywords" value="{{$product['keywords']}}"
                                       class="form-control tokenfield" data-fouc>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <label for="Delivery_description" class="label-default">توضیح شرایط تحویل محصول</label>
                            <textarea name="delivery_description" id="Delivery_description" cols="30"
                                      class="form-control">{{$product['delivery_description']}}</textarea>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <label for="Warning_info" class="label-default text-danger"><i class="icon-warning"></i>
                                توضیحات ویژه
                                <span class="text-muted"> (این توضیحات به صورت ویژه به نیکوکار نمایش داده میشود) </span>
                            </label>
                            <textarea name="warning_info" id="Warning_info"
                                      class="form-control">{{$product['warning_info']}}</textarea>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <label for="Allowed_provinces" class="label-default ">استان های مجاز
                                <span class="text-muted"> (اگر خالی باشد تمام استان ها مجاز به ثبت سفارش هستند) </span>
                            </label>
                            <select id="Allowed_provinces" name="allowed_provinces[]" multiple="multiple"
                                    class="form-control select" data-fouc>
                                @foreach(get_provinces() as $province)
                                    <option {{($product['allowed_provinces'] != null and in_array($province['id'],explode(',',$product['allowed_provinces']))) ? "selected":""}} value="{{$province['id']}}">{{$province['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <label for="Allowed_cities" class="label-default ">شهر های مجاز
                            </label>
                            <select id="Allowed_cities" name="allowed_cities[]" multiple="multiple"
                                    class="form-control select" data-fouc>
                                @foreach(get_cites() as $city)
                                    <option {{($product['allowed_cities'] != null and in_array($city['id'],explode(',',$product['allowed_cities']))) ? "selected":""}} value="{{$city['id']}}">{{$city['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-3">
                            <label for="Delivery_delay" class="label-default ">
                                فاصله زمانی از ثبت تا ارسال
                                <span class="text-muted"> (روز) </span>
                            </label>
                            <input type="text" id="Delivery_delay" name="delivery_delay"
                                   value="{{$product['delivery_delay']}}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="Delay_type" class="label-default ">
                                نوع فاصله زمانی
                            </label>
                            <select id="Delay_type" name="delay_type" class="form-control select" data-fouc>
                                <option {{ $product['delivery_delay_type'] == 'actual_day' ? "selected":""}} value="actual_day">
                                    روز واقعی
                                </option>
                                <option {{ $product['delivery_delay_type'] == 'working_day' ? "selected":""}} value="working_day">
                                    روز کاری
                                </option>
                            </select>

                        </div>
                        <div class="col-md-3">
                            <label for="Status" class="label-default ">
وضعیت
                            </label>
                            <select id="Status" name="status" class="form-control select" data-fouc>
                                <option {{  $product['active'] == 1 ? "selected":""}} value="1">
فعال (نمایش داده شود)
                                </option>
                                <option {{  $product['active'] == 0 ? "selected":""}} value="0">
                                    غیر فعال
                                </option>
                            </select>

                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-9"></div>
                        <div class="col-md-3 ">
                            <button class="btn btn-success pull-left">
                                ذخیره
                                <span class="icon-stack-plus"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="general_modal" class="modal fade">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h6 class="modal-title">{{__('messages.add_image')}}</h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" id="frm_add_image" enctype="multipart/form-data">
                        <div class="dropzone" id="dropzone_remove">
                            <div class="fallback">
                                <input name="file" type="file" multiple/>
                            </div>
                        </div>
                        <div class="form-group pull-left pt-2">
                            <button type="button" id="button" class="btn btn-default" class="close"
                                    data-dismiss="modal">{{__('messages.cancel')}}</button>
                            <button type="submit" id="button" class="btn btn-primary">{{__('messages.add')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection