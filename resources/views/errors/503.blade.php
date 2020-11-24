@extends('errors::minimal')

@section('title', __('سرویس در دسترس نیست |'))
@section('code', '503')
@section('message', __($exception->getMessage() ?: 'متاسفانه در حال حاظر سایت در دسترس نیست، لطفا پس از چند دقیقه مجدد امتحان کنید'))
