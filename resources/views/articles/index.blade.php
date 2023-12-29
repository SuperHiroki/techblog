{{-- articles/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <a class="m-4" href="{{ route('articles.create') }}">Add a New Article</a>
    <h1 class="mx-4 mt-4 mb-2">Articles List</h1>
    <div class="row">
        @foreach ($articles as $article)
            <div class="col-md-12">
                <div class="card shadow p-4 m-4">
                    <div class="card-body">
                        <h5 class="card-title">{{ $article->title }}</h5>
                        <p class="card-text">{{ $article->description }}</p>
                        <p class="card-text">著者: {{ $article->author->name ?? 'Unknown' }}</p>
                        <p  class="card-text">Link: <a href="{{ $article->link }}">{{ $article->link }}</a></P>
                        <hr>
                        <div class="d-flex gap-4">
                            <span>Likes: {{ $article->likeUsers->count() }}</span>
                            <span>Bookmarks: {{ $article->bookmarkUsers->count() }}</span>
                            <span>Archives: {{ $article->archiveUsers->count() }}</span>
                        </div>
                        <hr>
                        <img src="{{ $article->favicon_url }}" alt="Favicon" style="width: 50px; height: auto;">
                        <p>Favicon URL: <a href="{{ $article->favicon_url }}">{{ $article->favicon_url }}</a></p>
                        <img src="{{ $article->thumbnail_url }}" alt="Thumbnail" style="width: 50px; height: auto;">
                        <p>Thumbnail URL: <a href="{{ $article->thumbnail_url }}">{{ $article->thumbnail_url }}</a></p>
                    </div>
                    <div class="m-2">
                        <form action="{{ route('articles.destroy', $article) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete Article</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection