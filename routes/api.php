<?php

use Illuminate\Http\Request;

Route::get('/main_page', 'app\api@main_page')->name('main_page'); //صفحه اول شامل اخبا، اطلاعیه ها، عناوین پرداخت و درن انتظار پرداخت ها اگر لاگین بود
Route::get('/show_post/{slug}', 'app\api@show_post')->name('show_post');// نمایش یک پست و خبر با امکان ثبت نظر

// throttle to a max of 10 attempts in 3 minutes:
Route::group(['middleware' => 'throttle:5,3'], function () {
    Route::post('/comment/{blog_post_slug}','app\api@addNewComment')->name('comment');
});

Route::post('/login', 'app\api@login')->name('login'); // ورود به حساب کاربری

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('/user_data', 'app\api@user_data')->name('user_data'); // مشاهده سوابق پرداخت
    Route::post('/login_link', 'app\api@login_link')->name('login_link'); // مشاهده لینک موقت ورود
});

