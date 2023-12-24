{{-- articles/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <a href="{{ route('articles.create') }}">Add a New Article</a>
    <h1>Articles List</h1>
    <ul>
        @foreach ($articles as $article)
            <li>
                {{ $article->title }} - {{ $article->description }}
                <form action="{{ route('articles.destroy', $article) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection
