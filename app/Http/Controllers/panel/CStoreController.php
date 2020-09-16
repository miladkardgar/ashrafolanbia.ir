<?php

namespace App\Http\Controllers\panel;

use App\c_store_order;
use App\c_store_product;
use App\c_store_product_image;
use App\c_store_setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CStoreController extends Controller
{
    public function product_list()
    {
        $products = c_store_product::get();
        return view('panel.c_store.index',compact('products'));

    }
    public function add_product()
    {
        return view('panel.c_store.add');

    }
    public function store_product(Request $request)
    {
        $request['price'] = preg_replace("/[^0-9.]/", "", latin_num($request['price']));
        $request['count'] = preg_replace("/[^0-9.]/", "", latin_num($request['count']));
        $this->validate($request,
            [
                'title' => 'required',
                'price' => 'required|numeric',
                'description' => 'required',
                'delivery_delay' => 'nullable|numeric',
                'status' => 'nullable|boolean',
            ]);
        $c_product = new c_store_product();
        $c_product->title = $request['title'];
        $c_product->slug = str_slug_persian($request['title'])."_".rand(11111,999999);
        $c_product->keywords = $request['keywords'];
        $c_product->description = $request['description'];
        $c_product->dimension = $request['dimension'];
        $c_product->weight = $request['weight'];
        $c_product->delivery_description = $request['delivery_description'];
        $c_product->warning_info = $request['warning_info'];
        $c_product->price = $request['price'];
        $c_product->count = $request['count'];
        $c_product->allowed_provinces = implode(',',$request['allowed_provinces']);
        $c_product->allowed_cities = implode(',',$request['allowed_cities']);
        $c_product->delivery_delay = latin_num($request['delivery_delay']);
        $c_product->delivery_delay_type = $request['delay_type'];
        $c_product->active = $request['status'];
        $c_product->save();

        return redirect(route('c_store.edit_product',['slug'=>$c_product->slug]));

    }
    public function edit_product($slug)
    {
        $product = c_store_product::with('images')->where('slug',$slug)->firstOrFail();

        return view('panel.c_store.edit',compact('product'));
    }
    public function update_product(Request $request)
    {
        $request['price'] = preg_replace("/[^0-9.]/", "", latin_num($request['price']));
        $request['count'] = preg_replace("/[^0-9.]/", "", latin_num($request['count']));
        $this->validate($request,
            [
                'CSP_id' => 'required',
                'title' => 'required',
                'price' => 'required|numeric',
                'description' => 'required',
                'delivery_delay' => 'nullable|numeric',
                'status' => 'nullable|boolean',
            ]);
        $c_product = c_store_product::find($request['CSP_id']);
        $c_product->title = $request['title'];
        $c_product->keywords = $request['keywords'];
        $c_product->description = $request['description'];
        $c_product->dimension = $request['dimension'];
        $c_product->weight = $request['weight'];
        $c_product->delivery_description = $request['delivery_description'];
        $c_product->warning_info = $request['warning_info'];
        $c_product->price = $request['price'];
        $c_product->count = $request['count'];
        $c_product->allowed_provinces = implode(',',$request['allowed_provinces']);
        $c_product->allowed_cities = implode(',',$request['allowed_cities']);
        $c_product->delivery_delay = latin_num($request['delivery_delay']);
        $c_product->delivery_delay_type = $request['delay_type'];
        $c_product->active = $request['status'];
        $c_product->save();

        return redirect(route('c_store.product_list'));

    }
    public function delete_product(Request $request,$slug)
    {
        $product = c_store_product::with('images')->where('slug',$slug)->delete();
        return back_normal($request,'محصول حذف شد');
    }

    public function upload_product_image (Request $request)
    {
        $this->validate($request, [
            'file' => 'required|image',
            'CSP_id' => 'required',
        ]);

        c_store_image($request['file'], $request['CSP_id']);
        $messages = trans('messages.item_created', ['item' => trans('messages.image')]);
        return back_normal($request, $messages);
    }

    public function remove_product_image (Request $request)
    {
        if ($media = c_store_product_image::find($request['id'])) {
            $media->delete();
            $messages = trans('messages.item_deleted', ['item' => trans('messages.file')]);
            return back_normal($request, $messages);
        }
    }
    public function set_main_product_image (Request $request)
    {
        $this->validate($request,
            [
                'CSP_id' => 'required',
                'media_id' => 'required',
            ]);
        c_store_product_image::where('CSP_id',$request['CSP_id'])->update(['main_img'=>0]);
        c_store_product_image::where('CSP_id',$request['CSP_id'])->where('id',$request['media_id'])->update(['main_img'=>1]);
        return back_normal($request);
    }

    public function orders_list()
    {
        $orders = c_store_order::where('status','paid')->get();
        return view('panel.c_store.orders_list',compact('orders'));

    }
    public function order($id)
    {
        $order = c_store_order::with('items')->find($id);
        return view('panel.c_store.order',compact('order'));

    }
    public function change_order_status($id,Request $request)
    {
        $order = c_store_order::with('items')->find($id);
        $this->validate($request,
            [
                'status' => 'required|in:new,processing,done,canceled',
            ]);
        $order->process_status = $request['status'];
        $order->save();
        return back_normal($request,'وضعیت سفارش بروزرسانی شد');

    }

    public function setting_show()
    {
        $phones = c_store_setting::where('key','phones')->first()['value'];
        $end_time = c_store_setting::where('key','end_time')->first()['value'];
        $transport_price = c_store_setting::where('key','transport_price')->first()['value'];
        $transport_free = c_store_setting::where('key','transport_free')->first()['value'];
        $free_provinces = json_decode(c_store_setting::where('key','free_provinces')->first()['value']);
        $free_cities = json_decode(c_store_setting::where('key','free_cities')->first()['value']);
        $description = json_decode(c_store_setting::where('key','description ')->first()['value']);

        return view('panel.c_store.setting',compact('phones','end_time','transport_free','transport_price'
        ,'free_provinces','free_cities','description'));
    }
    public function setting_update(Request $request)
    {
        if ($request['phones']){
            c_store_setting::where('key','phones')->delete();
            $setting = new c_store_setting();
            $setting->key = 'phones';
            $setting->value = $request['phones'];
            $setting->save();
        }
        if ($request['time']){
            c_store_setting::where('key','end_time')->delete();
            $setting = new c_store_setting();
            $setting->key = 'end_time';
            $setting->value = $request['time'];
            $setting->save();
        }
        if ($request['transport_price']){
            c_store_setting::where('key','transport_price')->delete();
            $setting = new c_store_setting();
            $setting->key = 'transport_price';
            $setting->value = preg_replace("/[^0-9.]/", "", latin_num($request['transport_price']));
            $setting->save();
        }
        if ($request['transport_free']){
            c_store_setting::where('key','transport_free')->delete();
            $setting = new c_store_setting();
            $setting->key = 'transport_free';
            $setting->value = preg_replace("/[^0-9.]/", "", latin_num($request['transport_free']));
            $setting->save();
        }
        if ($request['free_provinces']){
            c_store_setting::where('key','free_provinces')->delete();
            $setting = new c_store_setting();
            $setting->key = 'free_provinces';
            $setting->value = json_encode($request['free_provinces']);
            $setting->save();
        }
        if ($request['free_cities']){
            c_store_setting::where('key','free_cities')->delete();
            $setting = new c_store_setting();
            $setting->key = 'free_cities';
            $setting->value = json_encode($request['free_cities']);
            $setting->save();
        }
        if ($request['description']){
            c_store_setting::where('key','description')->delete();
            $setting = new c_store_setting();
            $setting->key = 'description';
            $setting->value = json_encode($request['description']);
            $setting->save();
        }

        return back_normal($request,'تنظیمات ذخیره شد');

    }

}
