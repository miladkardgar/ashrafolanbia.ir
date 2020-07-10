@extends('layouts.panel.panel_layout')
@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('js')
    <script
        src="{{ URL::asset('/public/assets/panel/global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script
        src="{{ URL::asset('/public/assets/panel/global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script
        src="{{ URL::asset('/public/assets/panel/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js') }}"></script>
    <script>
        var DatatableBasic = function () {
            var _componentDatatableBasic = function () {
                if (!$().DataTable) {
                    console.warn('Warning - datatables.min.js is not loaded.');
                    return;
                }
                $('.datatable-payments2').DataTable({
                    autoWidth: false,
                    columnDefs: [{
                        orderable: false,
                        width: 100,
                        targets: [8]
                    }],
                    "order": [[ 0, "desc" ]],
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'csv',
                            charset: 'utf-8',
                            extension: '.xls',
                            bom: true,
                        }
                    ],                    language: {
                        search: '<span>{{__('messages.filter')}}:</span> _INPUT_',
                        searchPlaceholder: '{{__('messages.search')}}...',
                        lengthMenu: '<span>{{__('messages.show')}}:</span> _MENU_',
                        paginate: {
                            'first': '{{__('messages.first')}}',
                            'last': '{{__('messages.last')}}',
                            'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;',
                            'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;'
                        }
                    }
                });
                $('.sidebar-control').on('click', function () {
                    table.columns.adjust().draw();
                });
            }
            var _componentSelect2 = function () {
                if (!$().select2) {
                    console.warn('Warning - select2.min.js is not loaded.');
                    return;
                }
                $('.dataTables_length select').select2({
                    minimumResultsForSearch: Infinity,
                    dropdownAutoWidth: true,
                    width: 'auto'
                });
            };
            return {
                init: function () {
                    _componentDatatableBasic();
                    _componentSelect2();
                }
            }
        }();
        document.addEventListener('DOMContentLoaded', function () {
            DatatableBasic.init();
            $("body").addClass('sidebar-xs')
        });

    </script>
@endsection
@section('css')
@stop
@php
    $active_sidbare = ['charity', 'charity_list']
@endphp
@section('content')
    <section>
        <div class="content">
            <section>
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title text-black">{{__('messages.Charity')}}
                            | {{__('messages.other_payments')}}</h6>
                        <hr>
                    </div>

                    <div class="card-body">
                        <table class="table datatable-payments2 table-striped">
                            <thead>
                            <tr>
                                <th>{{__('messages.id')}}</th>
                                <th>{{__('messages.name_family')}}</th>
                                <th>{{__('messages.phone')}}</th>
                                <th>{{__('messages.amount')}}</th>
                                <th>{{{__('messages.payment_date')}}}</th>
                                <th>{{__('messages.patern')}}</th>
                                <th>{{__('messages.title')}}</th>
                                <th>{{__('messages.status')}}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1; ?>
                            @foreach($otherPayments as $payment)
                                <tr>
                                    <td>{{$payment['id']}}</td>
                                    <td>{{$payment['user']['people']['name']}} {{$payment['user']['people']['family']}}</td>
                                    <td>{{$payment['phone']}}</td>
                                    <td>{{number_format($payment['amount'])}} {{__('messages.rial')}}</td>
                                    <td>
                                        @if($payment['payment_date'])
                                            {{jdate("Y-m-d",strtotime($payment['payment_date']))}}
                                        @endif
                                    </td>
                                    <td>{{$payment['patern']['title']}}</td>
                                    <td>
                                        {{$payment['title']['title']}}
                                    </td>
                                    <td>
                                        @if(isset($payment['tranInfo'][0]) && $payment['tranInfo'][0]['status']=='SUCCEED')
                                            <span
                                                class="badge badge-success">{{__('messages.'.$payment['tranInfo'][0]['status'])}}</span>
                                        @elseif(isset($payment['tranInfo'][0]) && $payment['tranInfo'][0]['status']=='FAILED')
                                            <span
                                                class="badge badge-danger">{{__('messages.'.$payment['tranInfo'][0]['status'])}}</span>
                                        @else
                                            <span class="badge badge-danger">{{__('messages.unknown')}}</span>
                                        @endif
                                    </td>

                                    <td>
                                        <a href="{{route('charity_payment_list_vow_show',['id'=>$payment['id']])}}"
                                           data-toggle="tooltip" data-placement="top" title="{{__('messages.view')}}"
                                           class="btn btn-outline-dark btn-sm"><i class="icon-eye"></i></a>
                                    </td>
                                </tr>
                                <?php $i++; ?>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </section>
@stop
