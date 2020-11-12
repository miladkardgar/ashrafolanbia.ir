@extends('layouts.panel.panel_layout')
<?php
$active_sidbare = ['charity', 'charity_setting', 'charity_titles']
?>
@section('content')
    <section>
        <div class="content">
            <section>
                <div class="card">
                    <form method="POST" id=""
                          action="{{route('charity_payment_title_add')}}"
                          autocomplete="off">
                        @csrf

                        <div class="row">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="title"
                                           class=" col-form-label text-md-right">{{ __('messages.title') }}</label>
                                    <div class="input-group-btn">
                                        <input id="title" type="text" class=" form-control"
                                               name="title"
                                               value="">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <button type="submit" class="btn  btn-warning">
                                        {{ __('messages.add') }} <i class="icon-arrow-left5"></i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </section>
            <section>
                <div class="card">
                <table class="table">
                    <tr>
                        <th>عنوان</th>
                    </tr>
                    @foreach($titles as $title)
                    <tr>
                        <td>{{$title['title']}}</td>
                    </tr>
                    @endforeach
                </table>
                </div>
            </section>
        </div>
    </section>
@stop