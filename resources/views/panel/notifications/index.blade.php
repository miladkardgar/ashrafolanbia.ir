@extends('layouts.panel.panel_layout')

@section('content')
    <?php
    $active_sidbare = ['blog', 'notifications']
    ?>
    <!-- Content area -->
    <div class="content">
        <!-- Single line -->
        <div class="card">
            <div class="card-header bg-indigo">
                <span class="card-title font-size-lg">{{__('لیست اطلاعیه ها')}}</span>

                <div class="header-elements-inline float-right">

                    <a class="btn btn-light m-0" href="{{route('notifications.new')}}"><i class="icon-pencil7"></i> <span
                                class="d-none d-lg-inline-block ml-2">{{__('ثبت اطلاعیه جدید')}}</span></a>
                </div>
            </div>

            <div class="card-body">

            <table class="table table-columned ">
                <thead>
                <tr>
                    <th></th>
                    <th>{{__('messages.title')}}</th>
                    <th>{{__('تاریخ شروع')}}</th>
                    <th>{{__('تاریخ پایان')}}</th>
                    <th>{{__('وضعیت')}}</th>
                    <th>{{__('مشاهده/ویرایش')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($notifications as $notification)
                    <tr>
                        <?php
                        $status = (strtotime(date('Y-m-d H:i:s')) >= strtotime($notification['start']) and  strtotime(date('Y-m-d H:i:s')) <= strtotime($notification['end']))
                        ?>
                        <td></td>
                        <td>{{$notification['title']}}</td>
                        <td>{{miladi_to_shamsi_date($notification['start'])}}</td>
                        <td>{{miladi_to_shamsi_date($notification['end'])}}</td>
                        <td>{{$status ? "در حال نمایش":"غیر فعال"}}</td>
                        <td>
                            <a href="{{route('notifications.edit',$notification['id'])}}" class="btn btn-circled btn-outline-success">
                                <span class="icon-pencil"></span>
                            </a>
                            <button
                                    class="legitRipple swal-alert float-right btn alpha-pink border-pink-400 text-pink-800 btn-icon rounded-round ml-2"
                                    data-ajax-link="{{route('notifications.delete',['id'=>$notification['id']])}}"
                                    data-method="delete"
                                    data-csrf="{{csrf_token()}}"
                                    data-title="{{trans('messages.delete_item',['item'=>trans('اعلان')])}}"
                                    data-text="{{trans('messages.delete_item_text',['item'=>trans('اعلان')])}}"
                                    data-type="warning"
                                    data-cancel="true"
                                    data-confirm-text="{{trans('messages.delete')}}"
                                    data-cancel-text="{{trans('messages.cancel')}}">
                                <i class="icon-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>


        </div>
        </div>
    </div>
    <!-- /content area -->

@endsection
