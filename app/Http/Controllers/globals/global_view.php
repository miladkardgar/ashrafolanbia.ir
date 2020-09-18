<?php

namespace App\Http\Controllers\globals;

use App\blog;
use App\blog_option;
use App\blog_slider;
use App\c_store_order;
use App\c_store_order_item;
use App\c_store_product;
use App\c_store_product_image;
use App\c_store_setting;
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
use App\Events\c_storePaymentAlert;
use App\Events\charityPaymentConfirmation;
use App\Events\confirmPhone;
use App\Events\payToCharityMoney;
use App\Events\storePaymentConfirmation;
use App\Events\userRegisterEvent;
use App\gallery_category;
use App\gateway;
use App\gateway_transaction;
use App\Http\HijriDate;
use App\Mail\confirmEmail;
use App\media;
use App\notification;
use App\order;
use App\orders_item;
use App\Scopes\nonGroupPayment;
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
use Illuminate\Support\Facades\Hash;
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

        if (isset($_GET['lt'])) {
            $user = User::where('login_token', $_GET['lt'])->first();
            if ($user) {
                $user['login_token'] = '';
                $user->save();
                Auth::loginUsingId($user['id']);
            }
        }
        return redirect(route('global_profile'));
    }

    public function profile_page()
    {
        Artisan::call("cache:clear");

        if (isset($_GET['lt'])) {
            $user = User::where('login_token', $_GET['lt'])->first();
            if ($user) {
                $user['login_token'] = '';
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
        return view('global.profile', compact('periods', 'unpaidPeriod', 'userInfo', 'paidPeriod'));
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

    public function send_sms(Request $request)
    {
        $user = User::findOrFail(Auth::id());
        if ($request->mobile) {
            $user->phone = $request->mobile;
        }
        $user->code_phone = random_int(12320, 98750);
        $user->code_phone_send = date("Y-m-d H:i:s");
        $user->save();

        $smsData = [
            'phone' => $user->phone,
            'code' => $user->code_phone,
        ];
        event(new confirmPhone($smsData));

        return redirect(route('global_profile'));
    }

    public function send_email(Request $request)
    {
        $user = Auth::user();
        if ($request->email) {
            $user->email = $request->email;
        }
        if ($user->email) {

            $user->code_email = random_int(12320, 98750);
            $user->code_email_send = date("Y-m-d H:i:s");
            $user->save();

            $mailData = [
                'address' => $user->email,
                'code' => $user->code_email,
            ];
            event(new \App\Events\confirmEmail($mailData));
        }
        return redirect(route('global_profile_change_password'));
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

    public function vow_view($id, Request $request)
    {
        $charity = charity_payment_patern::with('fields')->find($request['id']);
        $titles = charity_payment_title::where('ch_pay_pattern_id', $id)->get();
        $gateways = gateway::with('bank')->where('online', 1)->get();
        $user = null;
        if (Auth::user()) {
            $user['name'] = Auth::user()->people['name'] . " " . Auth::user()->people['family'];
            $user['phone'] = Auth::user()->phone;
            $user['email'] = Auth::user()->email;
        }

        return view('global.vows.vow', compact('charity', 'gateways', 'titles', 'user'));
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
        } else {
            $user = User::where('phone', $request['phone'])->first();
            if ($user) {
                $user_id = $user['id'];
            }
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
        $charityIn = charity_periods_transaction::with('period')
            ->withoutGlobalScope(nonGroupPayment::class)->findOrFail($request['id']);
        $user = User::with('people')->find($charityIn['user_id']);
        $name = $user['people']['name'] . " " . $user['people']['family'];
        $gateways = gateway::with('bank')->get();

        return view('global.vows.cart', compact('charityIn', 'gateways', 'name'));

    }

    public function vow_donate()
    {
        $title = charity_payment_title::where('ch_pay_pattern_id', 2)->get();
        $patern = charity_payment_patern::find(2);
        $gateways = gateway::with('bank')->where('online', 1)->get();
        $user = null;
        if (Auth::user()) {
            $user['name'] = get_name(Auth::user()->id);
            $user['phone'] = Auth::user()->phone;
            $user['email'] = Auth::user()->email;
        }

        return view('global.vows.donate', compact('title', 'patern', 'gateways', 'user'));
    }

    public function vow_period()
    {
        $patern = charity_payment_patern::find(1);
        return view('global.vows.period', compact('patern'));
    }

    public function gallery()
    {
        $medias = gallery_category::where('status', 'active')->with('media', 'media_one', 'media_two')->orderBy('created_at', 'desc')->paginate('9');
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
        $categoryInfo = gallery_category::findOrFail($request['id']);
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
            $info = charity_periods_transaction::withoutGlobalScope(nonGroupPayment::class)->findOrFail($id);
            $info->gateway_id = $request['gateway_id'];
            $info->save();
//            $info = charity_periods_transaction::findOrFail($id);
            if ($info['user_id']) {
                if ($info['status'] != "unpaid") {
                    $con = false;
                }
            }
        } elseif ($type == "charity_champion") {
            $info = champion_transaction::findOrFail($id);
            $info->gateway_id = $request['gateway_id'];
            $info->save();
        } elseif ($type == 'shop') {
            $info = order::find($id);
            $info->gateway_id = $request['gateway_id'];
            $info->save();
        } elseif ($type == 'c_store') {
            $info = c_store_order::find($id);
            $info->gateway_id = $request['gateway_id'];
            $info->save();
        }

        if (!is_null($info) && $con) {

            $gatewayInfo = gateway::findOrFail($info['gateway_id']);

            if ($gatewayInfo['function_name'] == "SamanGateway") {
                try {
                    $gateway = \Gateway::make(new Saman());
                    $gateway->setCallback(route('callback', ['gateway' => 'saman']));
                    $gateway->moduleSet($type)->moduleIDSet($info['id']);
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
                    $gateway->price($info['amount'])->moduleSet($type)->moduleIDSet($info['id'])->ready();
                    $refId = $gateway->refId();
                    $transID = $gateway->transactionId();

                    $info->trans_id = $transID;
                    $info->gateway_id = $gatewayInfo['id'];
                    $info->save();
                    return $gateway->redirect();

                } catch (\Exception $e) {
                    echo $e->getMessage();
                }
            } elseif ($gatewayInfo['function_name'] == "MelliGateway") {
                try {
                    $gateway = \Larabookir\Gateway\Gateway::make(new Sadad());

                    $gateway->setCallback(route('callback', ['gateway' => 'sadad']));
                    $gateway->price($info['amount'])->moduleSet($type)->moduleIDSet($info['id'])->ready();
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
            $date = jdate("Y/m/d", strtotime($data->created_at));
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
                $reason = ($this_charity ? $this_charity['title'] : " ایتام و محرومین ");

                $messages['des'] = $charity['title']['title'];
            } elseif ($data->module == "charity_period") {
                $charity = charity_periods_transaction::withoutGlobalScope(nonGroupPayment::class)->findOrFail($data->module_id);
                $charity->status = 'paid';
                $charity->trans_id = $data->id;
                $charity->pay_date = date("Y-m-d H:i:s", time());
                $messages['des'] = __('messages.charity_period');
                $user = User::find($charity['user_id']);
                $charity->save();

                if ($charity->group_pay) {
                    $groupIds = json_decode($charity->group_ids);
                    foreach ($groupIds as $groupId) {
                        $groupItem = charity_periods_transaction::find($groupId);
                        if ($groupItem) {
                            $groupItem->status = 'paid';
                            $groupItem->trans_id = $data->id;
                            $groupItem->pay_date = date("Y-m-d H:i:s", time());
                            $groupItem->gateway_id = $charity->gateway_id;
                            $groupItem->save();
                        }
                    }
                }

                $phone = $user['phone'];
                $email = $user['email'];

                if ($user->people) {
                    $name = ($user->people->gender == 1 ? " آقای " : " خانم ") . $user->people->name . " " . $user->people->family;
                }
                $amount = $charity->amount;
                $reason = $charity->description;


            } elseif ($data->module == "charity_champion") {
                $charity = champion_transaction::with('champion')->findOrFail($data->module_id);
                $charity->status = 'paid';
                $charity->trans_id = $data->id;
                $messages['des'] = $charity['champion']['title'];
                $user=null;
                if ($charity['user_id'] != 0) {
                    $user = User::find($charity['user_id']);
                    $phone = $user['phone'];
                    $email = $user['email'];
                    $name = ($user->people->gender == 1 ? " آقای " : " خانم ") . $user->people->name . " " . $user->people->family;
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
                $reason = ($chapion ? $chapion['title'] : " کمپین خیریه ");


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
            } elseif ($data->module == "c_store") {
                $charity = c_store_order::findOrFail($data->module_id);
                $charity->status = 'paid';
                $charity->pay_date = date("Y-m-d H:i:s", time());
                $charity->save();
                session()->forget('c_store_cart');
                session()->forget('cs_order');
                $messages['des'] = __('messages.shop_order');
                $user = User::find($charity['user_id']);
                event(new storePaymentConfirmation($user));
                $phone = $user['phone'];
                $amount = $charity->amount;
                $reason = 'سفارش تاج گل/استند';

                $alert_phones = explode(',', c_store_setting::where('key', 'phones')->first()['value']);
                foreach ($alert_phones as $alert_phone) {
                    $smsData = [
                        'phone' => $alert_phone,
                        'c_phone' => $phone,
                        'title' => "تاج گل و استند",
                        'meeting_date' => miladi_to_shamsi_date($charity['date']),
                    ];
                    event(new c_storePaymentAlert($smsData));
                }
            }
            $messages['result'] = "success";
            $messages['name'] = (isset($charity) and isset($charity->name)) ? $charity->name : get_name($user['id']);
            $messages['trackingCode'] = $request['transaction_id'];
            $messages['date'] = jdate("Y/m/d");

            $messages['amount'] = number_format($charity->amount) . " " . __('messages.rial');

            if ($phone and $amount > 0) {
                $smsData = [
                    'phone' => $phone,
                    'name' => $name,
                    'date' => $date,
                    'price' => number_format($amount),
                    'reason' => $reason,
                ];
                $mailData = [
                    'address' => $email,
                    'messages' => $messages,
                ];
                event(new payToCharityMoney($smsData, $mailData));
            }
            $messages['share'] =
                "رسید پرداخت" . " %0D%0A " .
                " %0D%0A " .
                "نام نیکوکار:" . $name . " %0D%0A " .
                "مبلغ:" . number_format($amount) . " %0D%0A " .
                "در تاریخ:" . $date . " %0D%0A " .
                ($reason ? "بابت:" . $reason . " %0D%0A " : "") .
                ($messages['trackingCode'] ? "شماره پیگیری:" . $messages['trackingCode'] . " %0D%0A " : "") .
                " %0D%0A " .
                "موسسه خیریه اشرف الانبیا(ص)" . " %0D%0A ";

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
            } elseif ($data->module == "c_store") {
                $charity = c_store_order::findOrFail($data->module_id);
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

    public function t_profile()
    {

        if (isset($_GET['lt'])) {
            $user = User::where('login_token', $_GET['lt'])->first();
            if ($user) {
                $user['login_token'] = '';
                $user->save();
                Auth::loginUsingId($user['id']);
            }
        }
        $period = charity_period::where('user_id', Auth::id())->first();

        $history = charity_periods_transaction::where(
            [
                ['status', '=', 'unpaid'],
                ['user_id', '=', Auth::id()],
            ])->orderBy('payment_date', 'DESC')->paginate(50);
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
        $notifications = notification::where('start',"<=",date("Y-m-d H:i:s"))
            ->where('end',">=",date('Y-m-d H:i:s'))->get()->map(function ($notice){
                return[
                  'title'=>$notice->title,
                  'body'=>$notice->body,
                ];
            });
        return view('global.t-profile.index', compact('period', 'history', 'unpaidPeriodCount', 'paidPeriodAmount', 'paidPeriodCount','notifications'));
    }

    public function t_payment_history()
    {

        $history = charity_periods_transaction::where(
            [
                ['user_id', '=', Auth::id()],
                ['status', '=', 'paid'],
            ])->orderBy('payment_date', "DESC")->with('period')->paginate(20);
        $otherHistory = charity_transaction::where(
            [
                ['user_id', '=', Auth::id()],
                ['status', '=', 'success'],
            ]
        )->with('patern', 'title')->get();
        return view('global.t-profile.pay_history', compact('history', 'otherHistory'));
    }

    public function t_addresses()
    {

        $provinces = city::all();
        $userInfo = User::with('addresses')->findOrFail(Auth::id());
        return view('global.t-profile.addresses', compact('userInfo', 'provinces'));
    }

    public function t_edit_profile()
    {

        $userInfo = User::with('addresses', 'people', 'profile_image')->find(Auth::id());

        return view('global.t-profile.edit', compact('userInfo'));
    }

    public function t_routine_vow()
    {


        $routine = charity_period::where('user_id', Auth::id())->first();
        $routine_types = config('charity.routine_types');
        $pattern = charity_payment_patern::where('periodic', '1')->first();
        return view('global.t-profile.vow', compact('routine', 'pattern', 'routine_types'));
    }

    public function t_routine_payment(Request $request)
    {
        $group_id = [];
        $total_amount = 0;
        $this_routine = null;
        if ($request['payment'] and is_array($request['payment'])) {
            foreach ($request['payment'] as $payment_id) {
                $this_routine = charity_periods_transaction::whereNull('pay_date')->find($payment_id);
                if ($this_routine) {
                    $group_id[] = $this_routine['id'];
                    $total_amount += $this_routine['amount'];
                }
            }
        }
        if ($total_amount > 0) {
            $new_pay = charity_periods_transaction::create(
                [
                    'user_id' => Auth::id(),
                    'period_id' => $this_routine['period_id'],
                    'payment_date' => date("Y-m-d H:i:s"),
                    'amount' => $total_amount,
                    'description' => "پرداخت کمک ماهانه / هفتگی",
                    'status' => "unpaid",
                    'group_ids' => json_encode($group_id),
                    'group_pay' => 1,
                ]
            );
            return redirect(route('vow_cart', ['id' => $new_pay['id']]));
        }
        return back();

    }

    public function t_c_store($id=null)
    {
        $order = null;
        if ($id){
            $order = c_store_order::where('user_id',Auth::id())->with('items')->findOrFail($id);
        }
        $orders = c_store_order::where('user_id',Auth::id())->where('status','paid')->paginate(20);
        return view('global.t-profile.c_store_history', compact('orders','order'));
    }

    public function c_store()
    {
        $description = json_decode(c_store_setting::where('key','description ')->first()['value']);
        $products = c_store_product::where('active', 1)->get()->map(function ($product) {
            $image = c_store_product_image::where('CSP_id', $product['id'])->where('main_img', 1)->first();
            return [
                'id' => $product['id'],
                'title' => $product['title'],
                'description' => $product['description'],
                'slug' => $product['slug'],
                'price' => $product['price'],
                'image' => $image ? $image['medium'] : "http://lorempixel.com/output/nature-q-c-640-394-5.jpg",
            ];
        });
        return view('global.c_store.index', compact('products','description'));
    }

    public function c_store_show($slug)
    {

        $product = c_store_product::with('images')
            ->where('slug', $slug)
            ->where('active', 1)
            ->firstOrFail();
        $image = c_store_product_image::where('CSP_id', $product['id'])->first();
        return view('global.c_store.show', compact('product', 'image'));
    }

    public function c_store_checkout()
    {

        $card = session()->get('c_store_cart');
        if (!$card) {
            $card = [];
        }

        return view('global.c_store.checkout', compact('card'));
    }

    public function c_store_add_to_card(Request $request)
    {
        $this->validate($request,
            [
                'product' => 'required',
            ]);
        $product = c_store_product::findOrFail($request['product']);
        $cart = session()->get('c_store_cart');
        $image = c_store_product_image::where('CSP_id', $product['id'])->where('main_img', 1)->exists() ? c_store_product_image::where('CSP_id', $product['id'])->where('main_img', 1)->first()['large'] : "";
        if (!$cart) {
            $cart = [
                $product['id'] => [
                    "name" => $product->title,
                    "quantity" => 1,
                    "price" => $product->price,
                    "slug" => $product->slug,
                    "image" => $image,
                    "provinces" => $product->allowed_provinces,
                    "cities" => $product->allowed_cities,
                    "delay" => $product->delivery_delay,
                    "delay_type" => $product->delivery_delay_type,
                ]
            ];

            session()->put('c_store_cart', $cart);

            return redirect(route('global.c_store_checkout'));
        }

        // if cart not empty then check if this product exist then increment quantity
        if (isset($cart[$product['id']])) {

            $cart[$product['id']]['quantity']++;

            session()->put('c_store_cart', $cart);

            return redirect(route('global.c_store_checkout'));

        }
        // if item not exist in cart then add to cart with quantity = 1
        $cart[$product['id']] = [
            "name" => $product->title,
            "quantity" => 1,
            "price" => $product->price,
            "slug" => $product->slug,
            "image" => $image,
            "provinces" => $product->allowed_provinces,
            "cities" => $product->allowed_cities,
            "delay" => $product->delivery_delay,
            "delay_type" => $product->delivery_delay_type,
        ];

        session()->put('c_store_cart', $cart);

        return redirect(route('global.c_store_checkout'));

    }

    public function c_store_remove_from_card($id, Request $request)
    {
        $cart = session()->get('c_store_cart');
        if ($cart) {
            unset($cart[$id]);
            session()->put('c_store_cart', $cart);
        }
        return back_normal($request, 'آیتم حذف شد');
    }

    public function c_store_update_card(Request $request)
    {
        $cart = [];
        session()->put('c_store_cart', $cart);
        foreach ($request['quantity'] as $key => $value) {
            $product = c_store_product::find($key);
            if ($product and $value > 0) {
                $image = c_store_product_image::where('CSP_id', $product['id'])->where('main_img', 1)->exists() ? c_store_product_image::where('CSP_id', $product['id'])->where('main_img', 1)->first()['large'] : "";
                $cart[$product['id']] = [
                    "name" => $product->title,
                    "quantity" => $value,
                    "price" => $product->price,
                    "slug" => $product->slug,
                    "image" => $image,
                    "provinces" => $product->allowed_provinces,
                    "cities" => $product->allowed_cities,
                    "delay" => $product->delivery_delay,
                    "delay_type" => $product->delivery_delay_type,
                ];
            }
        }
        session()->put('c_store_cart', $cart);
        return back_normal($request, 'سبد بروزرسانی شد');
    }

    public function c_store_card_completion_phone(Request $request)
    {
        $user = Auth::user();
        if ($user and $user['phone_verified_at']) {
            return redirect(route('global.c_store_card_completion_order'));
        } else {

            $phone = '';
            if ($request->phone) {
                $phone = $request->phone;
            }
            return view('global.c_store.partial.get_phone', compact(['phone']));
        }

    }

    public function c_store_card_completion_submit_phone(Request $request)
    {

        $this->validate($request,
            [
                'phone' => 'required|regex:/(09)[0-9]{9}/',
            ]);
        $user = Auth::user();
        if ($user and $user['phone_verified_at']) {
            return redirect(route('global.c_store_card_completion_order'));
        } else {

            $phone = $request['phone'];
            $code = rand(11111, 99999);
            $user = User::where('phone', $request['phone'])->first();
            if ($user) {
                if ($user->code_phone and $user->code_phone_send and (strtotime($user->code_phone_send) + 180 < time())) {
                    $user->code_phone = $code;
                    $user->code_phone_send = date("Y-m-d H:i:s");
                    $user->save();
                }
                $smsData = [
                    'phone' => $user->phone,
                    'code' => $user->code_phone,
                ];
                event(new confirmPhone($smsData));
            } else {
                $user = new User();
                $user->phone = $phone;
                $user->code_phone = $code;
                $user->password = Hash::make($code);
                $user->code_phone_send = date("Y-m-d H:i:s");
                $user->save();

                $smsData = [
                    'phone' => $user->phone,
                    'code' => $user->code_phone,
                ];
                event(new confirmPhone($smsData));
            }
            return redirect(route('global.c_store_card_completion_submit_phone_page', ['phone' => $user->phone]));
        }

    }

    public function c_store_card_completion_submit_code_page(Request $request, $phone)
    {

        $user = Auth::user();
        if ($user and $user['phone_verified_at']) {
            return redirect(route('global.c_store_card_completion_order'));
        } else {
            return view('global.c_store.partial.submit_code', compact('phone'));
        }

    }

    public function c_store_resend_code(Request $request)
    {
        $phone = $request['phone'];
        $user = User::where('phone', $phone)->first();
        if (!$user) {
            return redirect(route('global.c_store_card_completion_phone'));
        } else {
            if (!empty($user->code_phone) and !empty($user->code_phone_send) and ((strtotime($user->code_phone_send) + 180) < time())) {
                $code = rand(11111, 99999);
                $user->code_phone = $code;
                $user->code_phone_send = date("Y-m-d H:i:s");
                $user->save();
            }

            $smsData = [
                'phone' => $user->phone,
                'code' => $user->code_phone,
            ];
            event(new confirmPhone($smsData));

            return redirect(route('global.c_store_card_completion_submit_phone_page', ['phone' => $user->phone]));
        }
    }

    public function c_store_card_completion_submit_code(Request $request)
    {

        $this->validate($request, [
            'phone' => 'required',
            'code' => 'required',
        ]);
        $user = User::where('phone', $request['phone'])->first();
        if ($user['code_phone'] == $request['code']) {
            session()->put('c_store_phone', $request['phone']);
            return redirect(route('global.c_store_card_completion_order'));
        } else {
            return back_error($request, ['خطا' => 'کد وارد شده صحیح نمی باشد.']);
        }

    }

    public function c_store_card_completion_order(Request $request)
    {
        $user = Auth::user();
        $phone = session()->get('c_store_phone');
        $card = session()->get('c_store_cart');
        $allowed_provinces = get_provinces()->pluck('id')->toArray();
        $allowed_cities = get_cites()->pluck('id')->toArray();
        $actual_day_delay = 0;
        $working_day_delay = 0;
        if (!$card) {
            return redirect(route('global.c_store'));
        }
        foreach ($card as $key => $value) {
//            if ($value['provinces']){
//                $allowed_provinces = array_intersect($allowed_provinces,explode(',',$value['provinces'])) ;
//            };
//
//            if ($value['cities']){
//                $allowed_cities = array_intersect($allowed_cities,explode(',',$value['cities'])) ;
//            };

            if ($value['delay_type'] == 'actual_day' and $actual_day_delay < $value['delay']) {
                $actual_day_delay = $value['delay'];
            } elseif ($value['delay_type'] == 'working_day' and $working_day_delay < $value['delay']) {
                $working_day_delay = $value['delay'];
            }
        }

        $allowed_provinces = array_map(function ($province) {
            return [
                'id' => $province,
                'name' => get_provinces($province)['name'],
            ];
        }, $allowed_provinces);

        $allowed_cities = array_map(function ($city) {
            return [
                'id' => $city,
                'name' => get_cites($city)['name'],
            ];
        }, $allowed_cities);

        $working_day_delay = $this->working_day_delay($working_day_delay);

        $delay = max($working_day_delay, $actual_day_delay);
        $firstDate = time() + ($delay * 86400);

        if (!$user and $phone) {
            $user = User::where('phone', $phone)->first();
        } elseif (!$user and !$phone) {
            return redirect(route('global.c_store_checkout'));
        }
        $order = session()->get('cs_order');
        return view('global.c_store.order_data', compact('order', 'firstDate', 'user', 'allowed_provinces', 'allowed_cities'));
    }

    private function working_day_delay($days)
    {
        $delay = 0;
        if ($days <= 0) {
            return 0;
        }
        $balance = 0;


        for ($i = 0; $i <= ($days + $balance); $i++) {
            $off = false;
            $timestamp = time() + ($i * 86400);
            $hijri = new HijriDate($timestamp);
            $icD = $hijri->get_day();
            $icM = $hijri->get_month();
            $response = getEvents(tr_num(jdate('j', $timestamp)), tr_num(jdate('m', $timestamp)), $icD, $icM);
            if (json_decode($response)->values) {
                foreach (json_decode($response)->values as $value) {
                    if ($value->dayoff) {
                        $days++;
                        $off = true;
                        break;
                    };
                }
                $wd_end_time = c_store_setting::where('key', 'end_time')->first()['value'];
                $wd_end_time = $wd_end_time ? latin_num($wd_end_time) : "12:00";
                if ($i == 0 and !$off and time() > strtotime($wd_end_time . ':00')) {
                    $balance = 1;
                }
            }
            $delay++;
        }
        return $delay;
    }

    public function c_store_card_completion_order_save(Request $request)
    {
        $this->validate($request,
            [
                'province' => 'required',
                'cities' => 'required',
                'receiver' => 'required',
                'zip_code' => 'nullable',
                'phone' => 'nullable',
                'mobile' => 'required',
                'description' => 'nullable',
                'condolences_to' => 'required',
                'from_as' => 'required',
                'late_name' => 'required',
                'meeting_date' => 'required',
                'meeting_time' => 'required',
                'meeting_address' => 'required',
                'lat' => 'nullable',
                'lon' => 'nullable',
            ]
        );
        $user = Auth::user();
        $card = session()->get('c_store_cart');
        $allowed_provinces = get_provinces()->pluck('id')->toArray();
        $allowed_cities = get_cites()->pluck('id')->toArray();
        $actual_day_delay = 0;
        $working_day_delay = 0;
        if (!$card) {
            return redirect(route('global.c_store'));
        }
        foreach ($card as $key => $value) {

            if ($value['provinces']) {
                $allowed_provinces = array_intersect($allowed_provinces, explode(',', $value['provinces']));
            };

            if ($value['cities']) {
                $allowed_cities = array_intersect($allowed_cities, explode(',', $value['cities']));
            };

            if ($value['delay_type'] == 'actual_day' and $actual_day_delay < $value['delay']) {
                $actual_day_delay = $value['delay'];
            } elseif ($value['delay_type'] == 'working_day' and $working_day_delay < $value['delay']) {
                $working_day_delay = $value['delay'];
            }
        }


        $working_day_delay = $this->working_day_delay($working_day_delay);
        $delay = max($working_day_delay, $actual_day_delay);
        $firstDate = time() + ($delay * 86400);
        if (!in_array($request['cities'], $allowed_cities) and !in_array($request['province'], $allowed_provinces)) {
            return back_error($request, ['محل مراسم' => 'متاسفانه امکان ارسال سفارش به شهر انتخاب شده وجود ندارد، برای اطلاعات بیشتر تماس بگیرید.']);
        }

        //compare date
        $meetingDate = shamsi_to_miladi($request['meeting_date']);
        if ($meetingDate < date("Y-m-d 00:00:00", $firstDate)) {
            return back_error($request, ['زمان مراسم' => 'امکان ثبت درخواست در این تاریخ وجود ندارد']);
        };

        $cs_order = [];
        session()->put('cs_order', $cs_order);
        $cs_order = [
            "user_id" => Auth::id(),
            "province" => $request['province'],
            "cities" => $request['cities'],
            "receiver" => $request['receiver'],
            "zip_code" => $request['zip_code'],
            "phone" => $request['phone'],
            "mobile" => $request['mobile'],
            "description" => $request['description'],
            "condolences_to" => $request['condolences_to'],
            "from_as" => $request['from_as'],
            "late_name" => $request['late_name'],
            "date" => $meetingDate,
            "time" => $request['meeting_time'],
            "meeting_address" => $request['meeting_address'],
            "lat" => $request['lat'],
            "lon" => $request['lon'],
        ];
        session()->put('cs_order', $cs_order);
        return redirect(route('global.c_store_card_completion_order_confirm_show'));
    }

    public function c_store_card_completion_order_confirm_show(Request $request)
    {
        $card = session()->get('c_store_cart');
        $gateways = gateway::with('bank')->where('online', 1)->get();

        if (!$card) {
            return redirect(route('global.c_store'));
        }
        $order = session()->get('cs_order');
        if (empty($order)) {
            return redirect('global.c_store_checkout');
        }
        return view('global.c_store.order_summary', compact('card', 'order', 'gateways'));
    }

    public function c_store_card_completion_order_confirm_process(Request $request)
    {
        $this->validate($request,
            [
                'gateway_id' => 'required',
            ]
        );

        $card = session()->get('c_store_cart');
        if (!$card) {
            return redirect(route('global.c_store'));
        }
        $order = session()->get('cs_order');
        if (empty($order)) {
            return redirect('global.c_store_checkout');
        }
        $user = Auth::user();
        $phone = session()->get('c_store_phone');
        if (!$user and $phone) {
            $user = User::where('phone', $phone)->first();
            if (!$user) {
                return redirect(route('global.c_store_card_completion_phone'));
            }
        } elseif (!$user and !$phone) {
            return redirect(route('global.c_store_card_completion_phone'));
        }

        $total_amount = 0;
        foreach ($card as $key => $value) {
            $total_amount += $value['price'] * $value['quantity'];
        }

        $cs_order = new c_store_order();
        $cs_order->user_id = $user['id'];
        $cs_order->province = $order['province'];
        $cs_order->cities = $order['cities'];
        $cs_order->receiver = $order['receiver'];
        $cs_order->zip_code = $order['zip_code'];
        $cs_order->phone = $order['phone'];
        $cs_order->mobile = $order['mobile'];
        $cs_order->description = $order['description'];
        $cs_order->condolences_to = $order['condolences_to'];
        $cs_order->from_as = $order['from_as'];
        $cs_order->late_name = $order['late_name'];
        $cs_order->date = $order['date'];
        $cs_order->time = $order['time'];
        $cs_order->meeting_address = $order['meeting_address'];
        $cs_order->lat = $order['lat'];
        $cs_order->lon = $order['lon'];
//        $cs_order->amount = $total_amount;
        $cs_order->amount = 10000;
        $cs_order->save();

        foreach ($card as $key => $value) {
            $cs_order_item = new c_store_order_item();
            $cs_order_item->CSO_id = $cs_order->id;
            $cs_order_item->CSP_id = $key;
            $cs_order_item->name = $value['name'];
            $cs_order_item->quantity = $value['quantity'];
            $cs_order_item->price = $value['price'];
            $cs_order_item->slug = $value['slug'];
            $cs_order_item->image = $value['image'];
            $cs_order_item->save();
        }

        return $this->payment('c_store', $cs_order['id'], $request);
    }
}
