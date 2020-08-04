<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AckPhone
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::user() or !Auth::user()->phone_verified_at) {
            session()->put(['message' => 'لطفا شماره موبایل خود را تایید کنید.']);
            return redirect(route('global_profile_completion'));
        }
        return $next($request);
    }
}
