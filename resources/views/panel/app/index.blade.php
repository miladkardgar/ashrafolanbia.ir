@extends('layouts.panel.panel_layout')
@section('js')

@endsection
@section('content')
    <?php
    $active_sidbare = ['mobile-app', 'mobile-app-manage']
    ?>
    <!-- Content area -->
    <div class="content">

        <section>
            <div class="card">
                <div class="card-header">
                        <form action="{{route('mobile.save_notification')}}" method="post" class="">
                            {{csrf_field()}}
                            <row>
                                <div class="col-md-12 mb-2">
                                <span class="text-center text-info">
                                    پیامی که در صفحه اول اپلیکیشن نمایش داده میشود.
                                </span>
                                </div>
                                    <div class="col-md-12 mb-2">
                            <textarea name="notification" id=""  rows="3" class="form-control">
                            {{$notification ? $notification->text:""}}
                            </textarea>
                                        <label for="notice_link" class="text-info">لینک ارجاع (اختیاری)</label>
                                        <input type="text" name="link" class="form-control" value="{{isset($notification->link) ? $notification->link:""}}">
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-info"> ثبت </button>

                                </div>

                            </row>
                        </form>
                    <hr>
                </div>
                <div class="card-body">
                    <div class="col-md-12 mb-2">
                                <span class="text-center text-info">
                                   لینک هایی که در صفحه اول اپلیکیشن نمایش داده میشوند
                                </span>
                    </div>
                    <form action="{{route('mobile.save_payment_title')}}" method="post" class="" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <label for="title" class="label label-default">تیتر(کوتاه)*</label>
                                <input type="text" id="title" name="title" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="link" class="label label-default">لینک*</label>
                                <input type="text" id="link" name="link" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="image" class="label label-default">تصویر(نزدیک به مربع)*</label>
                                <input type="file" id="image" name="image" class="form-control">
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12">

                            <button class="btn btn-info">ذخیره</button>
                            </div>
                        </div>

                    </form>
                    <hr>
                        @foreach($links->chunk(3) as $chunk)
                            <div class="row">
                                @foreach($chunk as $link)
                                    <div class="col-md-4">
                                        <div class="card">

                                            <div class="card-img-actions px-1 pt-1">
                                                <img class="card-img img-fluid img-absolute "
                                                     src="{{'/'.$link['image']}}" alt="">
                                                <div class="card-img-actions-overlay  card-img bg-dark-alpha">

                                                </div>
                                            </div>

                                            <div class="card-body">
                                                <h6 class="font-weight-semibold"><b>{{$link['title']}}</b></h6>

                                                <a href="{{$link['link']}}" class="">link</a>

                                                <button type="button"
                                                        class="legitRipple swal-alert float-right btn alpha-pink border-pink-400 text-pink-800 btn-icon rounded-round ml-2"
                                                        data-ajax-link="{{route('mobile.delete_payment_title',['title'=>$link['id']])}}"
                                                        data-method="POST"
                                                        data-csrf="{{csrf_token()}}"
                                                        data-title="حذف لینک!"
                                                        data-text="آیا میخواهید این لینک دیگر در اپلیکیشن نمایش داده نشود؟"
                                                        data-type="warning"
                                                        data-cancel="true"
                                                        data-confirm-text="{{trans('messages.delete')}}"
                                                        data-cancel-text="{{trans('messages.cancel')}}">
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </div>
                                        </div>


                                    </div>
                                @endforeach
                            </div>
                        @endforeach

                </div>
            </div>
        </section>

    </div>
    <!-- /content area -->

@endsection
