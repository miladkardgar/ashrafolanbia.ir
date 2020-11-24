@extends('errors::illustrated-layout')

@section('title', __('درخواست بیش از حد |'))
@section('code', '429')
@section('message', __('تعداد درخواست ارسال شده بیش از حد تایین شده است، لطفا پس از چند دقیقه مجدد امتحان کنید'))
