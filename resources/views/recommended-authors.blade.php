@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">おすすめ著者</h1>
        <div class="row">
            @foreach ($authors as $author)
                <div class="col-md-12 mb-3">
                    <div class="card shadow">
                        <div class="row g-0">
                            <div class="col-md-2 d-flex align-items-center justify-content-center">
                                @if($author->thumbnail_url)
                                    <img src="{{ $author->thumbnail_url }}" class="img-fluid" alt="No Image">
                                @else
                                    <span class="text-center">No Image!</span>
                                @endif
                            </div>
                            <div class="col-md-8 d-flex align-items-center">
                                <div class="card-body align-center">
                                    <h4 class="card-title">{{ $author->name }}</h4>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $author->favicon_url ?: asset('images/default-favicon.png') }}" style="width: 20px; height: auto; margin-right: 5px;">
                                        <a href="{{ $author->link }}" target="_blank">{{ $author->link }}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end justify-content-end p-3">
                                @if($followedAuthors->contains('id', $author->id))
                                    <form action="{{ route('unfollow-author', $author->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">登録解除</button>
                                    </form>
                                @else
                                    <form action="{{ route('follow-author', $author->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success">登録</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
