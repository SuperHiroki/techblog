@foreach ($replies as $reply)
    @include('comments.reply_template', ['reply' => $reply])
@endforeach