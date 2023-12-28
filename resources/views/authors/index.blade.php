{{-- authors/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <a href="{{ route('authors.create') }}">Add a New Author</a>
    <h1>Authors List</h1>
    @foreach ($authors as $author)
        <div class="author card shadow m-4 p-4">
            <h2>{{ $author->name }}</h2>
            <p>
                <strong>Link:</strong> 
                <a href="{{ $author->link }}">{{ $author->link }}</a>
            </p>
            @if ($author->rss_link)
                <p>
                    <strong>RSS Link:</strong> 
                    <a href="{{ $author->rss_link }}">{{ $author->rss_link }}</a>
                </p>
            @endif
            <hr>
            <p>Articles: {{ $author->articles_count }}</p>
            <p>Followers: {{ $author->followers }}</p>
            @if ($author->latestArticle)
                <p>Latest Article: {{ $author->latestArticle->title }}</p>
            @endif
            <hr>
            @if ($author->thumbnail_url)
                <img src="{{ $author->thumbnail_url }}" alt="Thumbnail for {{ $author->name }}" style="width: 100px; height: auto;">
                <p>
                    <strong>Thumbnail uRL:</strong> 
                    <a href="{{ $author->thumbnail_url }}">{{ $author->thumbnail_url }}</a>
                </p>
            @endif
            @if ($author->favicon_url)
                <img src="{{ $author->favicon_url }}" alt="Favicon for {{ $author->name }}" style="width: 50px; height: auto;">
                <p>
                    <strong>Favicon URL:</strong> 
                    <a href="{{ $author->favicon_url }}">{{ $author->favicon_url }}</a>
                </p>
            @endif
        </div>
        <div class="m-4">
            <form action="{{ route('authors.destroy', $author) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete Author</button>
            </form>
        </div>
    @endforeach
@endsection
