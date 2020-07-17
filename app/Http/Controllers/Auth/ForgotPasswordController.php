<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\passwordResetCode;
use App\Mail\payment_confirmation;
use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

//    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function findUsername($login)
    {

        if(filter_var($login, FILTER_VALIDATE_EMAIL)){
            $fieldType = 'email';

        }
        elseif (preg_match('/^((09)|(\+989)|(9))([0-9]{9})/', $login)){
            $fieldType = 'phone';
        }
        else{
            $fieldType = 'name';
        }

        return $fieldType;
    }

    public function password_reset()
    {
        $login = request()->input('name');

        $fieldType = $this->findUsername($login);

        $user = User::where($fieldType,$login)->first();

        if ($user){
        $code = rand(11111,99999);

        $user->password_reset_code = $code;

        $user->save();
        if ($user->phone){
        sendSms($user->phone, $code);
        }
        if ($user->email){
        Mail::to($user->email)->send(new passwordResetCode($code));
        }
        return view('global.materials.password_reset',['code_sent'=>true,'login'=>$login]);
        }
        else{
            return back()->with('message', "کاربر پیدا نشد");
        }
    }

    public function password_change(Request $request)
    {
        $code = request()->input('code');
        $login = request()->input('name');
        $password = request()->input('new_password');
        $fieldType = $this->findUsername("name@domain.com");

        $user = User::where($fieldType,$login)->where('password_reset_code',$code)->first();
        if ($user){
            $user->password = Hash::make($password);
            $user->save();
        }

        return redirect(route('global_login_page'));
    }
}
