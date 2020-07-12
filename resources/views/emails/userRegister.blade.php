@component('mail::message')

{{$user['email']}} خوش آمدید
@component('mail::button', ['url' => url('/')])
موسسه خیریه اشرف الانبیا(ص)
@endcomponent

{{ config('app.name') }}
@endcomponent
