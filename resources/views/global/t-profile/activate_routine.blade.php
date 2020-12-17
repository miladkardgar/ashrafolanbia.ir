<h3 class="notifications " style="color: black"><b>{!!isset($routine) ? __('long_msg.edit_routine'):__("long_msg.create_routine")  !!}</b></h3>

<div class="">
    <div class="mrn-status-user-widget mt-2">
        <ul class="radio-tabs">
            @foreach(config('charity.routine_types') as $key => $routine_type)
                <li class="all_bills w-50">
                    <input type="radio"
                           data-target="#routine-modal-{{$key}}" data-toggle="modal"
                           {{(isset($routine) and $routine['period']==$key) ?"checked":""}}
                           id="radio-{{$key}}"
                           data-notice="notice-{{$key}}"
                           data-day="{{$routine_type['week_day']}}"
                           class="radio-type "
                           value="{{$key}}"
                           name="type"

                    />
                    <label for="radio-{{$key}}" class="text-center {{$routine_type['color']}}">
                        <h4 style="margin-top: 1.5em">
                            <i class="fa fa-hand-pointer-o"></i>
                            {{$routine_type['text']}}


                            @if(isset($routine) and $routine['period'] == $key)
                                <div class="row">
                                            <span class="text-white font-size-sm"
                                                  style="font-weight: bold">(فعال)</span>
                                </div>
                            @endif
                        </h4>
                    </label>
                </li>

            @endforeach
        </ul>
    </div>
</div>
<div class="">
    @foreach(config('charity.routine_types') as $key => $routine_type)

        <div id="routine-modal-{{$key}}" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <form method="post" id="" action="{{route('add_charity_period')}}"
                      class="clearfix routine-form">
                    <div class="modal-content">

                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">{{$routine_type['title']}}</h4>
                        </div>
                        <div class="modal-body">

                            <div class="mrn-notifications-box-green vow-notice"
                                 id="notice-{{$key}}">
                                <h4 class="notifications"><i
                                            class="fa fa-bell-o"></i> {{$routine_type['title']}} </h4>

                                <ul class="list-unstyled">
                                    <li class="announce-read">
                                        <div class="notifications-content">
                                            <p>
                                                {{$routine_type['description']}}
                                            </p>

                                        </div>
                                    </li>
                                </ul>
                            </div>

                            @csrf
                            <input type="hidden" name="type" value="{{$key}}">
                            <div class="row">
                                <div class="form-group col-md-6 ">
                                    <label for="amount" class="">مبلغ: <span
                                                class="text-muted">(ریال)</span></label>
                                    <input id="amount" name="amount" class="form-control amount left"
                                           value="{{isset($routine) ? number_format($routine['amount']):""}}"
                                           type="text" required="required" placeholder="مبلغ"
                                           autocomplete="off">
                                </div>
                            </div>

                            <div class="row" id="day-of-month"
                                 style=" {{$routine_type['week_day']<7  ?"display: none":"display: block"}}">
                                @php
                                    if (isset($routine)){
                                        $day = latin_num(jdate('d',strtotime($routine['start_date'])));
                                    }else{
                                        $day = latin_num(jdate("d",time()));
                                    }
                                @endphp
                                <div class="form-group col-md-6">
                                    <label for="Day" class="">روز:</label>
                                    <select name="day" id="Day" class="form-control">
                                        <option value="" disabled class="">روز ماه:</option>
                                        @for($d=1 ; $d<=29;$d++)
                                            <option {{$day == $d ? "selected":""}} value="{{$d}}"
                                                    class="">{{$d}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <?php
                            $routine_pattern = \App\charity_payment_patern::where('system',1)->where('periodic',1)->first()
                            ?>
                            @if($routine_pattern->titles)
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>{{__('messages.for')}}</label>
                                        <select name="payment_title" class="form-control" id="title">
                                            @foreach($routine_pattern->titles as $title)
                                                <option
                                                        {{(isset($routine) and $routine->title_id == $title['id'])?"selected":""}}
                                                        value="{{$title['id']}}">{{$title['title']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button class="button mrn-button pull-right" name="submit-btn" type="submit"
                            >ثبت و ذخیره
                            </button>
                            <button type="button" class="button mrn-button-danger" data-dismiss="modal">بستن
                                پنجره
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    @endforeach
</div>
