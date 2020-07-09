<?php

use Illuminate\Http\Request;

Route::get('/main_page', 'app\api@main_page')->name('main_page'); //صفحه اول شامل اخبا، اطلاعیه ها، عناوین پرداخت و درن انتظار پرداخت ها اگر لاگین بود
//Route::get('/more_posts', 'app\api@more_posts')->name('more_posts');// لود 6 خبر بیشتر
Route::get('/show_post/{slug}', 'app\api@show_post')->name('show_post');// نمایش یک پست و خبر با امکان ثبت نظر

// throttle to a max of 10 attempts in 3 minutes:
Route::group(['middleware' => 'throttle:5,3'], function () {
    Route::post('/comment/{blog_post_slug}','app\api@addNewComment')->name('comment');
});

Route::get('/payment', 'app\api@payment')->name('payment');//صفحه پرداخت
Route::post('/transaction', 'app\api@transaction')->name('transaction');// ساخت ترنزاکشن
Route::get('/callback', 'app\api@callback')->name('callback');// ثبت پرداخت موفق

Route::get('/show_from', 'app\api@show_from')->name('show_from'); //صفحه فرم درخواست
Route::post('/submit_from', 'app\api@set_from')->name('set_from');//ارسال فرم

Route::post('/login', 'app\api@login')->name('login'); // ورود به حساب کاربری

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/profile', 'app\api@profile')->name('profile'); // مشاهده پروفایل شامل تعهد های فعال و در انتظار پرداخت ها
    Route::post('/set_periodic', 'app\api@set_periodic')->name('set_periodic'); // ثبت تعهد جدید
    Route::get('/payment_history', 'app\api@payment_history')->name('payment_history'); // مشاهده سوابق پرداخت
});

