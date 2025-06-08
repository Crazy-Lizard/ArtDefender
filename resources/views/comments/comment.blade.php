<div class="comment card mb-3" data-id="{{ $comment->id }}">
    <div class="card-body">
        <div class="d-flex justify-content-between" style="display: flex; justify-content: flex-start; align-items:baseline; gap: 10px; height:30px">
            <h3 class="card-title"><a href="/profile/{{ $comment->user_id }}" style="color: whitesmoke">{{ $comment->user->name }}</a></h3>
            <small class="text-muted" style="font-weight: normal; font-size: 12px; opacity: 50%">{{ $comment->created_at->diffForHumans() }}</small>
        </div>
        
        <p class="card-text" style="font-weight: normal; font-size: 14px;">{{ $comment->body }}</p>
        
        <!-- Кнопка ответа -->
        <div class="comment-actions mt-2">
                @auth
                    <button class="btn btn-sm btn-outline-secondary reply-btn" data-comment-id="{{ $comment->id }}" style="cursor:pointer; border: none; background: none; color:#43E6B1">
                        ответить
                    </button>
                    @if($comment->user_id == auth()->id() || auth()->user()->isModerator())
                        <button class="delete-comment text-red-500 text-sm" data-id="{{ $comment->id }}" style="cursor:pointer; border: none; background: none; color:#F2603E">
                            удалить
                        </button>
                    @else
                        {{-- <button class="report-comment text-red-500 text-sm" data-id="{{ $comment->id }}" style="cursor:pointer; border: none; background: none; color:#F2603E">
                            жалоба
                        </button> --}}
                    @endif
                @endauth
            </div>
        
        <!-- Форма ответа (изначально скрыта) -->
        @auth
        <div class="reply-form mt-3" style="display: none;">
            <form class="reply-comment-form" action="{{ route('comments.store') }}" method="POST" data-parent-id="{{ $comment->id }}">
                @csrf
                <input type="hidden" name="art_id" value="{{ $art->id ?? $comment->art_id }}">
                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                <div class="form-group">
                    <textarea name="body" class="reply-text" rows="2" placeholder="Ваш ответ..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-sm" style="cursor:pointer; border: none; background: none; color:#43E6B1">отправить</button>
                <button type="button" class="btn btn-secondary btn-sm cancel-reply" style="cursor:pointer; border: none; background: none; color:#F2603E">отмена</button>
            </form>
        </div>
        @endauth
        
        <!-- Ответы на комментарий -->
        @if($comment->replies->count() > 0)
            <div class="replies" style="padding-left: 30px; margin-bottom:10px">
                @foreach($comment->replies as $reply)
                    @include('comments.comment', ['comment' => $reply])
                @endforeach
            </div>
        @endif
    </div>
</div>