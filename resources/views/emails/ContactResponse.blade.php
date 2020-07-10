@component('mail::message')
    <div dir="rtl" style="font-family: Tahoma!important;">
    @isset($message)
        {!! $message  !!}
    @endisset
    <br>
    <br>
        <hr>
    {{ __('site_info.web_title') }}
    </div>
@endcomponent
