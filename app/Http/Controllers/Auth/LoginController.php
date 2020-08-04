<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers{
        redirectPath as laravelRedirectPath;
    }
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/user/profile';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $username;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->username = $this->findUsername();
    }
    public function findUsername()
    {
        $login = request()->input('name');
        if(filter_var($login, FILTER_VALIDATE_EMAIL)){
            $fieldType = 'email';
        }
        elseif (preg_match('/^((09)|(\+989)|(9))([0-9]{9})/', $login)){
            $fieldType = 'phone';
        }
        else{
            $fieldType = 'name';
        }
        $user = User::where($fieldType,$login)->first();
        if ($user and $user->phone and $user->phone_verified_at){
            $fieldType = 'phone';
        }
        request()->merge([$fieldType => $login]);

        return $fieldType;
    }

    public function username()
    {
//        $login = request()->input('login');
//        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
//        request()->merge([$field => $login]);
//        return $field;
        return $this->username;
    }

    public function redirectPath()
    {
        // Do your logic to flash data to session...
        session()->flash('message', __('messages.you_are_login'));
        // Return the results of the method we are overriding that we aliased.
        return $this->laravelRedirectPath();
    }

}
