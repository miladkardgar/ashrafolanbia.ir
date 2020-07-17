<?php

namespace App\Http\Controllers\globals;

use App\blog;
use App\blog_option;
use App\blog_slider;
use App\caravan;
use App\champion_transaction;
use App\charity_champion;
use App\charity_payment_patern;
use App\charity_payment_title;
use App\charity_period;
use App\charity_periods_transaction;
use App\charity_supportForm;
use App\charity_transaction;
use App\charity_transactions_value;
use App\city;
use App\Events\charityPaymentConfirmation;
use App\Events\confirmPhone;
use App\Events\payToCharityMoney;
use App\Events\storePaymentConfirmation;
use App\Events\userRegisterEvent;
use App\gallery_category;
use App\gateway;
use App\gateway_transaction;
use App\media;
use App\order;
use App\orders_item;
use App\setting_transportation;
use App\setting_transportation_cost;
use App\store_product;
use App\store_product_inventory;
use App\store_product_inventory_size;
use App\transaction;
use App\User;
use App\users_address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Larabookir\Gateway\Mellat\Mellat;
use Larabookir\Gateway\Sadad\Sadad;
use Larabookir\Gateway\Saman\Saman;
use phpDocumentor\Reflection\Types\Integer;
use WebDevEtc\BlogEtc\Captcha\UsesCaptcha;
use WebDevEtc\BlogEtc\Middleware\UserCanManageBlogPosts;
use WebDevEtc\BlogEtc\Models\BlogEtcCategory;
use WebDevEtc\BlogEtc\Models\BlogEtcPost;

class global_view extends Controller
{
    use UsesCaptcha;

    public function index()
    {
        return view('global.index');
    }

    public function faq()
    {
        $local = app()->getLocale();
        $faqs = blog_option::where('key', $local)
            ->where('name', 'faq')
            ->get()
            ->map(function ($faq) {
                return [
                    'id' => $faq['id'],
                    'question' => json_decode($faq->value)->question,
                    'answer' => json_decode($faq->value)->answer,
                ];
            });

        return view('global.faq', compact('faqs'));
    }

    public function post_page($blogPostSlug, Request $request)
    {
        $blog_post = BlogEtcPost::where("slug", $blogPostSlug)
            ->firstOrFail();;

        if ($captcha = $this->getCaptchaObject()) {
            $captcha->runCaptchaBeforeShowingPosts($request, $blog_post);
        }

        return view('global.post', [
            'post' => $blog_post,
            // the default scope only selects approved comments, ordered by id
            'comments' => $blog_post->comments()
                ->with("user")
                ->get(),
            'captcha' => $captcha,
        ]);
    }

    public function register_form()
    {

        return view('global.materials.register');
    }

    public function register_form_store(Request $request)
    {
        return response()->json($request);
    }

    public function login_form()
    {
        return view(('global.materials.loginP'));
    }

    public function register_page()
    {
        return view('global.materials.register_page');
    }

    public function login_page()
    {
        return view('global.materials.login_page');
    }

    public function password_reset()
    {
        return view('global.materials.password_reset');
    }

    public function profile_app()
    {

        if (isset($_GET['lt'])){
            $user = User::where('login_token',$_GET['lt'])->first();
            if ($user){
                $user['login_token']='';
                $user->save();
                Auth::loginUsingId($user['id']);
            }
        }
        return redirect(route('global_profile'));
    }

    public function profile_page()
    {
        Artisan::call("cache:clear");

        if (isset($_GET['lt'])){
            $user = User::where('login_token',$_GET['lt'])->first();
            if ($user){
                $user['login_token']='';
                $user->save();
                Auth::loginUsingId($user['id']);
            }
        }

        $periods = charity_period::where('user_id', Auth::id())->get();
        $unpaidPeriod = charity_periods_transaction::where(
            [
                ['status', '=', 'unpaid'],
                ['user_id', '=', Auth::id()],
            ])->get();
        $paidPeriod = charity_periods_transaction::where(
            [
                ['status', '=', 'paid'],
                ['user_id', '=', Auth::id()],
            ])->get();
        $userInfo = User::with('addresses', 'people', 'profile_image')->find(Auth::id());
        return view('global.profile', compact('periods', 'unpaidPeriod', 'userInfo','paidPeriod'));
    }

