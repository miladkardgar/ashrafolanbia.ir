<!-- Footer -->
<footer id="footer" class="footer" data-bg-img="{{ URL::asset('/public/assets/global/images/footer-bg.png') }}"
        data-bg-color="#25272e">
    <div class="container pt-70 pb-40">
        <div class="row border-bottom-black">
            <div class="col-sm-6 col-md-3">
                <div class="widget dark">

                    <img src="https://trustseal.enamad.ir/logo.aspx?id=17773&amp;p=ClsXF8vecXgifjKa" alt="" onclick="window.open(&quot;https://trustseal.enamad.ir/Verify.aspx?id=17773&amp;p=ClsXF8vecXgifjKa&quot;, &quot;Popup&quot;,&quot;toolbar=no, location=no, statusbar=no, menubar=no, scrollbars=1, resizable=0, width=580, height=600, top=30&quot;)" style="cursor:pointer" id="ClsXF8vecXgifjKa">
                    <img id = 'jxlzwlaoapfuapfuapfufukz' style = 'cursor:pointer' onclick = 'window.open("https://logo.samandehi.ir/Verify.aspx?id=145556&p=rfthaodsdshwdshwdshwgvka", "Popup","toolbar=no, scrollbars=no, location=no, statusbar=no, menubar=no, resizable=0, width=450, height=630, top=30")' alt = 'logo-samandehi' src = 'https://logo.samandehi.ir/logo.aspx?id=145556&p=nbpdshwlujynujynujynwlbq' />


                    {{--                    <h5 class="widget-title line-bottom">Useful Links</h5>--}}
                    {{--                    <ul class="list angle-double-right list-border">--}}
                    {{--                        <li><a href="#">Body Building</a></li>--}}
                    {{--                        <li><a href="#">Fitness Classes</a></li>--}}
                    {{--                        <li><a href="#">Weight lifting</a></li>--}}
                    {{--                        <li><a href="#">Yoga Courses</a></li>--}}
                    {{--                        <li><a href="#">Training</a></li>--}}
                    {{--                    </ul>--}}
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="widget dark">
                    <img class="mt-10 mb-20" alt=""
                         src="{{ URL::asset('/public/assets/global/images/logo-wide@2x.png')}}?i=4">
                    <p>
                        {!!  trans('site_info.address')!!}
                    </p>
                    <ul class="list-inline mt-5">
                        <li class="m-0 pl-10 pr-10"><i class="fa fa-phone text-theme-colored mr-5"></i> <a
                                    class="text-gray" href="#">{{trans('site_info.phone')}}</a></li>
                        <br>
                        <li class="m-0 pl-10 pr-10"><i class="fa fa-fax text-theme-colored mr-5"></i> <a
                                    class="text-gray" href="#">{{trans('site_info.fax')}}</a></li>
                        <li class="m-0 pl-10 pr-10"><i class="fa fa-envelope-o text-theme-colored mr-5"></i> <a
                                    class="text-gray" href="#">{{trans('site_info.email')}}</a></li>
                        <li class="m-0 pl-10 pr-10"><i class="fa fa-globe text-theme-colored mr-5"></i> <a
                                class="text-gray" href="#">{{trans('site_info.site_address')}}</a></li>
                        <li class="m-0 pl-10 pr-10"> <a
                                class="text-gray" href="#">{{trans('messages.sms_box')}}:{{trans('site_info.sms_box')}}</a></li>

                    </ul>

                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="widget dark">
                    <h5 class="widget-title line-bottom">{!! trans('messages.latest_posts') !!}</h5>
                    <div class="latest-posts">
                        @forelse(get_posts(4) as $key=> $news)
                            <article class="post media-post clearfix pb-0 mb-10">
                                <div class="post-right">
                                    <h5 class="post-title mt-0">
                                        <a href="{{route('post_page',['blogPostSlug'=>$news['slug']])}}">
                                            {{$news['title']}}
                                        </a>
                                    </h5>
                                </div>
                            </article>

                        @empty
                            <article class="post media-post clearfix pb-0 mb-10">
                                <a href="#" class="post-thumb"><img alt="" src="http://placehold.it/80x55"></a>
                                <div class="post-right">
                                    <h5 class="post-title mt-0 mb-5"><a href="#">Sustainable Construction</a></h5>
                                    <p class="post-date mb-0 font-12">Mar 08, 2015</p>
                                </div>
                            </article>
                            <article class="post media-post clearfix pb-0 mb-10">
                                <a href="#" class="post-thumb"><img alt="" src="http://placehold.it/80x55"></a>
                                <div class="post-right">
                                    <h5 class="post-title mt-0 mb-5"><a href="#">Industrial Coatings</a></h5>
                                    <p class="post-date mb-0 font-12">Mar 08, 2015</p>
                                </div>
                            </article>
                            <article class="post media-post clearfix pb-0 mb-10">
                                <a href="#" class="post-thumb"><img alt="" src="http://placehold.it/80x55"></a>
                                <div class="post-right">
                                    <h5 class="post-title mt-0 mb-5"><a href="#">Storefront Installations</a></h5>
                                    <p class="post-date mb-0 font-12">Mar 08, 2015</p>
                                </div>
                            </article>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="widget dark">

                    @foreach(config('blog_setting.social_media') as $social_media)
                        <a href="{{$social_media['link']}}" target="_blank" class="m-5" title="{{$social_media['name']}}">
                            <i class="font-size-lg {{$social_media['icon']}} fa-3x"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="row mt-10">
            <div class="col-md-5">
                {{--                <div class="widget dark">--}}
                {{--                    <h5 class="widget-title mb-10">Subscribe Us</h5>--}}
                {{--                    <!-- Mailchimp Subscription Form Starts Here -->--}}
                {{--                    <form id="mailchimp-subscription-form-footer" class="newsletter-form">--}}
                {{--                        <div class="input-group">--}}
                {{--                            <input type="email" value="" name="EMAIL" placeholder="Your Email" class="form-control input-lg font-16" data-height="45px" id="mce-EMAIL-footer" style="height: 45px;">--}}
                {{--                            <span class="input-group-btn">--}}
                {{--                  <button data-height="45px" class="btn btn-colored btn-theme-colored btn-xs m-0 font-14" type="submit">Subscribe</button>--}}
                {{--                </span>--}}
                {{--                        </div>--}}
                {{--                    </form>--}}
                {{--                    <!-- Mailchimp Subscription Form Validation-->--}}
                {{--                    <script type="text/javascript">--}}
                {{--                        $('#mailchimp-subscription-form-footer').ajaxChimp({--}}
                {{--                            callback: mailChimpCallBack,--}}
                {{--                            url: '//thememascot.us9.list-manage.com/subscribe/post?u=a01f440178e35febc8cf4e51f&amp;id=49d6d30e1e'--}}
                {{--                        });--}}

                {{--                        function mailChimpCallBack(resp) {--}}
                {{--                            // Hide any previous response text--}}
                {{--                            var $mailchimpform = $('#mailchimp-subscription-form-footer'),--}}
                {{--                                $response = '';--}}
                {{--                            $mailchimpform.children(".alert").remove();--}}
                {{--                            console.log(resp);--}}
                {{--                            if (resp.result === 'success') {--}}
                {{--                                $response = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' + resp.msg + '</div>';--}}
                {{--                            } else if (resp.result === 'error') {--}}
                {{--                                $response = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' + resp.msg + '</div>';--}}
                {{--                            }--}}
                {{--                            $mailchimpform.prepend($response);--}}
                {{--                        }--}}
                {{--                    </script>--}}
                {{--                    <!-- Mailchimp Subscription Form Ends Here -->--}}
                {{--                </div>--}}
            </div>
            <div class="col-md-3 col-md-offset-1">
                <div class="widget dark">
                    {{--                    <h5 class="widget-title mb-10">{{trans('messages.sms_box')}}</h5>--}}
                    {{--                    <div class="text-gray">--}}
                    {{--                        {{trans('site_info.sms_box')}} <br>--}}
                    {{--                    </div>--}}
                </div>
            </div>
            <div class="col-md-3">
                <div class="widget dark">
                    {{--                    <h5 class="widget-title mb-10">Connect With Us</h5>--}}
                    {{--                    <ul class="styled-icons icon-dark icon-theme-colored icon-circled icon-sm">--}}
                    {{--                        <li><a href="#"><i class="fa fa-facebook"></i></a></li>--}}
                    {{--                        <li><a href="#"><i class="fa fa-twitter"></i></a></li>--}}
                    {{--                        <li><a href="#"><i class="fa fa-skype"></i></a></li>--}}
                    {{--                        <li><a href="#"><i class="fa fa-youtube"></i></a></li>--}}
                    {{--                        <li><a href="#"><i class="fa fa-instagram"></i></a></li>--}}
                    {{--                        <li><a href="#"><i class="fa fa-pinterest"></i></a></li>--}}
                    {{--                    </ul>--}}
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom bg-black-333">
        <div class="container pt-15 pb-10">
            <div class="row">
                <div class="col-md-6">
                    <p class="font-11 text-black-777 m-0">&copy; {{trans('site_info.copyright')}} </p>
                </div>
                <div class="col-md-6 text-right">
                    <div class="widget no-border m-0">
                        <ul class="list-inline sm-text-center mt-5 font-12">
                            <li>
                                <a href="{{route('faq')}}">FAQ</a>
                            </li>
                            <li>|</li>
                            <li>
                                <a href="{{trans('site_info.site_roles_link')}}">{{trans('site_info.site_roles')}}</a>
                            </li>
                            <li>|</li>
                            <li>
                                <a href="{{trans('site_info.criticism_link')}}">{{trans('site_info.criticism')}}</a>
                            </li>
                            <li>|</li>
                            <li>
                                <a href="{{route('blogetc.feed')}}">RSS</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Modal -->
<div id="searchModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">

            <div class="modal-body">
                <form method='get' action='{{route("blogetc.search")}}'
                      id="mailchimp-subscription-form-footer" class=" newsletter-form">
                    <div class="input-group">
                        <input type="text" value="{{\Request::get("s")}}" name="s"
                               placeholder="{{__('messages.search')}}"
                               class="form-control input-sm font-16" data-height="45px"
                               id="mce-EMAIL-footer" style="height: 45px;">
                        <span class="input-group-btn">
                  <button data-height="45px" class="btn btn-colored bg-theme-colored-darker2 btn-xs m-0 font-14"
                          type="submit">
                      <i class="fa fa-search text-white-f6"></i></button>
                </span>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>
<a class="scrollToTop" href="#">
    <img src="{{asset('public/images/side-logo.png')}}" alt="" class="">
{{--    <i class="fa fa-angle-up"></i>--}}
</a>
