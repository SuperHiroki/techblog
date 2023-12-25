@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">おすすめ著者</h1>
        <div class="row">
            @foreach ($authors as $author)
                <div class="col-md-12 mb-3">
                    <div class="card shadow d-flex flex-row align-items-center">
                        <img src="{{ $author->thumbnail_url ?: asset('images/author-icons/default-author-icon.jpg') }}" class="img-fluid mx-2" style="width: 60px; height: auto;" alt="No Image">
                        <div class="card-body">
                            <h4 class="card-title">{{ $author->name }}</h4>
                            <div class="d-flex align-items-center">
                                <img src="{{ $author->favicon_url ?: asset('images/default-favicon.png') }}" style="width: 20px; height: auto; margin-right: 5px;">
                                <a href="{{ $author->link }}" target="_blank">{{ $author->link }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
