<?php
$active_sidbare = ['c_store','c_store_list']
?>

@extends('layouts.panel.panel_layout')
@section('js')
    <script src="{{URL::asset('/public/assets/panel/js/ckeditor/ckeditor.js')}}"></script>
    <script src="{{ URL::asset('/public/assets/panel/global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ URL::asset('/public/assets/panel/global_assets/js/plugins/forms/tags/tokenfield.min.js') }}"></script>

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

        var TagInputs = function() {

            var _componentTokenfield = function() {
                if (!$().tokenfield) {
                    console.warn('Warning - tokenfield.min.js is not loaded.');
                    return;
                }
                $('.tokenfield').tokenfield();
            };
            return {
                init: function() {
                    _componentTokenfield();
                }
            }
        }();
        document.addEventListener('DOMContentLoaded', function() {
            TagInputs.init();
        });

    </script>

    <script >
        var Select2Selects = function() {
            var _componentSelect2 = function() {
                if (!$().select2) {
                    console.warn('Warning - select2.min.js is not loaded.');
                    return;
                }
                $('.select').select2({
                    minimumResultsForSearch: Infinity
                });
            };


            return {
                init: function() {
                    _componentSelect2();
                }
            }
        }();

        document.addEventListener('DOMContentLoaded', function() {
            Select2Selects.init();
        });
    </script>
@endsection
@section('css')

@endsection
@section('content')

    <div class="content">
        <div class="card">
            <div class="card-header"></div>
            <div class="card-body">
                <form method="post" action="{{route('c_store.store_product')}}" >
                @csrf
                    <div class="row mt-2">
                    <div class="col-md-6">
                        <label for="Title" class="label-default">عنوان</label>
                        <input type="text" name="title" value="{{old('title')}}" class="form-control" id="Title">
                    </div>
                    <div class="col-md-3">
                        <label for="Price" class="label-default text-info font-weight-bold">قیمت</label>
                        <input type="text" name="price" value="{{old('price')}}" class="form-control amount" id="Price">
                    </div>
                    <div class="col-md-3">
                        <label for="Count" class="label-default">تعداد موجودی</label>
                        <input type="text" name="count" value="{{old('count')}}" class="form-control amount" id="Count">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <label for="Description" class="label-default">توضیحات</label>
                        <textarea name="description"  id="Description" cols="30" class="form-control">{!! old('description') !!}</textarea>

                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label for="Dimension" class="label-default">ابعاد</label>
                        <input type="text" name="dimension" value="{{old('dimension')}}" class="form-control" id="Dimension">
                    </div>
                    <div class="col-md-6">
                        <label for="Weight" class="label-default">وزن</label>
                        <input type="text" name="weight" value="{{old('weight')}}" class="form-control" id="Weight">
                    </div>
                    <div class="col-md-6">
                        <label for="Keywords" class="label-default">تگ ها (سئو)</label>
                        <div class="input-group" id="Keywords">
										<span class="input-group-prepend">
											<span class="input-group-text"><i class="icon-price-tags"></i></span>
										</span>
                            <input type="text" name="keywords" value="{{old('keywords')}}" class="form-control tokenfield"  data-fouc>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <label for="Delivery_description" class="label-default">توضیح شرایط تحویل محصول</label>
                        <textarea name="delivery_description"  id="Delivery_description" cols="30"  class="form-control">{{old('delivery_description')}}</textarea>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <label for="Warning_info" class="label-default text-danger"><i class="icon-warning"></i>  توضیحات ویژه
                        <span class="text-muted"> (این توضیحات به صورت ویژه به نیکوکار نمایش داده میشود) </span>
                        </label>
                        <textarea name="warning_info" id="Warning_info"   class="form-control">{{old('warning_info')}}</textarea>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <label for="Allowed_provinces" class="label-default ">استان های مجاز
                        <span class="text-muted"> (اگر خالی باشد تمام استان ها مجاز به ثبت سفارش هستند) </span>
                        </label>
                        <select id="Allowed_provinces" name="allowed_provinces[]"  multiple="multiple" class="form-control select" data-fouc>
                            @foreach(get_provinces() as $province)
                                <option {{(old('allowed_provinces') != null and in_array($province['id'],old('allowed_provinces'))) ? "selected":""}} value="{{$province['id']}}">{{$province['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <label for="Allowed_cities" class="label-default ">شهر های مجاز
                        </label>
                        <select id="Allowed_cities" name="allowed_cities[]" multiple="multiple" class="form-control select" data-fouc>
                            @foreach(get_cites() as $city)
                                <option {{(old('allowed_cities') != null and in_array($city['id'],old('allowed_cities'))) ? "selected":""}} value="{{$city['id']}}">{{$city['name']}}</option>
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
                        <input type="text" id="Delivery_delay" name="delivery_delay" value="{{old('delay_day')}}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="Delay_type" class="label-default ">
                            نوع فاصله زمانی
                        </label>
                        <select id="Delay_type" name="delay_type"  class="form-control select" data-fouc>
                            <option {{ old('delay_type') == 'actual_day' ? "selected":""}} value="actual_day">روز واقعی</option>
                            <option {{ old('delay_type') == 'working_day' ? "selected":""}} value="working_day">روز کاری</option>
                        </select>

                    </div>
                    <div class="col-md-3">
                        <label for="Status" class="label-default ">
                            وضعیت
                        </label>
                        <select id="Status" name="status" class="form-control select" data-fouc>
                            <option {{  old('status') == 1 ? "selected":""}} value="1">
                                فعال (نمایش داده شود)
                            </option>
                            <option {{  old('status') == 0 ? "selected":""}} value="0">
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
@endsection