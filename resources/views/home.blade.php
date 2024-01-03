@extends('layouts.app')

@section('content')


<div class="mb-4 d-flex align-items-center justify-content-center">
    <p class="lead mb-0">このサイトはテックブログを管理できるサイトです。</p>
</div>

<div class="card mb-4">
    <div class="card-header d-flex align-items-center justify-content-center">
        <h4 class="mb-0">記事にいいね（ブックマーク、アーカイブ）をつける</h4>
    </div>
    <div class="card-body d-flex align-items-center justify-content-center">
        <p class="mb-0">
            拡張機能によって、その記事を読んでいる時に、いいね（ブックマーク、アーカイブ）をつけることができます。
            ただし、いいね（ブックマーク、アーカイブ）をつけることができる記事は、著者がこのサイトに登録されている場合のみです。
        </p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header d-flex align-items-center justify-content-center">
        <h4 class="mb-0">著者を申請する</h4>
    </div>
    <div class="card-body d-flex align-items-center justify-content-center">
        <p class="mb-0">
            サイトに登録されていない著者の記事にいいね（ブックマーク、アーカイブ）をつけたい場合は、コメントから著者を申請してください。
            記事にいいね（ブックマーク、アーカイブ）をつけるには、そのドメインが、サイトに登録されている著者のドメインと一致する必要があります。
        </p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header d-flex align-items-center justify-content-center">
        <h4 class="mb-0">日常的に勉強する</h4>
    </div>
    <div class="card-body d-flex align-items-center justify-content-center">
        <p class="mb-0">
            自身のマイページの新着記事のページに、フォロー中の著者の新着記事が掲載されるので、それを読むことで常に最新情報にキャッチアップできます。
        </p>
    </div>
</div>

@endsection
