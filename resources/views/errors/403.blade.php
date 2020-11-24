@extends('errors::illustrated-layout')

@section('title', __('ممنوع |'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'ورود به این بخش محدود شده است '))
