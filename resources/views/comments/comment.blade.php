<div class="comment card mb-3" data-id="{{ $comment->id }}">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h5 class="card-title"><a href="/profile/{{ $comment->user_id }}">{{ $comment->user->name }}</a></h5>
            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
        </div>
        
        <p class="card-text">{{ $comment->body }}</p>
        
        <!-- Кнопка ответа -->
        <div class="comment-actions mt-2">
                @auth
                    <button class="btn btn-sm btn-outline-secondary reply-btn" data-comment-id="{{ $comment->id }}">
                        reply
                    </button>
                    @if($comment->user_id == auth()->id() || auth()->user()->isModerator())
                        <button class="delete-comment text-red-500 text-sm" data-id="{{ $comment->id }}">
                            delete
                        </button>
                    @else
                        <button class="report-comment text-red-500 text-sm" data-id="{{ $comment->id }}">
                            report
                        </button>
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
                    <textarea name="body" class="form-control" rows="2" placeholder="Ваш ответ..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Отправить ответ</button>
                <button type="button" class="btn btn-secondary btn-sm cancel-reply">Отмена</button>
            </form>
        </div>
        @endauth
        
        <!-- Ответы на комментарий -->
        @if($comment->replies->count() > 0)
            <div class="replies mt-3 ml-4">
                @foreach($comment->replies as $reply)
                    @include('comments.comment', ['comment' => $reply])
                @endforeach
            </div>
        @endif
    </div>
</div>