<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use WebDevEtc\BlogEtc\Models\BlogEtcPost;

class api extends Controller
{
    //

    public function main_page()
    {
        $posts = get_posts(6,['last_post'])->map(function ($post){
            return [
                'id'=>$post->id,
                'image'=> 'ashrafolanbia.ir/public/images/blog_images/'.$post->image_medium,
                'title'=> $post->title,
            ];
        });

        $payment_titles = [
            [
                'id'=>'1',
                'title'=>'کمک ماهانه',
                'img'=>'img.jpg',
                'link'=>'/sadf/asdf/2'
            ]
        ];
        $notification = [
            'text'=>'این یک اطلاعیه است',
            'href'=>'link'
        ];

        return response()->json(['posts'=>$posts,'payments'=>$payment_titles,'notification'=>$notification]);
    }

    public function more_posts()
    {
        $posts = get_posts(null,[],['last_post'],10);
        return response()->json(['data'=>$posts]);
    }

    public function show_post()
    {
        $posts = get_posts(null,[],['last_post'],10);
        return response()->json(['data'=>$posts]);
    }

    public function comment(Request $request)
    {
        $posts = get_posts(null,[],['last_post'],10);
        return response()->json(['data'=>$posts]);
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
