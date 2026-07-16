{{-- Recursive/Flat Comment Node Partial (TikTok Style) --}}
{{-- Expects: $comment, $post --}}

{{-- Only render if it is a root comment (top-level) --}}
@if($comment->parent_id === null)
    <div class="comment-node-container mb-4">
        {{-- Root Comment Card --}}
        <div class="d-flex align-items-start gap-2 py-1">
            {{-- Commenter Avatar --}}
            <div class="d-flex align-items-center justify-content-center rounded-circle text-white fw-bold" 
                 style="width: 32px; height: 32px; min-width: 32px; background: {{ $comment->user->role === 'guru' ? 'linear-gradient(135deg, #f9a825, #fbc02d)' : '#90a4ae' }}; font-size: 0.75rem; font-family: 'Plus Jakarta Sans', sans-serif; line-height: 32px; text-align: center;">
                {{ strtoupper(substr($comment->user->name, 0, 2)) }}
            </div>
            
            <div class="flex-grow-1">
                {{-- Comment Header --}}
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="fw-bold text-dark" style="font-size: 0.85rem; font-family: 'Plus Jakarta Sans', sans-serif;">{{ $comment->user->name }}</span>
                        @if($comment->user->role === 'guru')
                            <span class="badge bg-warning-subtle text-warning ms-1 rounded-pill" style="font-size: 0.6rem;">Guru</span>
                        @elseif($comment->user->role === 'admin')
                            <span class="badge bg-danger-subtle text-danger ms-1 rounded-pill" style="font-size: 0.6rem;">Admin</span>
                        @endif
                        <span class="text-muted ms-2" style="font-size: 0.7rem;">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    
                    {{-- Delete Button --}}
                    @if(auth()->id() === $comment->user_id || auth()->user()->role === 'guru' || auth()->user()->role === 'admin')
                        <form action="{{ route('forum.comment.destroy', $comment) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-link text-muted p-0" title="Hapus" onclick="return confirm('Hapus komentar ini?')">
                                <i class="fas fa-trash-alt" style="font-size: 0.75rem;"></i>
                            </button>
                        </form>
                    @endif
                </div>

                {{-- Comment Body --}}
                <p class="mb-1 text-dark mt-1" style="font-size: 0.9rem; line-height: 1.4; white-space: pre-line;">{{ $comment->content }}</p>

                {{-- Reply Button --}}
                <div class="d-flex align-items-center gap-2 mb-2">
                    <button class="btn btn-sm btn-link text-muted p-0 toggle-reply" data-target="reply-comment-form-{{ $comment->id }}" style="font-size: 0.75rem; text-decoration: none; font-weight: 600;">
                        <i class="fas fa-reply me-1"></i> Balas
                    </button>
                </div>

                {{-- Inline Reply Form for Root Comment --}}
                <form action="{{ $post->meeting_id ? route('meeting.discussion.comment', $post) : route('forum.comment.store', $post) }}" method="POST" class="mt-2 mb-3 d-none" id="reply-comment-form-{{ $comment->id }}">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    <div class="d-flex gap-2">
                        <input type="text" name="content" class="form-control form-control-sm" placeholder="Balas {{ $comment->user->name }}..." style="border-radius: 8px; font-size: 0.85rem;" required>
                        <button type="submit" class="btn btn-sm px-3 text-white" style="background-color: #3f51b5; border: none; border-radius: 8px; white-space: nowrap;">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>

                {{-- TikTok-style Flat Nested Replies --}}
                @php $allReplies = $comment->all_replies; @endphp
                @if($allReplies->count() > 0)
                    <div class="comment-replies-list mt-2">
                        @foreach($allReplies as $reply)
                            <div class="comment-reply-row py-2">
                                <div class="d-flex align-items-start gap-2">
                                    {{-- Reply avatar (slightly smaller, 24px) --}}
                                    <div class="d-flex align-items-center justify-content-center rounded-circle text-white fw-bold" 
                                         style="width: 24px; height: 24px; min-width: 24px; background: {{ $reply->user->role === 'guru' ? 'linear-gradient(135deg, #f9a825, #fbc02d)' : '#b0bec5' }}; font-size: 0.55rem; font-family: 'Plus Jakarta Sans', sans-serif; line-height: 24px; text-align: center;">
                                        {{ strtoupper(substr($reply->user->name, 0, 2)) }}
                                    </div>
                                    
                                    <div class="flex-grow-1">
                                        {{-- Reply Header --}}
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <span class="fw-bold text-dark" style="font-size: 0.8rem; font-family: 'Plus Jakarta Sans', sans-serif;">{{ $reply->user->name }}</span>
                                                @if($reply->user->role === 'guru')
                                                    <span class="badge bg-warning-subtle text-warning ms-1 rounded-pill" style="font-size: 0.55rem;">Guru</span>
                                                @elseif($reply->user->role === 'admin')
                                                    <span class="badge bg-danger-subtle text-danger ms-1 rounded-pill" style="font-size: 0.55rem;">Admin</span>
                                                @endif
                                                <span class="text-muted ms-2" style="font-size: 0.65rem;">{{ $reply->created_at->diffForHumans() }}</span>
                                            </div>
                                            
                                            {{-- Delete Button --}}
                                            @if(auth()->id() === $reply->user_id || auth()->user()->role === 'guru' || auth()->user()->role === 'admin')
                                                <form action="{{ route('forum.comment.destroy', $reply) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-link text-muted p-0" title="Hapus" onclick="return confirm('Hapus balasan ini?')">
                                                        <i class="fas fa-trash-alt" style="font-size: 0.65rem;"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>

                                        {{-- Reply Body (prefix with @user mention if replying to another reply) --}}
                                        <p class="mb-1 text-dark mt-1" style="font-size: 0.85rem; line-height: 1.4;">
                                            @if($reply->parent_id !== $comment->id && $reply->parent)
                                                <span class="fw-bold" style="font-size: 0.8rem; color: #3f51b5 !important;">@&ZeroWidthSpace;{{ $reply->parent->user->name }}</span>
                                            @endif
                                            {{ $reply->content }}
                                        </p>

                                        {{-- Reply Action --}}
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <button class="btn btn-sm btn-link text-muted p-0 toggle-reply" data-target="reply-comment-form-{{ $reply->id }}" style="font-size: 0.7rem; text-decoration: none; font-weight: 600;">
                                                <i class="fas fa-reply me-1"></i> Balas
                                            </button>
                                        </div>

                                        {{-- Inline Reply Form for Reply --}}
                                        <form action="{{ $post->meeting_id ? route('meeting.discussion.comment', $post) : route('forum.comment.store', $post) }}" method="POST" class="mt-2 mb-2 d-none" id="reply-comment-form-{{ $reply->id }}">
                                            @csrf
                                            <input type="hidden" name="parent_id" value="{{ $reply->id }}">
                                            <div class="d-flex gap-2">
                                                <input type="text" name="content" class="form-control form-control-sm" placeholder="Balas {{ $reply->user->name }}..." style="border-radius: 8px; font-size: 0.85rem;" required>
                                                <button type="submit" class="btn btn-sm px-3 text-white" style="background-color: #3f51b5; border: none; border-radius: 8px; white-space: nowrap;">
                                                    <i class="fas fa-paper-plane"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif
