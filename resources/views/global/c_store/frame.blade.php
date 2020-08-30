@extends('layouts.global.global_layout')
@section('title','تابلو و تاج گل'. " |")

@section('css')
    <link href="{{ URL::asset('/public/assets/global/css/mrn/style.css') }}?i=2" rel="stylesheet" type="text/css">

    @yield('css2')
@endsection
@section('content')

    @yield('mrn-content')

@endsection
@section('js')
    <script>
        function product_gallery(imgs) {
            var expandImg = document.getElementById("expandedImg");
            expandImg.src = imgs.src;
            expandImg.parentElement.style.display = "block";
        }
    </script>

    <script >
        $(document).ready(function () {
            $(document).on("keyup", '.amount', function (event) {
                $(this).val(function (index, value) {
                    return value
                        .replace(/\D/g, "")
                        .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                });
                $('.flexslider').flexslider({
                    animation: "slide"
                });
            });
        });


    </script>

    @yield('js2')
@endsection
