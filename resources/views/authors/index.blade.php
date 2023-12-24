{{-- authors/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <a href="{{ route('authors.create') }}">Add a New Author</a>
    <h1>Authors List</h1>
    <ul>
        @foreach ($authors as $author)
            <li>
                {{ $author->name }}
                <form action="{{ route('authors.destroy', $author) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection
