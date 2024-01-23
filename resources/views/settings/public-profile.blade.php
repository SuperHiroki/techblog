{{-- resources/views/settings/public-profile.blade.php --}}
@extends('layouts.app')

@section('page-specific-header')
    @include('partials.sub_header')
@endsection

@section('content')
<div class="mb-5">
    <form method="POST" action="{{ route('settings.public-profile.update', $user->id) }}">
        @csrf
        @method('PATCH')

        <!-- 名前 -->
        <div class="mb-3">
            <label for="name" class="form-label">名前</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $profile->name ?? '' }}" style="border-width: 6px;">
        </div>

        <!-- 公開メール -->
        <div class="mb-3">
            <label for="publicEmail" class="form-label">公開メール</label>
            <input type="email" class="form-control" id="publicEmail" name="public_email" value="{{ $profile->public_email ?? '' }}" style="border-width: 6px;">
        </div>

        <!-- GitHub -->
        <div class="mb-3">
            <label for="github" class="form-label">GitHub</label>
            <input type="text" class="form-control" id="github" name="github" value="{{ $profile->github ?? '' }}" style="border-width: 6px;">
        </div>

        <!-- ウェブサイト -->
        <div class="mb-3">
            <label for="website" class="form-label">ウェブサイト</label>
            <input type="text" class="form-control" id="website" name="website" value="{{ $profile->website ?? '' }}" style="border-width: 6px;">
        </div>

        <!-- 組織 -->
        <div class="mb-3">
            <label for="organization" class="form-label">組織</label>
            <input type="text" class="form-control" id="organization" name="organization" value="{{ $profile->organization ?? '' }}" style="border-width: 6px;">
        </div>

        <!-- 場所 -->
        <div class="mb-3">
            <label for="location" class="form-label">場所</label>
            <input type="text" class="form-control" id="location" name="location" value="{{ $profile->location ?? '' }}" style="border-width: 6px;">
        </div>

        <!-- 自己紹介 -->
        <div class="mb-3">
            <label for="bio" class="form-label">自己紹介</label>
            <textarea class="form-control" id="bio" name="bio" rows="5" style="border-width: 6px;">{{ $profile->bio ?? '' }}</textarea>
        </div>

        <!-- SNSリンク（sns1 から sns6） -->
        @for ($i = 1; $i <= 6; $i++)
            <div class="mb-3">
                <label for="sns{{ $i }}" class="form-label">SNS {{ $i }}</label>
                <input type="text" class="form-control" id="sns{{ $i }}" name="sns{{ $i }}" value="{{ $profile->{'sns'.$i} ?? '' }}" style="border-width: 6px;">
            </div>
        @endfor

        <button type="submit" class="btn btn-primary">更新</button>
    </form>
</div>
@endsection
