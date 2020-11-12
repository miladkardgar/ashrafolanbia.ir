<?php
$adv_cards = get_option('adv_card');
?>

<link href="{{ URL::asset('/public/assets/global/css/carosel-v/carosel-v.css') }}" rel="stylesheet" type="text/css">
{{--<style>--}}

{{--    .carousel_v__item {--}}
{{--        display: -webkit-box;--}}
{{--        display: flex;--}}
{{--        -webkit-box-align: center;--}}
{{--        align-items: center;--}}
{{--        position: absolute;--}}
{{--        width: 100%;--}}
{{--        padding: 0 12px;--}}
{{--        opacity: 0;--}}
{{--        -webkit-filter: drop-shadow(0 2px 2px #555);--}}
{{--        filter: drop-shadow(0 2px 2px #555);--}}
{{--        will-change: transform, opacity;--}}
{{--        -webkit-animation: carousel_v-animate-vertical {{$adv_cards->count()*3}}s linear infinite;--}}
{{--        animation: carousel_v-animate-vertical {{$adv_cards->count()*6}}s linear infinite;--}}
{{--    }--}}
{{--    .carousel_v__item:nth-child(1) {--}}
{{--        -webkit-animation-delay: calc(6s * 0);--}}
{{--        animation-delay: calc(6s * 0);--}}
{{--    }--}}
{{--    @for($i = 1 ; $i <$adv_cards->count()-1 ;$i++)--}}

{{--        .carousel_v__item:nth-child({{$i+1}}) {--}}
{{--        -webkit-animation-delay: calc(6s * {{$i}});--}}
{{--        animation-delay: calc(6s * {{$i}});--}}
{{--    }--}}

{{--    @endfor--}}

{{--    .carousel_v__item:last-child {--}}
{{--        -webkit-animation-delay: calc(6s * {{$adv_cards->count()-1}});--}}
{{--        animation-delay: calc(6s * {{$adv_cards->count()-1}});--}}
{{--    }--}}


{{--</style>--}}

<div class="row equal-height-inner hidden-sm hidden-xs home-boxes">
    @forelse(get_option('adv_card') as $adv_card)
        <div class="col-md-12 p-0 sm-height-auto wow fadeInUp" data-wow-duration="1.2s" data-wow-delay="0.3s">
            <a href="{{json_decode($adv_card['value'],true)['link']}}" >
                <div class="sm-height-auto p-5 rounded" style="text-align: center">
                    <img class="img-absolute-parent" src="{{URL::asset(json_decode($adv_card['value'],true)['image'])}}" style="max-width: 290px; border-radius: 8px">
                </div>
            </a>
        </div>
@empty
    @endforelse
</div>

{{--<div class='carousel_v_wrapper hidden-sm hidden-xs'>--}}
{{--    <div class='carousel_v'>--}}
{{--        @forelse($adv_cards as $adv_card)--}}

{{--            <div class='carousel_v__item'>--}}
{{--                <a href="{{json_decode($adv_card['value'],true)['link']}}" class='carousel_v__item-body'--}}
{{--                   style="padding-top: 34%;background-size: cover;background-image: url('{{URL::asset(json_decode($adv_card['value'],true)['image'])}}')!important;">--}}
{{--                </a>--}}
{{--            </div>--}}

{{--        @empty--}}

{{--        <div class='carousel_v__item'>--}}
{{--            <div class='carousel_v__item-head'>--}}
{{--                🐋--}}
{{--            </div>--}}
{{--            <div class='carousel_v__item-body'>--}}
{{--                <p class='title'>whale</p>--}}
{{--                <p>Unicode: U+1F40B</p>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        @endforelse--}}
{{--    </div>--}}
{{--</div>--}}
