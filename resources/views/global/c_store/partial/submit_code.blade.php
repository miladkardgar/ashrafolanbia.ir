@extends('global.c_store.frame')

@section('mrn-content')
    <div class="mrn-main-page-content">
        <div class="mrn-content-inner mrn-container" role="main">
            <div class="col-md-6 col-md-offset-3 text-center mb-50">
                <form method="post" action="{{route('global.c_store_card_completion_submit_code')}}" class="">
                    @csrf
                    <input type="hidden" name="phone" value="{{$phone}}" class="">
                    <label for="Phone">کد تایید ارسال شده به شماره
                        <span class="text-info">{{$phone}}</span>
                         را وارد کنید</label>
                    <input type="text" class="form-control input-lg mb-25" name="code" id="Code">
                    <button type="submit" class="btn btn-success btn-lg btn-block mt-2">تایید</button>
                </form>

                <div class="row mt-10">
                    <form method="post" action="{{route('global.c_store_resend_code')}}" class="">
                        @csrf
                        <input type="hidden" name="phone" value="{{$phone }}" class="">
                        <button class="" type="submit" style="
                          background: none!important;
                          border: none;
                          padding: 0!important;
                          color: #1f1dff;
                          cursor: pointer;
                        ">ارسال مجدد کد تایید
                        </button>

                    </form>
                </div>
                <div class="row mt-10">
                    <a href="{{route('global.c_store_card_completion_phone')}}" class="text-info">وارد کردن مجدد شماره</a>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

@endsection
