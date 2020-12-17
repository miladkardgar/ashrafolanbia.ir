<div id="routine-deactive" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">

    <div class="modal-dialog">
    @if(Session::get('ask_for_increase'))
        <!-- Modal content-->
            <div class="modal-content">

                <div class="modal-body text-center">

                        <h3>
                            نیکوکار گرامی
                            <b>
                                {{get_name($user['id'])}}
                            </b>
                            {{__('long_msg.ask_for_increase_title')}}
                            <br>
                        </h3>
                        <p>
                            {{__('long_msg.ask_for_increase_message')}}
                        </p>

                </div>

                <div class="modal-footer ">
                    <a href="{{route('t_routine_vow')}}" class="button mrn-button-success pull-right" name="submit-btn"
                       type="submit"
                    >
                        {{__('words.yes_increase')}}
                    </a>
                    <button type="button" class="button mrn-button-danger center" data-dismiss="modal">
                        {{__('words.no_maybe_later')}}
                    </button>
                </div>
            </div>
    @elseif(Session::get('routine_is_not_active'))
        <!-- Modal content-->
            <div class="modal-content">

                <div class="modal-body text-center">
                    <a class="">

                        <h3>
                            نیکوکار گرامی
                            <b>
                                {{get_name($user['id'])}}
                            </b>
                            کمک ماهانه/هفتگی شما فعال نیست،
                            <br>
                            لطفا در صورت تمایل کمک ماهانه یا هفتگی خود را فعال نمایید.

                        </h3>
                    </a>
                </div>

                <div class="modal-footer ">
                    <a href="{{route('t_routine_vow')}}" class="button mrn-button-success pull-right" name="submit-btn"
                       type="submit"
                    >
                        صفحه فعال سازی کمک ماهانه/هفتگی
                    </a>
                    <button type="button" class="button mrn-button-danger center" data-dismiss="modal">
                        فعلا نه، شاید زمانی دیگر
                    </button>
                </div>
            </div>
    @else
        <!-- Modal content-->
            <div class="modal-content">

                <div class="modal-body text-center">
                    <a class="">

                        <h3>
                            {{__('messages.you_have_unpaid_period')}}

                        </h3>
                    </a>
                </div>

                <div class="modal-footer ">

                    <button type="button" class="button mrn-button-danger btn-block center" data-dismiss="modal">
                        تایید
                    </button>
                </div>
            </div>
            }
        @endif

    </div>
</div>
<script type="text/javascript">
    $(window).on('load', function () {
        $('#routine-deactive').modal('show');
    });
</script>