    public function global_profile_completion()
    {
        $userInfo = User::with('addresses', 'people', 'profile_image')->find(Auth::id());
        return view('global.profile.completion_profile', compact('userInfo'));
    }

    public function caravan_page()
    {
        $active_caravans = caravan::where('duty', \auth()->id())->whereIn('status', ['1', '2', '3', '4'])->get();

        return view('global.profile.caravan', compact('active_caravans', 'caravan_doc'));
    }

    public function involved_projects($id)
    {
//        $project = building_project::find($id);
        $project = null;
        return view('global.profile.building_projects', compact('project'));
    }

    public function change_password()
    {
        return view('global.materials.change_password');
    }

    public function addresses()
    {
        $provinces = city::all();
        $userInfo = User::with('addresses')->findOrFail(Auth::id());
        return view('global.profile.addresses', compact('userInfo', 'provinces'));
    }

    public function send_sms()
    {
        $user = User::findOrFail(Auth::id());
        $user->code_phone = random_int(12320, 98750);
        $user->code_phone_send = date("Y-m-d H:i:s");
        $user->save();

        $smsData = [
            'phone'=>$user->phone,
            'code'=>$user->code_phone,
        ];
        event(new confirmPhone($smsData));

        return redirect(route('global_profile'));
    }

    public function send_email()
    {
        $user = User::findOrFail(Auth::id());
        $user->code_email = random_int(12320, 98750);
        $user->code_email_send = date("Y-m-d H:i:s");
        $user->save();

        // Email EVENT

        return view('global.materials.email');
    }

    public function edit_information()
    {
        $userInfo = User::find(Auth::id());
        return view('global.materials.edit_information', compact('userInfo'));
    }

    public function shop_page()
    {

        $products = store_product::where('status', 'active')->with('store_category', 'store_product_inventory')->get();
        return view('global.store.store', compact('products'));
    }

    public function detail_product(Request $request)
    {

        $proInfo = store_product::where('slug', $request['pro_id'])->with('store_category', 'store_product_inventory')->first();
        if ($proInfo) {
            return view('global.store.details', compact('proInfo'));
        } else {
            return abort(404);
        }
    }

    public function store_cart()
    {
        return view('global.store.cart');
    }

    public function store_order()
    {
        $userInfo = User::with('addresses', 'people')->findOrFail(Auth::id());
        $provinces = city::where('parent', 0)->get();
        return view('global.store.order', compact('userInfo', 'provinces'));
    }

    public function store_order_sub(Request $request)
    {
        $card2 = [
            "extraInfo" => [
                'address' => $request['address'],
                'payment' => $request['payment']
            ]];
        session()->put('info', $card2);

        $items = session('cart');
        $count = 0;
        $price = 0;
        $off = 0;
        foreach ($items['order'] as $item) {
            if ($proInfo = store_product::with('store_product_inventory')->find($item['product_id'])) {
                $count += $item['count'];
                $price += $proInfo['store_product_inventory']['price'] * $item['count'];
                $off += (($proInfo['store_product_inventory']['price'] * $item['count']) * $proInfo['store_product_inventory']['off']) / 100;
            }
        }
        $totalAfterOff = $price - $off;
        $order_info = order::create(
            [
                'user_id' => Auth::id(),
                'count' => $count,
                'address_id' => $request['address'],
                'transportation_id' => 2,
                'payment' => $request['payment'],
                'price' => $price . "0",
                'tax' => 0,
                'discount' => $off,
                'amount' => $totalAfterOff . "0",
            ]);
        foreach ($items['order'] as $item) {
            if ($proInfo = store_product::find($item['product_id'])) {
                $final = $proInfo['price'] * $item['count'];
                orders_item::create([
                    'order_id' => $order_info['id'],
                    'product_id' => $proInfo['id'],
                    'count' => $item['count'],
                    'price' => $proInfo['price'] . "0",
                    'final_price' => $final . "0",
                    'discount' => $item['off'],
                ]);
            }
        }
        $gateways = gateway::where('status', 'active')->get();
        $address = users_address::with('extraInfo', 'city', 'province')->find($request['address']);
        $transport = setting_transportation::find(2);
        $trnasCost = 0;
        if ($trna = setting_transportation_cost::where(
            [
                ['c_id', '=', $address['city_id']],
                ['t_id', '=', 2],
            ]
        )->first()) {
            $trnasCost = $trna['cost'];
        };
        return view('global.store.factor', compact('gateways', 'address', 'trnasCost', 'transport', 'order_info'));
    }

