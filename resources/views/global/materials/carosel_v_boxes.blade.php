<?php
$adv_cards = get_option('adv_card');
?>

<link href="{{ URL::asset('/public/assets/global/css/carosel-v/carosel-v.css') }}" rel="stylesheet" type="text/css">
<style>

    .carousel_v__item {
        display: -webkit-box;
        display: flex;
        -webkit-box-align: center;
        align-items: center;
        position: absolute;
        width: 100%;
        padding: 0 12px;
        opacity: 0;
        -webkit-filter: drop-shadow(0 2px 2px #555);
        filter: drop-shadow(0 2px 2px #555);
        will-change: transform, opacity;
        -webkit-animation: carousel_v-animate-vertical {{$adv_cards->count()*3}}s linear infinite;
        animation: carousel_v-animate-vertical {{$adv_cards->count()*6}}s linear infinite;
    }
    .carousel_v__item:nth-child(1) {
        -webkit-animation-delay: calc(6s * -1);
        animation-delay: calc(6s * -1);
    }
    @for($i = 0 ; $i <$adv_cards->count()-2 ;$i++)

        .carousel_v__item:nth-child({{$i+2}}) {
        -webkit-animation-delay: calc(6s * {{$i}});
        animation-delay: calc(6s * {{$i}});
    }

    @endfor

    .carousel_v__item:last-child {
        -webkit-animation-delay: calc(-6s * {{$adv_cards->count()-2}});
        animation-delay: calc(-6s * {{$adv_cards->count()-2}});
    }


</style>

<div class='carousel_v_wrapper hidden-sm hidden-xs'>
    <div class='carousel_v'>
        @forelse($adv_cards as $adv_card)

            <div class='carousel_v__item'>

                <a href="{{json_decode($adv_card['value'],true)['link']}}" class='carousel_v__item-body' style="padding-top: 33%;background-size: cover;background-image: url('{{URL::asset(json_decode($adv_card['value'],true)['image'])}}')">

                </a>
            </div>

        @empty

        <div class='carousel_v__item'>
            <div class='carousel_v__item-head'>
                🐋
            </div>
            <div class='carousel_v__item-body'>
                <p class='title'>whale</p>
                <p>Unicode: U+1F40B</p>
            </div>
        </div>
        @endforelse
    </div>
</div>
