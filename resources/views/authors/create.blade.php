{{-- authors/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="m-4">
    <a class="m-2" href="{{ route('authors.index') }}">Authors List</a>
    <h1 class="m-2">Add a New Author</h1>
    <div class="m-2">
        <form action="{{ route('authors.store') }}" method="POST">
            @csrf
            <label for="link">Link:</label>
            <input type="url" name="link" id="link" class="form-control border border-secondary" required>

            <label for="link_common">Link共通部分:</label>
            <input type="url" name="link_common" id="link_common" class="form-control border border-secondary" required>
            
            <button class="btn btn-primary my-2" type="submit">Add Author</button>
        </form>
    </div>
</div>
@endsection