    public function store_order_information()
    {
        $userInfo = User::with('addresses', 'people')->findOrFail(Auth::id());
        $tran = setting_transportation::where('status', "active")->get();
        $gateways = gateway::where('status', 'active')->get();
        return view('global.store.order_information', compact('tran', 'gateways', 'userInfo'));
    }

    public function store_order_factor()
    {
    }

    public function store_payment()
    {
        $tran = setting_transportation::where('status', "active")->get();
        return view('global.store.payment', compact('tran'));
    }

    public function vow_view($id,Request $request)
    {
        $charity = charity_payment_patern::with('fields')->find($request['id']);
        $titles = charity_payment_title::where('ch_pay_pattern_id',$id)->get();
        $gateways = gateway::with('bank')->where('online', 1)->get();
        return view('global.vows.vow', compact('charity', 'gateways','titles'));
    }

    public function vow_payment(Request $request)
    {

        if (!is_null($request['amount'])) {
            $request['amount'] = str_replace(',', '', $request['amount']);
        }
        $patern = charity_payment_patern::find($request['charity_id']);
        $this->validate($request,
            [
                'amount' => 'required|min:' . $patern['min'] . '|max:' . $patern['max'] . '|numeric',
                'gateway' => 'required',
                'email' => 'nullable|email'
            ]);
        $user_id = 0;
        if (Auth::id()) {
            $user_id = Auth::id();
        }
        if (!is_null($patern)) {
            $trans = new charity_transaction();
            $trans->user_id = $user_id OR null;
            $trans->charity_id = $request['charity_id'] OR null;
            $trans->charity_field_id = $request['charity_id'] OR null;
            $trans->name = $request['name'] OR null;
            $trans->phone = $request['phone'] OR null;
            $trans->email = $request['email'] OR null;
            $trans->title_id = $request['title'] OR null;
            $trans->description = $request['description'] OR null;
            $trans->amount = $request['amount'] OR null;
            $trans->gateway_id = $request['gateway'] OR null;
            $trans->status = 'pending';
            $trans->save();
            $transInfo = $trans->id;
            if (isset($request['field'])) {
                foreach ($request['field'] as $item => $value) {
                    if ($value != "") {
                        charity_transactions_value::create(
                            [
                                'trans_id' => $transInfo,
                                'field_id' => $item,
                                'value' => $value
                            ]
                        );
                    }
                }
            }


            $message = trans("messages.transaction_created");
            return back_normal($request, ['message' => $message, "code" => 200, 'id' => $transInfo]);

        } else {
            $message = trans("messages.error");
            return back_normal($request, ['message' => $message, 'code' => 201]);
        }

    }

    public function vow_cart(Request $request)
    {
        $charityIn = charity_periods_transaction::with('period')->findOrFail($request['id']);
        $user = User::with('people')->find($charityIn['user_id']);
        $name = $user['people']['name']." ".$user['people']['family'];
        $gateways = gateway::with('bank')->get();
        return view('global.vows.cart', compact('charityIn', 'gateways','name'));

    }

    public function vow_donate()
    {
        $title = charity_payment_title::where('ch_pay_pattern_id',2)->get();
        $patern = charity_payment_patern::find(2);
        $gateways = gateway::with('bank')->where('online', 1)->get();
        return view('global.vows.donate', compact('title', 'patern', 'gateways'));
    }

    public function vow_period()
    {
        $patern = charity_payment_patern::find(1);
        return view('global.vows.period', compact('patern'));
    }

    public function gallery()
    {
        $medias = gallery_category::where('status', 'active')->with('media', 'media_one', 'media_two')->orderBy('created_at','desc')->paginate('9');
        return view('global.gallery', compact('medias'));
    }

    public function video_gallery()
    {
        $videos = get_video_gallery(10000);
        return view('global.gallery.video_gallery_view', compact('videos'));
    }

    public function gallery_view(Request $request)
    {
        $pics = media::where(
            [
                ['category_id', '=', $request['id']],
                ['thumbnail_size', '=', null],
            ])->get();
        $categoryInfo = gallery_category::find($request['id']);
        return view('global.gallery.gallery_view', compact('pics', 'categoryInfo'));
    }

