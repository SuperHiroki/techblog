{{-- resources/views/settings/account.blade.php --}}
@extends('layouts.app')

@section('page-specific-header')
    @include('partials.sub_header')
@endsection

@section('content')
<div class="mb-5">
    <!-- enctype属性を追加してファイルアップロードを可能にする -->
    <form method="POST" action="{{ route('settings.account', $user->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <!-- 名前 -->
        <div class="mb-3">
            <label for="name" class="form-label">名前</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" style="border-width: 6px;">
        </div>

        <!-- メール -->
        <div class="mb-3">
            <label for="email" class="form-label">メール</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" style="border-width: 6px;">
        </div>

        <!-- パスワード -->
        <div class="mb-3">
            <label for="password" class="form-label">パスワード</label>
            <input type="password" class="form-control" id="password" name="password" value="" style="border-width: 6px;">
        </div>

        <!-- パスワード確認 -->
        <div class="mb-3">
            <label for="passwordConfirmation" class="form-label">パスワード確認</label>
            <input type="password" class="form-control" id="passwordConfirmation" name="password_confirmation" value="" style="border-width: 6px;">
        </div>

        <!-- 現在のアイコン画像 -->
        @if ($user->icon_image)
            <div class="mb-3">
                <label for="currentIconImage" class="form-label">現在のアイコン</label>
                <div>
                    <img src="{{ asset('storage/' . $user->icon_image) }}" alt="No Image" style="max-width: 150px; max-height: 150px;">
                </div>
            </div>
        @endif

        <!-- アイコン画像 -->
        <div class="mb-3">
            <label for="iconImage" class="form-label">アイコン画像</label>
            <input type="file" class="form-control" id="iconImage" name="icon_image">
        </div>

        <button type="submit" class="btn btn-primary">更新</button>
    </form>
</div>
@endsection
