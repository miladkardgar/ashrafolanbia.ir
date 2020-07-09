<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $posts = get_posts(null,['last_post'],[],6)->map(function ($post){
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

//    public function more_posts()
//    {
//        $posts = get_posts(null,[],['last_post'],10);
//        return response()->json(['data'=>$posts]);
//    }

    public function show_post($slug)
    {
        $blog_post = BlogEtcPost::where("slug", $slug)
            ->first();
        if (!$blog_post){
            return response()->json(['result'=>'fail','message'=>'post not found']);
        }

        return response()->json(['result'=>'success','message'=>'','post'=>$blog_post,'comment'=> $blog_post->comments()
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

    public function payment()
    {
        $posts = get_posts(null,[],['last_post'],10);
        return response()->json(['data'=>$posts]);
    }

    public function transaction(Request $request)
    {
        $posts = get_posts(null,[],['last_post'],10);
        return response()->json(['data'=>$posts]);
    }

    public function callback()
    {
        $posts = get_posts(null,[],['last_post'],10);
        return response()->json(['data'=>$posts]);
    }

    public function show_from()
    {
        $posts = get_posts(null,[],['last_post'],10);
        return response()->json(['data'=>$posts]);
    }

    public function set_from(Request $request)
    {
        $posts = get_posts(null,[],['last_post'],10);
        return response()->json(['data'=>$posts]);
    }

    public function login(Request $request)
    {
        $posts = get_posts(null,[],['last_post'],10);
        return response()->json(['data'=>$posts]);
    }

    public function profile()
    {
        $posts = get_posts(null,[],['last_post'],10);
        return response()->json(['data'=>$posts]);
    }

    public function set_periodic(Request $request)
    {
        $posts = get_posts(null,[],['last_post'],10);
        return response()->json(['data'=>$posts]);
    }

    public function payment_history()
    {
        $posts = get_posts(null,[],['last_post'],10);
        return response()->json(['data'=>$posts]);
    }

}
