<?php

namespace App\Http\Controllers\panel;

use App\notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class notificationController extends Controller
{

    public function list()
    {
        $notifications = notification::get();
        return view('panel.notifications.index',compact('notifications'));
    }
    public function new()
    {
        return view('panel.notifications.add');
    }
    public function edit($id)
    {
        $notification = notification::findOrFail($id);
        return view('panel.notifications.edit',compact('notification'));
    }
    public function save(Request $request)
    {
        $this->validate($request,
            [
                'title' => 'required',
                'start_time' => 'required',
                'end_time' => 'required',
                'description' => 'nullable',
            ]);
        $notification = new notification();
        $notification->title = $request['title'];
        $notification->body = $request['description'];
        $notification->start = shamsi_to_miladi(latin_num($request['start_time']));
        $notification->end = shamsi_to_miladi(latin_num($request['end_time']));
        $notification->save();
        return redirect(route('notifications.list'));

    }
    public function update($id,Request $request)
    {
        $this->validate($request,
            [
                'title' => 'required',
                'start_time' => 'required',
                'end_time' => 'required',
                'description' => 'nullable',
            ]);
        $notification = notification::findOrFail($id);
        $notification->title = $request['title'];
        $notification->body = $request['description'];
        $notification->start = shamsi_to_miladi(latin_num($request['start_time']));
        $notification->end = shamsi_to_miladi(latin_num($request['end_time']));
        $notification->save();
        return redirect(route('notifications.list'));
    }
    public function delete($id,Request $request)
    {
        $notification = notification::findOrFail($id);
        $notification->delete();
        return back_normal($request,'اعلان با موفقیت حذف شد');
    }

}
