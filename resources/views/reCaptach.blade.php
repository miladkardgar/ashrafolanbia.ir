<form name="login-form" class="clearfix" method="POST" action="{{route('login')}}">
    {{csrf_field()}}
    <input type="hidden" name="g-recaptcha-response">

</form>
<script src="https://www.google.com/recaptcha/api.js?render={{env('SITE_KEY')}}&hl=fa"></script>
<script>
    grecaptcha.ready(function () {
        grecaptcha.execute("{{env('SITE_KEY')}}", {action: 'contact'}).then(function (token) {
            document.querySelector('input[name=g-recaptcha-response]').value = token
        });
    });
</script>
