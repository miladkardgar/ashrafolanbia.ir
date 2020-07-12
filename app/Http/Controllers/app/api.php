<?php

namespace App\Http\Controllers\app;

use App\charity_period;
use App\charity_periods_transaction;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use WebDevEtc\BlogEtc\Captcha\CaptchaAbstract;
use WebDevEtc\BlogEtc\Events\CommentAdded;
use WebDevEtc\BlogEtc\Models\BlogEtcComment;
use WebDevEtc\BlogEtc\Models\BlogEtcPost;
use WebDevEtc\BlogEtc\Requests\AddNewCommentRequest;

class api extends Controller
{
    //

    public function main_page()
    {
        $posts = get_posts(null,['last_post'],[],15)->map(function ($post){
            return [
                'id'=>$post->id,
                'slug'=>$post->slug,
                'image'=> 'ashrafolanbia.ir/public/images/blog_images/'.$post->image_medium,
                'title'=> $post->title,
            ];
        });

        $payment_titles = [
            [
                'id'=>'1',
                'title'=>'کمک ماهانه',
                'img'=>'https://ashrafolanbia.ir/public/images/12144781741590231634.jpg',
                'link'=>'/sadf/asdf/2'
            ]
        ];
        $notification = [
            'text'=>'این یک اطلاعیه است',
            'href'=>'link'
        ];


        return response()->json(['posts'=>$posts,'payments'=>$payment_titles,'notification'=>$notification]);
    }

    public function show_post($slug)
    {
        $blog_post = BlogEtcPost::where("slug", $slug)
            ->first();
        if (!$blog_post){
            return response()->json(['result'=>'fail','message'=>'post not found']);
        }
        $base_url = URL::asset('public/images/'.config('blogetc.blog_upload_dir'))."/";
        return response()->json(['result'=>'success','message'=>'','base_url'=>$base_url,'post'=>$blog_post,'comment'=> $blog_post->comments()
            ->with("user")
            ->get()]);
    }

    public function addNewComment(Request $request, $blog_post_slug)
    {
        if (!isset($request['comment'])){
            return response()->json(['result'=>'fail','message'=>"نظری ثبت نشد"]);
        }

        if (config("blogetc.comments.type_of_comments_to_show", "built_in") !== 'built_in') {
            $message = "Built in comments are disabled";
            return response()->json(['result'=>'fail','message'=>$message]);
        }

        $blog_post = BlogEtcPost::where("slug", $blog_post_slug)
            ->first();
        if (!$blog_post){
            return response()->json(['result'=>'fail','message'=>'post not found']);
        }

        $new_comment = $this->createNewComment($request, $blog_post);

        if(config('blogetc.comments.auto_approve_comments')) {
            $message = __('messages.your_comment_send_successfully');
        }else{
            $message = __('messages.your_comment_send_successfully_and_show_after_approve');
        }
        return response()->json(['result'=>'success','message'=>$message]);

    }

    /**
     * @param AddNewCommentRequest $request
     * @param $blog_post
     * @return BlogEtcComment
     */
    protected function createNewComment(Request $request, $blog_post)
    {
        $new_comment = new BlogEtcComment($request->all());

        if (config("blogetc.comments.save_ip_address")) {
            $new_comment->ip = $request->ip();
        }
        if (config("blogetc.comments.ask_for_author_website")) {
            $new_comment->author_website = $request->get('author_website');
        }
        if (config("blogetc.comments.ask_for_author_website")) {
            $new_comment->author_email = $request->get('author_email');
        }
        if (config("blogetc.comments.save_user_id_if_logged_in", true) && Auth::check()) {
            $new_comment->user_id = Auth::user()->id;
        }

        $new_comment->approved = config("blogetc.comments.auto_approve_comments", true) ? true : false;

        $blog_post->comments()->save($new_comment);

        event(new CommentAdded($blog_post, $new_comment));

        return $new_comment;
    }

    public function login(Request $request)
    {
        if (!$request['username'] or !$request['password']){
            return response()->json(['result'=>'fail','message'=>"نام کاربری یا رمز عبور اشتباه است"]);
        }
        else{
            $user_by_name = User::where('name',$request['username'])->first();
            $user_by_phone = User::where('phone',$request['username'])->first();
            $user_by_email = User::where('email',$request['username'])->first();
            $user = $user_by_name ? $user_by_name : $user_by_phone;
            $user = $user ? $user : $user_by_email;
        }
        $password_is_match = !$user ? false : Hash::check($request['password'], $user->password);

        if ($password_is_match){
            if (!$user['api_token']){
                $user['api_token'] = Str::random(60);
                $user->save();
            }
            return response()->json(['result'=>'fail','message'=>"ورود با موفقیت انجام شد",'api_token'=>$user['api_token']]);
        }
        else{
            return response()->json(['result'=>'fail','message'=>"نام کاربری یا رمز عبور اشتباه است"]);
        }

    }

    public function user_data()
    {
        $user = Auth::guard('api')->user();

        $active_periods = charity_period::where('user_id', Auth::id())->get();
        $unpaid = charity_periods_transaction::where(
            [
                ['status', '=', 'unpaid'],
                ['user_id', '=', Auth::id()],
            ])->get();
        $paid_history = charity_periods_transaction::where(
            [
                ['status', '=', 'paid'],
                ['user_id', '=', Auth::id()],
            ])->get();
        $userInfo = User::with('people')->find($user['id']);
        $userInfo =[
            'name' =>$userInfo['people']['name']." ".$userInfo['people']['family'],
            'phone' =>$userInfo['phone'],
            'email' =>$userInfo['email'],
        ];

        return response()->json(['userInfo'=>$userInfo,'paid_history'=>$paid_history,'unpaid'=>$unpaid,'active_periods'=>$active_periods] );
    }

    public function login_link()
    {
        $user = Auth::guard('api')->user();
        $user->login_token = Str::random(60);
        $user->Save();

        return response()->json(['link'=>route('app_profile')."?lt=".$user->login_token] );
    }


}
