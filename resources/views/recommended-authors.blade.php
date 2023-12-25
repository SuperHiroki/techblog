@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>おすすめ著者</h1>
        <div class="authors-list">
            @foreach ($authors as $author)
                <div class="author">
                    <h2>{{ $author->name }}</h2>
                    <p><a href="{{ $author->link }}" target="_blank">プロフィールを見る</a></p>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .authors-list .author {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
@endpush
