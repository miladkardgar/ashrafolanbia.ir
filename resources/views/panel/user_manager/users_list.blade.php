@extends('layouts.panel.panel_layout')
<?php
$active_sidbare = ['user_manager', 'users_list']
?>
@section('js')
    <script src="{{ URL::asset('/public/assets/panel/global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/public/assets/panel/global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script>
        var DatatableBasic = function () {
            var _componentDatatableBasic = function () {
                if (!$().DataTable) {
                    console.warn('Warning - datatables.min.js is not loaded.');
                    return;
                }
                $('.datatable-basic').DataTable({
                    autoWidth: false,
                    columnDefs: [{
                        orderable: false,
                        width: 100,
                        targets: [4]
                    }],
                    order: [0, 'desc'],
                    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
                    language: {
                        search: '<span>{{__('messages.filter')}}:</span> _INPUT_',
                        searchPlaceholder: '{{__('messages.search')}}...',
                        lengthMenu: '<span>{{__('messages.show')}}:</span> _MENU_',
                        paginate: {
                            'first': '{{__('messages.first')}}',
                            'last': '{{__('messages.last')}}',
                            'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;',
                            'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;'
                        }
                    }
                });
                $('.sidebar-control').on('click', function () {
                    table.columns.adjust().draw();
                });
            }
            var _componentSelect2 = function () {
                if (!$().select2) {
                    console.warn('Warning - select2.min.js is not loaded.');
                    return;
                }
                $('.dataTables_length select').select2({
                    minimumResultsForSearch: Infinity,
                    dropdownAutoWidth: true,
                    width: 'auto'
                });
            };
            return {
                init: function () {
                    _componentDatatableBasic();
                    _componentSelect2();
                }
            }
        }();
        document.addEventListener('DOMContentLoaded', function () {
            DatatableBasic.init();
        });
    </script>
