<?php

namespace App\Http\Controllers\panel;

use App\ApplicationSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class mobile_app_controller extends Controller
{

    public function save_notification(Request $request){
        $this->validate($request, [
            'notification' => 'required',
        ]);
        ApplicationSetting::where('key','main_page_notification')->delete();

        $data =[
            'text'=>$request['notification'],
            'link'=>isset($request['link'])? $request['link']:""  ,
        ];

        $notification = new ApplicationSetting();
        $notification->key = 'main_page_notification';
        $notification->value = json_encode($data);
        $notification->save();

        return back();
    }

    public function save_payment_title(Request $request){
        $this->validate($request, [
            'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000',
            'link' => 'required',
            'title' => 'required',
        ]);
        $image_id = image_saver($request['image'], 'application', 'application');

        $data =[
            'title'=>$request['title'],
            'link'=>$request['link'],
            'image'=>$image_id
        ];

        $notification = new ApplicationSetting();
        $notification->key = 'main_page_links';
        $notification->value = json_encode($data);
        $notification->save();

        return back();
    }

    public function delete_payment_title($id){
        ApplicationSetting::where('key','main_page_links')->where('id',$id)->delete();
        return back();
    }
}
