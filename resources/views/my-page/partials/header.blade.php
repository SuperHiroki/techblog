<!-- resources/views/my-page/partials/header.blade.php -->
<div class="my-page-header">
    <a href="{{ route('my-page.followed-authors') }}">フォローした著者</a>
    <a href="{{ route('my-page.recent-articles') }}">最近の記事</a>
    <a href="{{ route('my-page.likes') }}">いいね</a>
    <a href="{{ route('my-page.bookmarks') }}">ブックマーク</a>
    <a href="{{ route('my-page.archive') }}">アーカイブ</a>
</div>
