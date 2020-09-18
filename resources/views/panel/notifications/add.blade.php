@extends('layouts.panel.panel_layout')

@section('js')
    <script src="{{ URL::asset('/node_modules/md.bootstrappersiandatetimepicker/src/jquery.md.bootstrap.datetimepicker.js') }}"></script>
    <script src="{{URL::asset('/public/assets/panel/js/ckeditor/ckeditor.js')}}"></script>
    <script>
        $(document).ready(function () {

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
    </script>

@endsection
@section('css')
    <link href="{{ URL::asset('/node_modules/md.bootstrappersiandatetimepicker/src/jquery.md.bootstrap.datetimepicker.style.css') }}"
          rel="stylesheet" type="text/css">
@endsection

@section('content')
    <!-- Content area -->
    <div class="content">
        <div class="card">
            <div class="card-header"></div>
        <div class="card-body">
            <form action="{{route('notifications.save')}}" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="Title" class="label-default">عنوان</label>
                            <input type="text" id="Title" name="title" value="{{old('title') }} " class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="Start_time" class="label-default">تاریخ شروع</label>
                            <input type="text" id="Start_time" name="start_time" class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="End_time" class="label-default">تاریخ پایان</label>
                            <input type="text" id="End_time" name="end_time" class="form-control" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="Description" class="label-default">متن اصلی</label>
                        <textarea name="description"  id="Description" cols="30" class="form-control">{!! old('description') !!}</textarea>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-success">ذخیره</button>
                    </div>
                </div>

            </form>
        </div>
        </div>
    </div>

@endsection

@section('footer_js')
    <script>
        $('#Start_time').MdPersianDateTimePicker({
            targetTextSelector: $("#Start_time"),
            enableTimePicker: true,
        });
        $('#End_time').MdPersianDateTimePicker({
            targetTextSelector: $("#End_time"),
            enableTimePicker: true,
        });
    </script>
@endsection