@endsection
@section('content')
    <section>
        <div class="content">
            <div class="container">
                <section>
                    <div class="row text-center">
                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-body bg-orange-400 has-bg-image" id="all_users">
                                <div class="media">
                                    <div class="media-body text-left">
                                        <h3 class="mb-0">{{number_format($active_users+$inactive_users)}}  </h3>
                                        <span class="text-uppercase font-size-xs">تعداد کل کاربران</span>
                                    </div>
                                    <div class="mr-3 align-self-center">
                                        <i class="icon-users icon-3x opacity-75"></i>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-body bg-success-400 has-bg-image" id="active_users">
                                <div class="media">
                                    <div class="media-body text-left">
                                        <h3 class="mb-0">{{number_format($active_users)}}  </h3>
                                        <span class="text-uppercase font-size-xs">کاربران فعال</span>
                                    </div>
                                    <div class="mr-3 align-self-center">
                                        <i class="icon-users icon-3x opacity-75"></i>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-body bg-danger-400 has-bg-image" id="inactive_users">
                                <div class="media">
                                    <div class="media-body text-left">
                                        <h3 class="mb-0">{{number_format($inactive_users)}}  </h3>
                                        <span class="text-uppercase font-size-xs">کاربران غیرفعال</span>
                                    </div>
                                    <div class="mr-3 align-self-center">
                                        <i class="icon-users icon-3x opacity-75"></i>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-body bg-info has-bg-image" id="admin_users">
                                <div class="media">
                                    <div class="media-body text-left">
                                        <h3 class="mb-0">{{number_format($admin_users)}}  </h3>
                                        <span class="text-uppercase font-size-xs">کاربران ادمین</span>
                                    </div>
                                    <div class="mr-3 align-self-center">
                                        <i class="icon-user-tie icon-3x opacity-75"></i>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    <form action="#">
                                        <div class="input-group mb-3">
                                            <div class="form-group-feedback form-group-feedback-left">
                                                <input type="text" id="t_search_q"
                                                       class="form-control form-control-lg alpha-grey"
                                                       placeholder="جستجو در کاربران">
                                                <div class="form-control-feedback form-control-feedback-lg">
                                                    <i class="icon-search4 text-muted"></i>
                                                </div>
                                            </div>

                                            <div class="input-group-append">
                                                <button type="button" id="t_search" class="btn btn-primary btn-lg">
                                                    جستجو
                                                </button>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                                <div class="col-md-5">

                                    @if($type)

                                        <span class="badge badge-info">
                                        {{$type}}
                                        </span>
                                    @endif
                                    @if($query)
                                        نتایج جستجو:
                                        <span class="badge badge-light">
                                        {{$query}}
                                        </span>
                                        <p class="text-info">
                                            {{number_format($count)}}
                                            مورد یافت شد
                                        </p>
                                    @endif

                                    @if($query or $type)
                                            <a href="{{route('users_list')}}" class="text-black-50 ">
                                        <span class="badge badge-danger">
                                            حذف فیلتر ها
                                            </span>
                                            </a>

                                        @endif

                                </div>
                                <div class="col-md-2">

                                    <button type="button" class="btn btn-success float-right modal-ajax-load"
                                            data-ajax-link="{{route('panel_register_form')}}" data-toggle="modal"
                                            data-modal-title="{{trans('messages.add_new_user')}}"
                                            data-target="#general_modal">
                                        <i class="icon-user-plus mr-2"></i> {{trans('messages.add_new_user')}}
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>

                </section>
                <section>
                    <div class="card">
                        <div class="card-body table-responsive">
                            <table class="table ">
                                <thead>
                                <tr>
                                    <th>{{__('messages.id')}}</th>
                                    <th>{{__('messages.username')}}</th>
                                    <th>{{__('messages.name')}}</th>
                                    <th>{{__('messages.email')}}</th>
                                    <th>{{__('messages.mobile')}}</th>
                                    <th>{{__('messages.register_date')}}</th>
                                    <th>{{__('messages.status')}}</th>
                                    <th class="text-center">{{__('messages.action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i = 1;
                                ?>
                                @foreach($users as $user)
                                    <tr>
                                        <td><b>{{$i}}</b></td>
                                        <td><b>{{$user['name']}}</b></td>
                                        <td><b>{{$user['people']['name']}} {{$user['people']['family']}}</b></td>
                                        <td><b>{{$user['email']}}</b></td>
                                        <td><b>{{$user['phone']}}</b></td>
                                        <td>
                                            @if($user['created_at'])
                                                <span dir="ltr">{{jdate("Y-m-d H:i",strtotime($user['created_at']))}}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user['disabled']==1)
                                                <span class="badge badge-danger">{{__('messages.inactive')}}</span>
                                            @else
                                                <span class="badge badge-success">{{__('messages.active')}}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a class="btn btn-outline-dark btn-sm"
                                                   href="{{route('user_permission_assign_page',['user_id'=>$user->id])}}"
                                                   data-toggle="tooltip" data-placement="top"
                                                   title="{{__('messages.permissions')}}"
                                                >
                                                    <i class="fa fa-key"></i></a>
                                                <a class="btn btn-outline-dark btn-sm"
                                                   href="{{route('users_list_info_edit',['user'=>$user->id])}}"
                                                   data-toggle="tooltip" data-placement="top"
                                                   title="{{__('messages.edit')}}"
                                                >
                                                    <i class="fa fa-edit"></i></a>
                                                @if($user['disabled']==0)
                                                    <button type="button"
                                                            class="btn btn-outline-danger btn-sm swal-alert"
                                                            data-ajax-link="{{route('users_list_delete',['id'=>$user->id])}}"
                                                            data-method="POST"
                                                            data-csrf="{{csrf_token()}}"
                                                            data-title="{{trans('messages.delete_item',['item'=>__('messages.user')])}}"
                                                            data-text="{{trans('messages.approve',['item'=>trans('messages.user')])}}"
                                                            data-type="warning"
                                                            data-cancel="true"
                                                            data-toggle="tooltip" data-placement="top"
                                                            title="{{__('messages.inactivate')}}"
                                                            data-confirm-text="{{trans('messages.delete')}}"
                                                            data-cancel-text="{{trans('messages.cancel')}}">
                                                        <i class="fa fa-remove"></i>
                                                    </button>
                                                @else
                                                    <button type="button"
                                                            class="btn btn-outline-success btn-sm swal-alert"
                                                            data-ajax-link="{{route('users_list_delete',['id'=>$user->id])}}"
                                                            data-method="POST"
                                                            data-csrf="{{csrf_token()}}"
                                                            data-title="{{trans('messages.active',['item'=>__('messages.user')])}}"
                                                            data-text="{{trans('messages.approve',['item'=>trans('messages.user')])}}"
                                                            data-type="warning"
                                                            data-cancel="true"
                                                            data-toggle="tooltip" data-placement="top"
                                                            title="{{__('messages.activate')}}"
                                                            data-confirm-text="{{trans('messages.active')}}"
                                                            data-cancel-text="{{trans('messages.cancel')}}">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                @endif
                                                @permission('User_manager_delete_force')
                                                <button type="button"
                                                        class="btn btn-danger btn-sm swal-alert"
                                                        data-ajax-link="{{route('users_list_delete_force',['id'=>$user->id])}}"
                                                        data-method="POST"
                                                        data-csrf="{{csrf_token()}}"
                                                        data-title="{{trans('messages.delete',['item'=>__('messages.user')])}}"
                                                        data-text="{{trans('messages.delete',['item'=>trans('messages.user')])}}"
                                                        data-type="warning"
                                                        data-cancel="true"
                                                        data-toggle="tooltip" data-placement="top"
                                                        title="{{trans('messages.delete',['item'=>__('messages.user')])}}"
                                                        data-confirm-text="{{trans('messages.delete')}}"
                                                        data-cancel-text="{{trans('messages.cancel')}}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                                @endpermission
                                                @permission('manage_charity')
                                                <a href="{{route('charity_periods_show',['user_id'=>$user['id'],'id'=>999999])}}"
                                                   class="btn btn-success btn-sm">
                                                    <i class="icon-heart6"></i>
                                                </a>
                                                @endpermission

                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                    $i++;
                                    ?>
                                @endforeach
                                </tbody>
                            </table>
                            {{$users->appends(request()->except('page'))->links()}}

                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
@endsection
@section('footer_js')
    <script>

        $(document).on('click', '#t_search', function () {
            var url = new URL(window.location.href);

            var search_params = url.searchParams;
            let param = 'q';
            let value = document.getElementById('t_search_q').value;
            search_params.set(param, value);
            url.search = search_params.toString();
            var new_url = url.toString();
            window.location.replace(new_url);
        });
        $(document).on('click', '#active_users', function () {
            var url = new URL(window.location.href);

            var search_params = url.searchParams;
            let param = 'type';
            let value = 'active';
            search_params.set(param, value);
            url.search = search_params.toString();
            var new_url = url.toString();
            window.location.replace(new_url);
        });
        $(document).on('click', '#inactive_users', function () {
            var url = new URL(window.location.href);

            var search_params = url.searchParams;
            let param = 'type'
            let value = 'inactive';
            search_params.set(param, value);
            url.search = search_params.toString();
            var new_url = url.toString();
            window.location.replace(new_url);
        });
        $(document).on('click', '#admin_users', function () {
            var url = new URL(window.location.href);

            var search_params = url.searchParams;
            let param = 'type'
            let value = 'admin';
            search_params.set(param, value);
            url.search = search_params.toString();
            var new_url = url.toString();
            window.location.replace(new_url);
        });
        $(document).on('click', '#all_users', function () {
            var url = new URL(window.location.href);

            var search_params = url.searchParams;
            let param = 'type';
            let value = '';
            search_params.set(param, value);
            url.search = search_params.toString();
            var new_url = url.toString();
            window.location.replace(new_url);
        });

    </script>
@stop
