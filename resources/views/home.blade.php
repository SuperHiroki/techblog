@extends('layouts.app')

@section('content')


<div class="mb-4 d-flex align-items-center justify-content-center">
    <h2 class="mb-0"><strong>Super Tech-Blog Management</strong></h2>
</div>

<div class="card mb-4 shadow">
    <div class="card-header d-flex align-items-center justify-content-center">
        <h3 class="mb-0"><strong>当アプリの用途</strong></h3>
    </div>
    <div class="card-body d-flex align-items-center justify-content-center">
        <p class="mb-0">
            当ウェブアプリと下記のChrome拡張機能を併用することで、簡単にテックブログを管理することができます。
        </p>
    </div>
</div>

<div class="card mb-4 shadow">
    <div class="card-header d-flex align-items-center justify-content-center" style="font-weight: bold;">
        <h3 class="mb-0"><strong>Chrome拡張機能の使い方</strong></h3>
    </div>
    <div class="card-body d-flex align-items-center justify-content-center">
        <div>
            <p class="mb-0">
                Chrome拡張機能によって、その記事を読んでいる時にその場で、いいね（ブックマーク、アーカイブ）をつけることができます。
            </p>
            <div class="my-4">
                <h4><strong>Chrome拡張機能をダウンロード</strong></h4>
                <p class="mb-0">
                    クロムストアへの公開は現在申請中なので、下記のレポジトリをダウンロードして手動で導入してください。
                </p>
                <a href='https://github.com/SuperHiroki/chrome-extensions-for-techblog' target='_blank' class="mb-0" style="color: blue; text-decoration: underline;">
                    https://github.com/SuperHiroki/chrome-extensions-for-techblog
                </a>
            </div>
            <div class="my-4">
                <h4><strong>Chrome拡張機能の使い方</strong></h4>
                <p class="mb-1">
                     本機能を起動するためのショートカットは、<strong>Ctrl+Shift+X</strong> です。<br>
                     下記にデモ動画があります。
                </p>
                <div class="card mb-2" style="max-width: 620px;">
                    <div class="card-body text-center">
                        <img src="images/home/extensionThumbnail.png" alt="Chrome拡張機能のサムネイル" class="card-img-top" style="width: 40%; height: auto;">
                    </div>
                    <div class="card-footer text-center">
                        <a href='https://www.youtube.com/watch?v=aX_NfZoXdXE' class="btn btn-primary" target='_blank'>
                            動画を見る
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="card mb-4 shadow">
    <div class="card-header d-flex align-items-center justify-content-center">
        <h3 class="mb-0"><strong>日常的に勉強する</strong></h3>
    </div>
    <div class="card-body d-flex align-items-center justify-content-center">
        <div>
            <p class="mb-0">
                自身のマイページの新着記事のページに、フォロー中の著者の新着記事が掲載されるので、それを読むことで常に最新情報をキャッチアップできます。
            </p>
        </div>
    </div>
</div>

<div class="card mb-4 shadow">
    <div class="card-header d-flex align-items-center justify-content-center">
        <h3 class="mb-0"><strong>著者を申請する</strong></h3>
    </div>
    <div class="card-body d-flex align-items-center justify-content-center">
        <div>
            <p class="mb-0">
                サイトに登録されていない著者の記事にいいね（ブックマーク、アーカイブ）をつけることはできません。
                記事にいいね（ブックマーク、アーカイブ）をつけるには、コメントのページで著者を申請して、登録されるのを待ってください。
            </p>
        </div>
    </div>
</div>

@endsection

