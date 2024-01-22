@foreach ($replies as $reply)
    @include('comments.reply_template', ['reply' => $reply])
@endforeach
<div style="display:none" id="keep-replies-lastPage-to-comment-{{ $reply->parent_id }}">{{ $replies->lastPage() }}</div>