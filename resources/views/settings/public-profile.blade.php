{{-- resources/views/settings/public-profile.blade.php --}}
@extends('layouts.app')

@section('page-specific-header')
    @include('partials.sub_header')
@endsection

@section('content')
<div class="mb-5">
    <form method="POST" action="{{ route('settings.public-profile', $user->id) }}">
        @csrf
        @method('PATCH')

        <!-- 公開メール -->
        <div class="mb-3">
            <label for="publicEmail" class="form-label">公開メール</label>
            <input type="email" class="form-control" id="publicEmail" name="public_email" value="{{ $profile->public_email ?? '' }}">
        </div>

        <!-- GitHub -->
        <div class="mb-3">
            <label for="github" class="form-label">GitHub</label>
            <input type="text" class="form-control" id="github" name="github" value="{{ $profile->github ?? '' }}">
        </div>

        <!-- ウェブサイト -->
        <div class="mb-3">
            <label for="website" class="form-label">ウェブサイト</label>
            <input type="text" class="form-control" id="website" name="website" value="{{ $profile->website ?? '' }}">
        </div>

        <!-- 組織 -->
        <div class="mb-3">
            <label for="organization" class="form-label">組織</label>
            <input type="text" class="form-control" id="organization" name="organization" value="{{ $profile->organization ?? '' }}">
        </div>

        <!-- 場所 -->
        <div class="mb-3">
            <label for="location" class="form-label">場所</label>
            <input type="text" class="form-control" id="location" name="location" value="{{ $profile->location ?? '' }}">
        </div>

        <!-- 自己紹介 -->
        <div class="mb-3">
            <label for="bio" class="form-label">自己紹介</label>
            <textarea class="form-control" id="bio" name="bio">{{ $profile->bio ?? '' }}</textarea>
        </div>

        <!-- SNSリンク（sns1 から sns6） -->
        @for ($i = 1; $i <= 6; $i++)
            <div class="mb-3">
                <label for="sns{{ $i }}" class="form-label">SNS {{ $i }}</label>
                <input type="text" class="form-control" id="sns{{ $i }}" name="sns{{ $i }}" value="{{ $profile->{'sns'.$i} ?? '' }}">
            </div>
        @endfor

        <button type="submit" class="btn btn-primary">更新</button>
    </form>
</div>
@endsection
