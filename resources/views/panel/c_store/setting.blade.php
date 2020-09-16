<?php
$active_sidbare = ['c_store', 'c_store_setting']
?>

@extends('layouts.panel.panel_layout')
@section('js')
    <script src="{{ URL::asset('/public/assets/panel/global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ URL::asset('/public/assets/panel/global_assets/js/plugins/forms/tags/tokenfield.min.js') }}"></script>
    <script src="{{URL::asset('/public/assets/panel/js/ckeditor/ckeditor.js')}}"></script>

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

@endsection
@section('content')

    <div class="content">
        <form action="" method="post" class="">
            @csrf
            <div class="row card ">
                <!-- phones text input -->
                <div class="form-group col-md-6">
                    <label for="Phones">شماره هایی که پس از خرید به آنها اطلاع داده میشود <span
                                class="text-danger">*</span></label>
                    <input type="text"
                           class="form-control form-control tokenfield"
                           name="phones" id="Phones" value="{{$phones}}">
                </div>
            </div>
            <div class="row card">
                <!-- time text input -->
                <div class="form-group col-md-4">
                    <label for="Time">ساعت پایان روز کاری (سفارش ها بعد از این ساعت مثل سفارش های روز بعد است) <span
                                class="text-danger">*</span></label>
                    <input type="time" class="form-control " name="time" id="Time" value="{{$end_time}}">
                </div>
            </div>
            <div class="row card ">
                <!-- time text input -->
                <div class="form-group col-md-6">
                    <label for="Transport_price">هزینه حمل</label>
                    <input type="text" class="form-control amount"
                           name="transport_price" id="Transport_price" value="{{number_format($transport_price)}}">
                </div>
                <div class="form-group col-md-6">
                    <label for="Free_provinces" class="label-default ">استان های رایگان</label>
                    <select id="Free_provinces" name="free_provinces[]" multiple="multiple" class="form-control select"
                            data-fouc>
                        @foreach(get_provinces() as $province)
                            <option {{(!empty($free_provinces) and in_array($province['id'],$free_provinces)) ? "selected":""}} value="{{$province['id']}}">{{$province['name']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="Free_cities" class="label-default ">شهر های رایگان
                    </label>
                    <select id="Free_cities" name="free_cities[]" multiple="multiple" class="form-control select"
                            data-fouc>
                        @foreach(get_cites() as $city)
                            <option {{(!empty($free_cities)  and in_array($city['id'],$free_cities)) ? "selected":""}} value="{{$city['id']}}">{{$city['name']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="Transport_free">رایگان اگر از این مبلغ بیشتر باشد</label>
                    <input type="text" class="form-control amount" name="transport_free" id="Transport_free"
                           value="{{number_format($transport_free)}}">
                </div>
            </div>
            <div class="row card">
                <!-- time text input -->
                <div class="form-group col-md-12">
                    <label for="Time">توضیحات صفحه اصلی </label>
                    <textarea name="description" id="Description"  rows="10" class="form-control">{!!$description!!}</textarea>
                </div>
            </div>

            <div class="row card p-1">
                
                <div class="col-md-6">
                    <button class="btn btn-outline-warning" type="submit"> ذخیره</button>
                </div>
            </div>
        </form>
    </div>

@endsection