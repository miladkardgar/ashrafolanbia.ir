@extends('global.c_store.frame')

@section('mrn-content')
    <div class="mrn-main-page-content">
        <div class="mrn-content-inner mrn-container" role="main">
            <div class="col-md-6 col-md-offset-3 text-center mb-50">
                <form method="post" action="{{route('global.c_store_card_completion_submit_phone')}}" class="">
                @csrf
                <label for="Phone">لطفا شماره موبایل خود را وارد کنید.</label>
                <input type="text" class="form-control input-lg mb-25" name="phone" value="{{$phone? $phone :""}}" id="Phone">
                <button type="submit" class="btn btn-success btn-lg btn-block mt-2"> دریافت کد تایید</button>
                </form>
            </div>
        </div>
    </div>
    </div>
    </div>

@endsection
