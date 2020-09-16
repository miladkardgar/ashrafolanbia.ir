<?php

namespace App\Providers;

use App\charity_payment_patern;
use App\charity_payment_title;
use App\charity_supportForm_file;
use App\contact;
use App\menu;
use App\order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use WebDevEtc\BlogEtc\Models\BlogEtcCategory;
use WebDevEtc\BlogEtc\Models\BlogEtcComment;
use WebDevEtc\BlogEtc\Models\BlogEtcSpecificPages;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {



    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        \View::composer("*", function ($view) {
            $view->with([
                'menu'=> menu::where('local',App()->getLocale())->where('parent_id',0)->where('type','top')->orderBy('order')->get(),
                'side_menu'=> menu::where('local',App()->getLocale())->where('type','side')->orderBy('order')->get(),
                's_form_count'=> charity_supportForm_file::where('status',0)->count(),
                'contact_msgs'=> contact::where('status','new')->count(),
                'orders_count'=> order::where('status','paid')->count(),
                'comments_count'=> BlogEtcComment::withoutGlobalScopes()->where("approved", false)->count(),

//                'menu'=> charity_payment_patern::get(),
//                'menu_blog'=> BlogEtcCategory::where("lang",app()->getLocale())->orderBy("category_name")->get(),
//                'menu_special'=> BlogEtcSpecificPages::where("lang",app()->getLocale())->orderBy("category_name")->get(),
            ]);
        });
        Schema::defaultStringLength(191);
    }
}