    public function blog($category_slug = null)
    {
        $this->middleware(UserCanManageBlogPosts::class);

        if ($category_slug) {
            $category = BlogEtcCategory::where("slug", $category_slug)->firstOrFail();
            $posts = $category->posts()->where("blog_etc_post_categories.blog_etc_category_id", $category->id);
        } else {
            $posts = BlogEtcPost::query();
        }

//        $posts = $posts->orderBy("posted_at", "desc")
//            ->paginate(config("blogetc.per_page", 10));


        $posts = BlogEtcPost::orderBy("posted_at", "desc")
            ->paginate(10);
        return view("global.blog", ['posts' => $posts]);

        return view('global.blog', compact('posts'));
    }

    public function TopPosts($category_slug = null)
    {
        $this->middleware(UserCanManageBlogPosts::class);

        $posts = get_posts(null, ['last_post'], [], 15);

        return view("global.blog", ['posts' => $posts]);
    }

    public function payment($type, $id, Request $request)
    {
        $con = true;
        $vow = array('charity_vow', 'charity_donate');
        $info = '';
        if (in_array($type, $vow)) {
            $info = charity_transaction::find($id);
        } elseif ($request['type'] == "charity_period") {
            $info = charity_periods_transaction::findOrFail($id);
            $info->gateway_id = $request['gateway_id'];
            $info->save();
            $info = charity_periods_transaction::findOrFail($id);
            if ($info['user_id']) {
                if ($info['user_id'] != Auth::id() || $info['status'] != "unpaid") {
                    $con = false;
                }
            }
        } elseif ($type == "charity_champion") {
            $info = champion_transaction::findOrFail($id);
            $info->gateway_id = $request['gateway_id'];
            $info->save();
            $info = champion_transaction::findOrFail($id);
        } elseif ($type == 'shop') {
            $info = order::find($id);
            $info->gateway_id = $request['gateway_id'];
            $info->save();
            $info = order::find($id);
        }
        if (!is_null($info) && $con) {
            $gatewayInfo = gateway::findOrFail($info['gateway_id']);
            if ($gatewayInfo['function_name'] == "SamanGateway") {
                try {
                    $gateway = \Gateway::make(new Saman());
                    $gateway->setCallback(route('callback', ['gateway' => 'saman']));
                    $gateway->moduleSet($request['type'])->moduleIDSet($info['id']);
                    $gateway->price($info['amount'])->ready();
                    $refId = $gateway->refId();
                    $transID = $gateway->transactionId();

                    $info->trans_id = $transID;
                    $info->gateway_id = $gatewayInfo['id'];
                    $info->save();
                    return $gateway->redirect();

                } catch (\Exception $e) {
                    echo $e->getMessage();
                }
            } elseif ($gatewayInfo['function_name'] == "MellatGateway") {
                try {
                    $gateway = \Larabookir\Gateway\Gateway::make(new Mellat());
                    $gateway->setCallback(route('callback', ['gateway' => 'mellat']));
                    $gateway->price($info['amount'])->moduleSet($request['type'])->moduleIDSet($info['id'])->ready();
                    $refId = $gateway->refId();
                    $transID = $gateway->transactionId();

                    $info->trans_id = $transID;
                    $info->gateway_id = $gatewayInfo['id'];
                    $info->save();
                    return $gateway->redirect();

                } catch (\Exception $e) {
                    echo $e->getMessage();
                }
            }elseif ($gatewayInfo['function_name'] == "MelliGateway") {
                try {
                    $gateway = \Larabookir\Gateway\Gateway::make(new Sadad());

                    $gateway->setCallback(route('callback', ['gateway' => 'sadad']));
                    $gateway->price($info['amount'])->moduleSet($request['type'])->moduleIDSet($info['id'])->ready();
                    $refId = $gateway->refId();
                    $transID = $gateway->transactionId();

                    $info->trans_id = $transID;
                    $info->gateway_id = $gatewayInfo['id'];
                    $info->save();
                    return $gateway->redirect();

                } catch (\Exception $e) {
                    echo $e->getMessage();
                }
            }
        } else {
            return back_normal($request, ['message' => trans('errors.payment_not_valid')]);
        }
    }

