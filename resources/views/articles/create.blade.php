{{-- articles/create.blade.php --}}
@extends('layouts.app')

@section('content')
    <a href="{{ route('articles.index') }}">Articles List</a>
    <h1>Add a New Article</h1>
    <form action="{{ route('articles.store') }}" method="POST">
        @csrf
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required></textarea>

        <label for="link">Link:</label>
        <input type="url" name="link" id="link" required>

        <label for="good">Good:</label>
        <input type="checkbox" name="good" id="good" value="1">

        <label for="bookmark">Bookmark:</label>
        <input type="checkbox" name="bookmark" id="bookmark" value="1">

        <label for="archive">Archive:</label>
        <input type="checkbox" name="archive" id="archive" value="1">

        <button type="submit">Add Article</button>
    </form>
@endsection
