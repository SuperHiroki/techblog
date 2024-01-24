{{-- authors/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <div style="margin-top: 120px;">
        <a class="m-4" href="{{ route('authors.create') }}">Add a New Author</a>
    </div>
    <h1 class="mx-4 mt-4 mb-2">Authors List</h1>
    @foreach ($authors as $author)
        <div class="author card shadow m-4 p-4">
            <h2>{{ $author->name }}</h2>
            <p>
                <strong>Description:</strong> 
                <p>{{ $author->description }}</p>
            </p>
            <p>
                <strong>Link:</strong> 
                <a href="{{ $author->link }}" target="_blank">{{ $author->link }}</a>
            </p>
            <p>
                <strong>Link共通部分:</strong> 
                <a href="{{ $author->link_common }}" target="_blank">{{ $author->link_common }}</a>
            </p>
            @if ($author->rss_link)
                <p>
                    <strong>RSS Link:</strong> 
                    <a href="{{ $author->rss_link }}" target="_blank">{{ $author->rss_link }}</a>
                </p>
            @endif
            <hr>
            <p>フォロワー数: {{ $author->followers_count ?? 0 }}</p>
            <p>記事数: {{ $author->articles_count ?? 0 }}</p>
            @if ($author->latestArticle)
                <p>Latest Article: {{ $author->latestArticle->title }}</p>
            @endif
            <hr>
            @if ($author->thumbnail_url)
                <img src="{{ $author->thumbnail_url }}" alt="No Image" style="width: 100px; height: auto;">
                <p>
                    <strong>Thumbnail uRL:</strong> 
                    <a href="{{ $author->thumbnail_url }}" target="_blank">{{ $author->thumbnail_url }}</a>
                </p>
            @endif
            @if ($author->favicon_url)
                <img src="{{ $author->favicon_url }}" alt="No Image" style="width: 50px; height: auto;">
                <p>
                    <strong>Favicon URL:</strong> 
                    <a href="{{ $author->favicon_url }}" target="_blank">{{ $author->favicon_url }}</a>
                </p>
            @endif
            <div class="m-4 d-flex align-items-center justify-content-center">
                <div class="mx-2">
                    <form action="{{ route('authors.destroy', $author) }}" method="POST" onsubmit="return confirmDelete()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete Author</button>
                    </form>
                </div>
                <div class="mx-2">
                    <form action="{{ route('authors.update', $author) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-primary">Update Author</button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

<script>
function confirmDelete() {
    return confirm('Are you sure you want to delete this author?');
}
</script>
