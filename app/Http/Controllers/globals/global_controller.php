<?php

namespace App\Http\Controllers\globals;

use App\champion_transaction;
use App\charity_champion;
use App\charity_payment_patern;
use App\Events\userRegister;
use App\Events\userRegisterEvent;
use App\person;
use App\Rules\RecaptchaV3;
use App\users_address;
use App\users_address_extra_info;
use Carbon\Carbon;
use function GuzzleHttp\Psr7\str;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Validator;
use App\charity_period;
use App\charity_periods_transaction;
use App\city;
use App\store_product;
use App\store_product_inventory;
use App\store_product_inventory_size;
use App\User;
use const http\Client\Curl\AUTH_ANY;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class global_controller extends Controller
{

    public function register_form_store(Request $request)
    {
        $this->validate($request, [
            'mobile' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);
        $this->validate($request, [
            'mobile' => 'required|numeric|regex:/(09)[0-9]{9}/'
        ]);
        $exists = User::where('phone',$request->mobile)->exists();
        if ($exists){
            return back_error($request,['شماره تکراری'=>'این شماره قبلا ثبت شده است.']);
        }

        $user = User::create([
            'phone' => $request->mobile,
            'code_phone' => random_int(12320, 98750),
            'code_phone_send' => date("Y-m-d H:i:s",time()+180),
            'disabled' => 1,
            'password' => bcrypt($request->password),
        ]);
        Auth::loginUsingId($user->id);

        event(new userRegisterEvent($user));
        $message = trans("messages.user_created");
//        return redirect(route("global_ack_phone_page"));
        return back_normal($request, $message);
    }

    public function reset_password(Request $request)
    {
        return $this->check_email_exists($request);
    }

    public function check_email(Request $request)
    {
        $email = null;
        $phone = null;
        $is_email = filter_var($request->phone_email, FILTER_VALIDATE_EMAIL);
        if ($is_email) {
            $email = $request->phone_email;
        } else {
            $phone = $request->phone_email;
        }
        if ((User::where('email', $email)->exists() and $email) || (User::where('phone', $phone)->exists() and $phone)) {
            return 'false';
        }

        return 'true';

    }

    public function check_email_exists(Request $request)
    {
        $email = null;
        $phone = null;
        $is_email = filter_var($request->phone_email, FILTER_VALIDATE_EMAIL);
        if ($is_email) {
            $email = $request->phone_email;
        } else {
            $phone = $request->phone_email;
        }
        if ((User::where('email', $email)->exists() and $email) || (User::where('phone', $phone)->exists() and $phone)) {
            $check = true;
        } else {
            $check = false;
        }
        if ($check) {
            return view('auth.passwords.reset');
        }

    }

    public function login(Request $request)
    {
        back_normal($request);
    }

    public function update_information(Request $request)
    {
        dd($request->all());

    }

    public function update_password(Request $request)
    {

        $this->validate($request, [
            'password' => 'required|confirmed|min:6',
        ]);
        $user = User::find(Auth::id());
        if (Hash::check($request['old_password'], $user->password)) {
            $user->password = Hash::make($request['password']);;
            $user->save();
            $message = trans("messages.password_changed");
            return back_normal($request, $message);
        } else {
            $message[] = trans("messages.current_password_invalid");
            return back_error($request, $message);
        }

    }

    public function product_size_info(Request $request)
    {

        $info = store_product_inventory_size::find($request['size_id']);
        return json_encode($info);
    }


    //start cart actions
    public function add_to_cart(Request $request)
    {
        $count = 1;
        $product = store_product::with('store_product_inventory')->find($request['pro_id']);
        $price = $product['store_product_inventory']['price'];
        $off = $product['store_product_inventory']['off'];
        $time = $product['ready'];
        if (isset($request['count'])) {
            $count = $request['count'];
        }
        if (!$product) {
            abort(404);
        }

        $cart = session()->get('cart');

        // if cart is empty then this the first product
        if (!$cart) {
            $cart = [
                "order" => [
                    $request['pro_id'] => [
                        "title" => $product['title'],
                        "product_id" => $product['id'],
                        "price" => $price,
                        "off" => $off,
                        "count" => $count,
                        "photo" => $product['main_image'],
                        'time' => $time
                    ]
                ]
            ];
            session()->put('cart', $cart);
            $message = trans('messages.product_added_successfully');
            return back_normal($request, $message);
        }

        // if cart not empty then check if this product exist then increment quantity
        if (isset($cart['order'][$request['pro_id']])) {

            $cart[$request['order']['pro_id']]['count']++;

            session()->put('cart', $cart);

            $message = trans('messages.product_added_successfully');
            return back_normal($request, $message);

        }

        // if item not exist in cart then add to cart with quantity = 1
        $cart['order'][$request['pro_id']] = [
            "title" => $product['title'],
            "product_id" => $product['id'],
            "price" => $price,
            "off" => $off,
            "count" => $count,
            "photo" => $product['main_image'],
            'time' => $time
        ];

        session()->put('cart', $cart);

        $message = trans('messages.product_added_successfully');
        return back_normal($request, $message);
    }

    public function cart_update(Request $request)
    {
        if ($request['id'] and $request['count']) {
            $cart = session()->get('cart');

            $cart[$request['id']]["count"] = $request['count'];

            session()->put('cart', $cart);

            session()->flash('success', 'Cart updated successfully');
        }
    }

    public function cart_remove(Request $request)
    {
        if ($request['id']) {

            $cart = session()->get('cart');

            if (isset($cart['order'][$request['id']])) {

                unset($cart['order'][$request['id']]);

                session()->put('cart', $cart);
            }

            session()->flash('success', 'Product removed successfully');
        }
    }

//    public function add_charity_period__OLD(Request $request)
//    {
//        if (!is_null($request['amount'])) {
//            $request['amount'] = str_replace(',', '', $request['amount']);
//        }
//        $this->validate($request,
//            [
//                'amount' => 'required|min:10000|max:1000000000|numeric',
//                'start_date' => 'required',
//                'period' => 'required',
//            ]);
//        $info = charity_period::create(
//            [
//                'user_id' => Auth::id(),
//                'amount' => $request['amount'],
//                'start_date' => shamsi_to_miladi($request['start_date']),
//                'next_date' => shamsi_to_miladi($request['start_date']),
//                'period' => $request['period'],
//                'description' => $request['description'],
//            ];
//        );
//
//        if (strtotime(shamsi_to_miladi($request['start_date'])) <= time()) {
//            charity_periods_transaction::create(
//                [
//                    'user_id' => Auth::id(),
//                    'period_id' => $info['id'],
//                    'payment_date' => $info['next_date'],
//                    'amount' => $info['amount'],
//                    'description' => "پرداخت دوره ای شماره " . $info['id'],
//                    'status' => "unpaid",
//                ]
//            );
//            charity_period::where('id', $info['id'])->update(
//                [
//                    'next_date' => date('Y-m-d', strtotime("+" . $info['period'] . " month", time()))
//                ]
//            );
//        }
//
//
//        $message = trans("messages.period_created");
//        return back_normal($request, ['message' => $message, "code" => 200]);
//    }


    public function add_charity_period(Request $request)
    {
        if (!is_null($request['amount'])) {
            $request['amount'] = str_replace(',', '', $request['amount']);
        }
        $availableTypes = config('charity.routine_types');
        $pattern = charity_payment_patern::where('periodic','1')->first();
        $this->validate($request,
            [
                'amount' => 'required|min:'.$pattern['min'].'|max:'.$pattern['max'].'|numeric',
                'type' => 'required|in:'.implode(',', array_keys($availableTypes)),
                'payment_title' => 'required',
            ],
            [
                'amount.min' =>  "مبلغ نباید از " . number_format($pattern['min']) . " ریال کمتر باشد",
                'type.required' => 'انتخاب نوع کمک ماهانه/هفتگی الزامی است',
                'payment_title.required' => 'انتخاب مورد مصرف کمک ماهانه/هفتگی الزامی است'

            ]);

        $vow_type =$availableTypes[$request['type']];
        $month = latin_num(jdate('m'));
        $year = latin_num(jdate("Y"));

        if (in_array($vow_type['week_day'],[0,1,2,3,4,5,6])){

            $day = latin_num(jdate('d'));
            $targetTimestamp = jmktime(2,0,0,$month,$day,$year);
            $current_week_day = latin_num(jdate('w',$targetTimestamp));
            $day_dif = (7 + ($vow_type['week_day'] - $current_week_day)) % 7;
            $targetTimestamp = jmktime(2,0,0,$month,$day+$day_dif,$year);

        }else{
            $this->validate($request,
                [
                    'day' => 'required|min:1|max:29',
                ]);

            $day = latin_num($request['day']);
            $targetTimestamp = jmktime(2,0,0,$month,$day,$year);

        }
        charity_period::where('user_id',Auth::user()['id'])->delete();

        $date = date("Y-m-d H:i:s",$targetTimestamp);

        $info = charity_period::create(
            [
                'user_id' => Auth::id(),
                'amount' => $request['amount'],
                'title_id' => $request['payment_title'],
                'start_date' => $date,
                'next_date' => $date,
                'period' => $request['type'],
                'description' => " ",
            ]
        );
        if ($day < latin_num(jdate('d'))){
            $info = updateNextRoutine($info['id']);
        }


        if (strtotime($info['next_date']) <= time() and !charity_periods_transaction::where('user_id',Auth::id())->where('payment_date',$info['next_date'])->exists()) {
            $random = Str::random(6);
            while (charity_periods_transaction::where('slug', $random)->exists()) {
                $random = Str::random(7);
            }
            charity_periods_transaction::create(
                [
                    'user_id' => Auth::id(),
                    'period_id' => $info['id'],
                    'title_id' => $info['title_id'],
                    'payment_date' => $info['next_date'],
                    'amount' => $info['amount'],
                    'description' => $availableTypes[$request['type']]['title']." " . $info['id'],
                    'status' => "unpaid",
                    'slug' => $random,
                ]
            );
            $update = updateNextRoutine($info['id']);
        };

        $message = trans("messages.period_created");
        return back_normal($request,  $message);
    }

    public function profile_period_delete(Request $request)
    {
        try {
            if (!charity_period::where('user_id', Auth::id())->exists()){
                return back_normal($request, ['message' => "کمک ماهانه/هفتگی پرداخت شما فعال نیست.", "code" => 400]);
            }
            charity_period::where('user_id', Auth::id())->delete();
            return back_normal($request, ['message' => "کمک ماهانه/هفتگی پرداخت غیرفعال شد", "code" => 200]);
        }
        catch (\Throwable $exception){
            $message[] = trans("messages.period_not_found");
            return back_error($request, $message);
        }

    }

    public function profile_period_check()
    {
        $charity = charity_period::where(
            [
                ['status', '=', 'active'],
                ['next_date', '<', date("Y-m-d")]
            ])->get();
        foreach ($charity as $item) {
            $nextDate = strtotime($item['next_date']);
            $now = time();
            if ($now > $nextDate) {
                charity_periods_transaction::create(
                    [
                        'user_id' => $item['user_id'],
                        'period_id' => $item['id'],
                        'title_id' => $item['title_id'],
                        'payment_date' => $item['next_date'],
                        'amount' => $item['amount'],
                        'description' => "پرداخت دوره ای شماره " . $item['id'],
                        'status' => "unpaid",
                    ]
                );
                charity_period::where('id', $item['id'])->update(
                    [
                        'next_date' => date('Y-m-d', strtotime("+" . $item['period'] . " month", time()))
                    ]
                );
            }
        }
    }

    //end cart actions

    public function get_city_list(Request $request)
    {
        $this->validate($request,
            [
                'proID' => 'required|integer'
            ]);
        $cities = city::where('parent', $request['proID'])->get();
        return response()->json($cities);
    }

    public function store_order_add_address(Request $request)
    {
        $request['mobile'] = latin_num($request['mobile']);
        $request['phone'] = latin_num($request['phone']);

        $this->validate($request,
            [
                'province' => 'required',
                'cities' => 'required',
                'meeting_address' => 'required',
                'receiver' => 'required',
                'condolences_to' => 'required',
                'from_as' => 'required',
                'late_name' => 'required',
                'meeting_date' => 'required',
                'meeting_time' => 'required',
            ]
        );
        $address = users_address::create(
            [
                'user_id' => Auth::id(),
                'address' => $request['meeting_address'],
                'province_id' => $request['province'],
                'city_id' => $request['cities'],
                'receiver' => $request['receiver'],
                'phone' => $request['phone'],
                'mobile' => $request['mobile'],
                'zip_code' => $request['zip_code'],
                'lat' => $request['lat'],
                'lon' => $request['lon'],
            ]
        );
        users_address::where(
            [
                ['user_id', '=', Auth::id()],
                ['id', '!=', $address['id']],
            ])->update(
            [
                'default' => 0
            ]
        );
        users_address_extra_info::create(
            [
                'condolences' => $request['condolences_to'],
                'on_behalf_of' => $request['from_as'],
                'late_name' => $request['late_name'],
                'meeting_date' => $request['meeting_date'],
                'meeting_time' => $request['meeting_time'],
                'descriptions' => $request['description'],
                'address_id' => $address->id,
            ]
        );
        return back_normal($request, ['message' => __("messages.address_added"), 'status' => 200]);
    }

    public function store_order_remove_address(Request $request)
    {
        $address = users_address::findOrFail($request['id']);
        if ($address && $address['user_id'] == Auth::id()) {
            $address->delete();
            $maxAddress = users_address::where('user_id', Auth::id())->max('id');
            users_address::where('id', $maxAddress)->update(['default' => 1]);
            return back_normal($request, ['message' => __("messages.address_deleted"), 'status' => 200]);
        }
    }

    public function champion_payment(Request $request)
    {
        if ($request['amount']) {
            $request['amount'] = intval(str_replace(',', '', $request['amount']));
        }
        $this->validate($request,
            [
                'champion_id' => 'required',
                'amount' => 'required|numeric|between:10000,9000000000|'
            ]);
        if (charity_champion::where('status', 1)->findOrFail($request['champion_id'])) {
            $user_id = 0;
            if (Auth::id()) {
                $user_id = Auth::id();
            }
            $champion = champion_transaction::create(
                [
                    'champion_id' => $request['champion_id'],
                    'amount' => $request['amount'],
                    'user_id' => $user_id,
                    'name' => $request['name'],
                    'last_name' => $request['last_name'],
                    'phone' => $request['phone'],
                    'email' => $request['email'],
                ]
            );
            return back_normal($request, ['message' => __('messages.transaction_created'), 'code' => 200, 'id' => $champion['id']]);
        }
    }

    public function global_profile_completion_upload_image(Request $request)
    {
        uploadGallery($request['file'], "profile", ['category_id' => Auth::id(), 'title' => 'تصویر پروفایل']);
        return redirect()->back();
    }

    public function global_profile_completion_submit(Request $request)
    {
        $con = true;
        if ($request['birthday']) {
            $request['birthday'] = shamsi_to_miladi($request['birthday']);
        }
        if ($request['national_code']) {
            if (!national_code_validation($request['national_code'])) {
                $con = false;
            };
        }
        $user = Auth::user();
        $user->address = $request['address'];

        if ($request['email']) {
            if ($user->email != $request['email']) {
                $user->email_verified_at = null;
                $user->code_email = null;
            }
            $user->email = $request['email'];
            $user->save();

        }
        if ($request['mobile']) {

            if ($user->phone != $request['mobile']) {
                $user->phone_verified_at = null;
                $user->code_phone = null;
            }
            $user->phone = $request['mobile'];
            $user->save();

        }

        $message = '';
        if ($con) {
            if ($person = person::where('user_id', '=', Auth::id())->first()) {

                $person->name = $request['name'];
                $person->family = $request['family'];
                $person->gender = $request['gender'];

                $person->national_code = $request['national_code'];
                if ($request['phone']) {
                    $person->phone = $request['phone'];
                }
                $person->birth_date = $request['birthday'];
                if ($request['email']) {
                    $person->email = $request['email'];
                }
                $person->save();
                $message = __('messages.item_updated', ['item' => trans('messages.information')]);
            } else {
                person::create(
                    [
                        'parent_id' => Auth::id(),
                        'user_id' => Auth::id(),
                        'name' => $request['name'],
                        'family' => $request['family'],
                        'national_code' => $request['national_code'],
                        'phone' => $request['phone'],
                        'email' => $request['email'],
                        'gender' => $request['gender'],
                        'birth_date' => $request['birthday']
                    ]
                );
                $message = __('messages.item_updated', ['item' => trans('messages.information')]);
            }
            return back_normal($request, ['message' => $message, 'status' => 200]);
        } else {
            $message = __('messages.national_code_invalid');
            return back_error($request, ['message' => $message]);
        }
    }

    public function verify_mobile(Request $request)
    {
        if (Auth::user()) {
            $user = Auth::user();
            if (time()-strtotime($user->code_phone_send) < 200) {
                if ($user->code_phone == $request['code']) {
                    $user->phone_verified_at = date("Y-m-d H:i:s");
                    $user->save();
                    User::where('phone',$user->phone)->where('id','!=',$user->id)->update(['phone_verified_at'=>null]);
                    if (!charity_period::where('user_id', $user->id)->exists()){
                        return redirect(route('t_routine_vow'))->with('message', 'شماره شما با موفقیت تایید شد.');
                    }else{
                        return redirect(route('global_profile'))->with('message', 'شماره شما با موفقیت تایید شد.');
                    }
                } else {
                    return back_error($request, __('messages.code_invalid'));
                }
            } else {
                return back_error($request, __('messages.timeout'));
            }
        } else {
            return back_error($request, __('messages.user_not_valid'));
        }
    }

    public function verify_email(Request $request)
    {
        if (Auth::user()) {
            $user = Auth::user();
            if (time()-strtotime($user->code_email_send) < 1800) {

                if ($user->code_email == $request['code']) {
                    $user->email_verified_at = date("Y-m-d H:i:s");
                    $user->save();
                    User::where('email',$user->email)->where('id','!=',$user->id)->update(['email_verified_at'=>null]);

                    return back_normal($request, __('messages.email_verified'));
                } else {
                    return back_error($request, __('messages.code_invalid'));
                }
            } else {
                return back_error($request, __('messages.timeout'));
            }
        } else {
            return back_error($request, __('messages.user_not_valid'));
        }
    }

    public function login_link($slug,Request $request)
    {
        $ct = charity_periods_transaction::where('slug',$slug)
            ->where('payment_date','>=',date("Y-m-d H:i:s",strtotime(date('Y-m-d H:i:s')."-31 days")))
            ->first();
        if (!$ct){
            Log::warning("someone try to login via quick pay link ip = ".$request->ip());
            return redirect(route('home_main'));
        }
        else{
        $user = User::findOrFail($ct['user_id']);
        $user->login_token = Str::random(60);
        $user->Save();
            Log::notice("user with id ".$user['id']." login via quick pay link ip = ".$request->ip());

            return redirect(route('app_profile')."?lt=".$user->login_token);
        }
    }

}