    public function callback(Request $request)
    {
        $res = false;
        try {
            $gateway = \Gateway::verify();
            $trackingCode = $gateway->trackingCode();
            $refId = $gateway->refId();
            $cardNumber = $gateway->cardNumber();
            $res = true;

        } catch (\Larabookir\Gateway\Exceptions\RetryException $e) {

            // تراکنش قبلا سمت بانک تاییده شده است و
            // کاربر احتمالا صفحه را مجددا رفرش کرده است
            // لذا تنها فاکتور خرید قبل را مجدد به کاربر نمایش میدهیم

//            echo $e->getMessage() . "<br>";

            $messages['message'] = $e->getMessage();
            $messages['result'] = "repeat";

        } catch (\Exception $e) {

            // نمایش خطای بانک
//            echo $e->getMessage();
            $messages['message'] = $e->getMessage();
            $messages['result'] = "repeat";
        }

        if ($res == true) {
            $phone = null;
            $email = "info@ashrafolanbia.ir";
            $amount = 0;
            $reason = "";
            $name = "";
            $gateway = config('gateway.table', 'gateway_transactions');
            $data = \DB::table($gateway)->find($request['transaction_id']);
            $date = jdate("Y/m/d",strtotime($data->created_at));
            if ($data->module == "charity_donate" || $data->module == "charity_vow") {
                $charity = charity_transaction::with('title')->findOrFail($data->module_id);
                $charity->status = 'success';
                $charity->trans_id = $data->id;
                $charity->payment_date = date("Y-m-d H:i:s", time());
                if ($charity['user_id'] != 0) {
                    $user = User::find($charity['user_id']);
                    $phone = $user['phone'];
                    $email = $user['email'];
                } else {
                    if ($charity['phone'] != "") {
                        $phone = $charity['phone'];
                        $email = $charity['email'];
                    }
                }
                $charity->save();
                $name = $charity->name;
                $amount = $charity->amount;
                    $this_charity = charity_payment_title::find($charity->title_id);
                $reason =($this_charity ? $this_charity['title']:" خیریه ");

                $messages['des'] = $charity['title']['title'];
            } elseif ($data->module == "charity_period") {
                $charity = charity_periods_transaction::findOrFail($data->module_id);
                $charity->status = 'paid';
                $charity->trans_id = $data->id;
                $charity->pay_date = date("Y-m-d H:i:s", time());
                $messages['des'] = __('messages.charity_period');
                $user = User::find($charity['user_id']);
                $charity->save();
                $phone = $user['phone'];
                $email = $user['email'];

                if($user->people){
                    $name = ($user->people->gender == 1 ? " آقای " :" خانم "). $user->people->name." ".$user->people->family;
                }
                $amount = $charity->amount;
                $reason =$charity->description;


            } elseif ($data->module == "charity_champion") {
                $charity = champion_transaction::with('champion')->findOrFail($data->module_id);
                $charity->status = 'paid';
                $charity->trans_id = $data->id;
                $messages['des'] = $charity['champion']['title'];
                if ($charity['user_id'] != 0) {
                    $user = User::find($charity['user_id']);
                    $phone = $user['phone'];
                    $email = $user['email'];
                    $name = ($user->people->gender == 1 ? " آقای " :" خانم "). $user->people->name." ".$user->people->family;
                } else {
                    if ($charity['phone'] != "") {
                        $phone = $charity['phone'];
                        $email = $charity['email'];
                        $name = $charity['name'];
                    }
                }
                $charity->save();

                $sum = champion_transaction::where(
                    [
                        'champion_id' => $charity['champion_id'],
                        'status' => 'paid'
                    ]
                )->sum('amount');
                charity_champion::where('id', $charity['champion_id'])->update(
                    [
                        'raised' => $sum
                    ]
                );
                $chapion = charity_champion::find($charity['champion_id']);
                $amount = $charity->amount;
                $reason = ($chapion ? $chapion['title']:" کمپین خیریه ");



            } elseif ($data->module == "shop") {
                $charity = order::findOrFail($data->module_id);
                $charity->status = 'paid';
                $charity->pay_date = date("Y-m-d H:i:s", time());
                $charity->save();
                session()->forget('cart');
                session()->forget('info');
                $messages['des'] = __('messages.shop_order');
                $user = User::find($charity['user_id']);
                event(new storePaymentConfirmation($user));
            }
            $messages['result'] = "success";
            $messages['name'] = $charity->name;
            $messages['trackingCode'] = $request['transaction_id'];
            $messages['date'] = jdate("Y/m/d");

            $messages['amount'] = number_format($charity->amount) . " " . __('messages.rial');

            if($phone and $amount>0){
                $smsData = [
                    'phone'=> $phone,
                    'name'=>$name,
                    'date'=>$date,
                    'price'=>number_format($amount),
                    'reason'=>$reason,
                ];
                $mailData = [
                    'address'=> $email,
                    'messages'=>$messages,
                ];
                event(new payToCharityMoney($smsData,$mailData));
            }
            $messages['share']=
                "رسید پرداخت"." %0D%0A ".
                " %0D%0A ".
                "نام خیر:".$name." %0D%0A ".
                "مبلغ:".number_format($amount)." %0D%0A ".
                "در تاریخ:".$date." %0D%0A ".
                ($reason?"بابت:".$reason." %0D%0A ":"").
                ($messages['trackingCode']?"شماره پیگیری:".$messages['trackingCode']." %0D%0A ":"").
                " %0D%0A ".
                "موسسه خیریه اشرف الانبیا(ص)"." %0D%0A ";

            return view('global.callbackmain', compact('messages'));
        } else {
            $gateway = config('gateway.table', 'gateway_transactions');
            $data = \DB::table($gateway)->find($request['transaction_id']);
            if ($data->module == "charity_donate" || $data->module == "charity_vow") {
                $charity = charity_transaction::findOrFail($data->module_id);
                $charity->status = 'fail';
                $charity->trans_id = $data->id;
                $charity->payment_date = date("Y-m-d H:i:s", time());
                $charity->save();
            } elseif ($data->module == "charity_champion") {
                $charity = champion_transaction::findOrFail($data->module_id);
                $charity->status = 'fail';
                $charity->trans_id = $data->id;
                $charity->payment_date = date("Y-m-d H:i:s", time());
                $charity->save();
            } elseif ($data->module == "shop") {
                $charity = order::findOrFail($data->module_id);
                $charity->status = 'fail';
                $charity->trans_id = $data->id;
                $charity->save();
            }
            $messages['result'] = "fail";
            return view('global.callback', compact('messages'));
        }
    }

