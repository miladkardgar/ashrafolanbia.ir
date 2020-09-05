<?php
$active_sidbare = ['c_store','c_store_list']
?>

@extends('layouts.panel.panel_layout')
@section('js')

@endsection
@section('css')

@endsection
@section('content')

    <div class="content">
        <div class="card">
            <div class="card-header header-elements-inline bg-light"><span class="card-title">{{__('لیست محصولات')}}</span>
            <div class="header-elements">

                <a href="{{route('c_store.add_product')}}" class="btn  btn-warning btn-sm ">
                    <i class="icon-plus2"></i> افزودن
                </a>
            </div>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>{{__('messages.title')}}</th>
                        <th>{{__('messages.price')}}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{$product['title']}}</td>
                                <td>{{number_format($product['price'])}}</td>
                                <td>
                                    <a href="{{route('c_store.edit_product',['slug'=>$product['slug']])}}" class="btn btn-sm btn-outline-success ">
                                        <span class="icon-eye"></span>
                                    </a>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection