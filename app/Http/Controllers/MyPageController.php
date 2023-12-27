<?php

namespace App\Http\Controllers;

class MyPageController extends Controller
{
    public function publicProfile()
    {
        // フォローした著者に関する処理
        return view('my-page.public-profile');
    }

    public function followedAuthors()
    {
        // フォローした著者に関する処理
        return view('my-page.followed-authors');
    }

    public function recentArticles()
    {
        // 最近の記事に関する処理
        return view('my-page.recent-articles');
    }

    public function likes()
    {
        // いいねに関する処理
        return view('my-page.likes');
    }

    public function bookmarks()
    {
        // ブックマークに関する処理
        return view('my-page.bookmarks');
    }

    public function archive()
    {
        // アーカイブに関する処理
        return view('my-page.archive');
    }
}