    public function champion_show($id)
    {
        $champion = charity_champion::with('image', 'projects', 'transaction')->where('slug', $id)->first();
        $champions = charity_champion::with('image')->orderBy('created_at', 'desc')->limit(3)->get();

        return view('global.champion.champion', compact('champion', 'champions'));
    }

    public function champion_cart($id)
    {
        $champion = champion_transaction::with('champion')->findOrFail($id);
        $gateways = gateway::with('bank')->where('online', 1)->get();
        return view('global.champion.cart', compact('champion', 'gateways'));
    }

    public function reset_password()
    {
        return view('auth.passwords.email');
    }

    public function weblist()
    {
        $list = BlogEtcPost::all();
        return response()->json($list);
    }

    public function t_profile(){

        if (isset($_GET['lt'])){
            $user = User::where('login_token',$_GET['lt'])->first();
            if ($user){
                $user['login_token']='';
                $user->save();
                Auth::loginUsingId($user['id']);
            }
        }
        $period = charity_period::where('user_id', Auth::id())->first();

        $unpaidPeriod = charity_periods_transaction::where(
            [
                ['status', '=', 'unpaid'],
                ['user_id', '=', Auth::id()],
            ])->get();
        $unpaidPeriodCount = charity_periods_transaction::where(
            [
                ['status', '=', 'unpaid'],
                ['user_id', '=', Auth::id()],
            ])->count();
        $paidPeriodCount = charity_periods_transaction::where(
            [
                ['status', '=', 'paid'],
                ['user_id', '=', Auth::id()],
            ])->count();
        $paidPeriodAmount = charity_periods_transaction::where(
            [
                ['status', '=', 'paid'],
                ['user_id', '=', Auth::id()],
            ])->sum('amount');

        return view('global.t-profile.index',compact('period','unpaidPeriod','unpaidPeriodCount','paidPeriodAmount','paidPeriodCount'));
    }
}
