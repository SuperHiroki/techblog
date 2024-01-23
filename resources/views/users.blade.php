@extends('layouts.app')

@section('content')

<div id="users-container">
    @foreach ($users as $user)
        <div class="card shadow m-2 p-2">
            <div class="m-1">
                <div class="d-flex align-items-center justify-content-left">
                    <a href="{{ route('my-page.profile', $user->id) }}" class="custom-user-link rounded p-1 d-flex align-items-center justify-content-left">
                        @if($user->icon_image)
                            <img src="{{ asset('storage/' . $user->icon_image) }}" alt="No Image" style="max-width: 30px; max-height: 30px; border-radius: 50%; margin-right: 5px;">
                        @else
                            <img src="{{ asset('images/default-icons/avatar.png') }}" alt="No Image" style="max-width: 30px; max-height: 30px; border-radius: 50%; margin-right: 5px;">
                        @endif
                        <div class="small">&#64;{{ $user->name }}</div>
                    </a>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-left">
                @if($user->profile->bio)
                    <div>{{ Str::limit($user->profile->bio, 100) }}</div>
                @endif
            </div>
        </div>
    @endforeach
</div>

<!----------------------------------------------------------------------------------------------------------------------------->
<!--ページネーション-->
@include('js.common-pagination-js')
<script>
//ページが読み込まれたときに発火する
document.addEventListener('DOMContentLoaded', function () {
    pagination({{ $users->lastPage() }}, "users-container");
});
</script>

<!----------------------------------------------------------------------------------------------------------------------------->

@endsection