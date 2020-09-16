@extends("blog.blogetc_admin.layouts.admin_layout")
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
                    pagingType: "simple",
                    order:[[0,'desc']],
                    language: {
                        paginate: {
                            'next': $('html').attr('dir') == 'rtl' ? '{{__('messages.next')}} &larr;' : '{{__('messages.next')}} &rarr;',
                            'previous': $('html').attr('dir') == 'rtl' ? '&rarr; {{__('messages.prev')}}' : '&larr; {{__('messages.prev')}}'
                        }
                    },
                    stateSave: true,
                    autoWidth: true,
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
@stop
<?php
$active_sidbare = ['blog', 'blog_posts', 'blog_posts_list']
?>
@section("content")
    <section>
        <div class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="mb-3">جستجو پیشرفته</h5>

                                <form action="#">
                                    <div class="input-group mb-3">
                                        <div class="form-group-feedback form-group-feedback-left">
                                            <input type="text" id="t_search_q" class="form-control form-control-lg alpha-grey"
                                                   placeholder="همه یا قسمتی از عنوان">
                                            <div class="form-control-feedback form-control-feedback-lg">
                                                <i class="icon-search4 text-muted"></i>
                                            </div>
                                        </div>

                                        <div class="input-group-append">
                                            <button type="button" id="t_search" class="btn btn-primary btn-lg">جستجو</button>
                                        </div>
                                    </div>

                                    <div class="d-md-flex align-items-md-center flex-md-wrap text-center text-md-left">
                                        <ul class="list-inline list-inline-condensed mb-0">
                                            <li class="list-inline-item ">
                                                <a href="#" id="t_search_reset" class=" btn btn-link text-default "
                                                >
                                                    <i class="icon-reset mr-2"></i>
                                                    حذف فیلتر ها
                                                </a>
                                            </li>
                                            <li class="list-inline-item dropdown">
                                                <a href="#" class="btn btn-link text-default dropdown-toggle"
                                                   data-toggle="dropdown">
                                                    <i class="icon-stack2 mr-2"></i>
                                                    دسته بندی:
                                                </a>

                                                <div class="dropdown-menu">
                                                    @foreach(\WebDevEtc\BlogEtc\Models\BlogEtcCategory::orderBy("category_name","asc")->get() as $category)
                                                        <a href="#" data-param="cat"
                                                           data-value="{{$category->id}}" class="dropdown-item t_filter">{{$category->category_name}}</a>
                                                    @endforeach
                                                </div>
                                            </li>
                                            <li class="list-inline-item dropdown">
                                                <a href="#" class="btn btn-link text-default dropdown-toggle"
                                                   data-toggle="dropdown">
                                                    <i class="icon-warning mr-2"></i>
                                                    صفحات خاص:
                                                </a>

                                                <div class="dropdown-menu">
                                                    @foreach(\WebDevEtc\BlogEtc\Models\BlogEtcSpecificPages::orderBy("category_name","asc")->get() as $category)
                                                        <a href="#" data-param="sp"
                                                           data-value="{{$category->id}}" class="dropdown-item t_filter">{{$category->category_name}}</a>
                                                    @endforeach

                                                </div>
                                            </li>

                                        </ul>

                                    </div>
                                </form>
                            </div>
                        </div>
                        @if(sizeof($posts)>=1)

                        <div class="card ">
                            <div class="card-header bg-light">
                                <span class="card-title">{{__('messages.post_list')}}</span>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table ">
                                    <thead class="fullwidth">
                                    <tr>
                                        <th>{{__('messages.id')}}</th>
                                        <th>{{__('messages.title')}}</th>
                                        <th>{{__('messages.author')}}</th>
                                        <th>{{__('messages.posted_at')}}</th>
                                        <th>{{__('messages.Categories')}}</th>
                                        <th>{{__('messages.action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $i=1+(isset($_REQUEST['page']) ? ($_REQUEST['page']-1) *100 :0); @endphp
                                    @forelse($posts as $post)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td><a href='{{$post->url()}}'>{!! substr($post->title,'0',100) !!}</a>
                                                <span class="badge badge-danger">{!!($post->is_published ? "" : "(".__('messages.draft').")")!!}</span>
                                            </td>
                                            <td>{{$post->author_string()}}</td>
                                            <td>{{miladi_to_shamsi_date($post->posted_at)}}</td>
                                            <td>
                                                @if(count($post->categories))
                                                    @foreach($post->categories as $category)
                                                        <a class='btn badge badge-primary btn-sm m-1'
                                                           href='{{$category->edit_url()}}'>
                                                            <small>{{$category->category_name}}</small>
                                                        </a>
                                                    @endforeach
                                                @endif

                                                @if(count($post->specificPage))
                                                    @foreach($post->specificPage as $specific)
                                                        <a class='btn badge badge-warning btn-sm m-1'
                                                           href='{{$specific->edit_url()}}'>
                                                            <small>{{$specific->category_name}}</small>
                                                        </a>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href='{{route('post_page',$post->slug)}}' target="_blank"
                                                       class="float-right btn alpha-success border-success-400 text-success-800 btn-icon rounded-round ml-2">
                                                        <i class="icon-eye8"></i>
                                                    </a>
                                                    <a href="{{$post->edit_url()}}"
                                                       class="float-right btn alpha-info border-info-400 text-info-800 btn-icon rounded-round ml-2">
                                                        <i class="icon-pencil"></i>
                                                    </a>
                                                    <button type="submit"
                                                            class="legitRipple  float-right btn alpha-pink border-pink-400 text-pink-800 btn-icon rounded-round ml-2 swal-alert "
                                                            data-ajax-link="{{route("blogetc.admin.destroy_post", $post->id)}}"
                                                            data-method="delete"
                                                            data-csrf="{{csrf_token()}}"
                                                            data-title="{{trans('messages.delete_item',['item'=>trans('messages.post')])}}"
                                                            data-text="{{trans('messages.delete_item_text',['item'=>trans('messages.post')])}}"
                                                            data-type="warning"
                                                            data-cancel="true"
                                                            data-confirm-text="{{trans('messages.delete')}}"
                                                            data-cancel-text="{{trans('messages.cancel')}}">
                                                        <i class="icon-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @php $i++; @endphp
                                    @empty
                                        <tr></tr>
                                    @endforelse
                                    </tbody>
                                </table>
                                <div class='text-center'>
                                    {{$posts->appends(request()->except('page'))->links()}}
                                </div>
                            </div>
                        </div>

                        @else
                            @include('panel.not_found',['html'=>'<a class="btn btn-primary" href="'.route('blogetc.admin.create_post').'">
                            '.__('messages.new_post').'</a>',
                           'msg'=>__('messages.not_found_any_data'),
                           'des'=>__('messages.please_insert_post')])
                        @endif

                    </div>
                </div>

        </div>
    </section>
@endsection
@section('footer_js')
    <script>
        $(document).on('click', '.t_filter', function () {
            var url = new URL(window.location.href);

            var search_params = url.searchParams;
            let param = $(this).attr('data-param');
            let value = $(this).attr('data-value');

            search_params.set(param, value);
            url.search = search_params.toString();
            var new_url = url.toString();
            window.location.replace(new_url);

        });
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
        $(document).on('click', '#t_search_reset', function () {
            var url = new URL(window.location.href);
            url.search = '';
            var new_url = url.toString();
            window.location.replace(new_url);
        });

    </script>
@stop
