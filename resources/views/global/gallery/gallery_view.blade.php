@extends('layouts.global.global_layout')
@section('css')
    <link rel="stylesheet" href="{{URL::asset('/public/assets/global/js/fancybox/dist/jquery.fancybox.min.css')}}"
          type="text/css" media="screen"/>
    <style>
        .btn:focus, .btn:active, button:focus, button:active {
            outline: none !important;
            box-shadow: none !important;
        }

        #image-gallery .modal-footer {
            display: block;
        }

        .thumb {
            margin-top: 15px;
            margin-bottom: 15px;
        }
        .round-img{
            border-radius: 5px;
            border: initial !important;
            box-shadow: 0 1px 15px rgba(0, 0, 0, .14), 0 1px 6px rgba(0, 0, 0, .14) !important;
        }
        .bg-img{
            background-image: url("{{url($pics[0]['url'])}}");
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
@stop
@section('content')
    <div class="main-content">
        <section class="bg-white-f7 pt-20 bg-img" >
            <div class="container pb-0">
                <div class="section-title">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <div class="text-center">
                                <h3 class="sub-title">{{$categoryInfo['title']}}</h3>
                                <hr>
                                <p>{!! $categoryInfo['more_description'] !!}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="container">
                <div class="section-content">
                    <div class="row">
                        @foreach($pics as $pic)
                            <div class="col-lg-3 col-md-4 col-xs-6 thumb" >
                                <a href="{{url($pic['url'])}}" data-fancybox="images"
                                   data-caption="{{$pic['title']}}" >
                                    <img class="round-img" src="{{url($pic['path']."/600/".$pic['name'])}}" alt="{{$pic['title']}} - {{__('messages.ashraf')}}" />
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('js')
    <script src="{{URL::asset('/public/assets/global/js/fancybox/dist/jquery.fancybox.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('[data-fancybox="images"]').fancybox({
                closeExisting: false,
                gutter: 50,
                keyboard: true,
                arrows: true,
                protect: true,
                image: {
                    preload: true
                },
                buttons: [
                    "zoom",
                    "slideShow",
                    "fullScreen",
                    "thumbs",
                    "close"
                ],
                thumbs: {
                    autoStart: true
                },
                zoomOpacity: "auto",

                afterLoad: function (instance, current) {
                    var pixelRatio = window.devicePixelRatio || 1;

                    if (pixelRatio > 1.5) {
                        current.width = current.width / pixelRatio;
                        current.height = current.height / pixelRatio;
                    }
                }
            })
        });
    </script>
@stop
