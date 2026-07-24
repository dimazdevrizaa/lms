{{-- Discussion Forum Partial for Meeting Pages --}}
{{-- Expects: $meeting, $discussionPosts --}}
<div class="content-card mb-5 reveal" id="diskusi">
    <div class="content-card-header bg-white d-flex align-items-center justify-content-between py-3">
        <div class="d-flex align-items-center gap-2">
            <div class="content-card-header-icon" style="background: linear-gradient(135deg, rgba(63, 81, 181, 0.15), rgba(63, 81, 181, 0.06)); color: #3f51b5;">
                <i class="fas fa-comments"></i>
            </div>
            <h5 class="content-card-title" style="color: #3f51b5 !important;">Forum Diskusi</h5>
            @if(isset($discussionPosts) && $discussionPosts->count() > 0)
                <span class="badge bg-primary-subtle text-primary rounded-pill ms-1" style="font-size: 0.7rem; background-color: rgba(63, 81, 181, 0.1) !important; color: #3f51b5 !important;">{{ $discussionPosts->count() }}</span>
            @endif
        </div>
    </div>
    <div class="content-card-body p-4">
        {{-- New Post Form --}}
        <form action="{{ route('meeting.discussion.store', $meeting) }}" method="POST" class="mb-4">
            @csrf
            <div class="d-flex gap-3">
                <div class="d-flex align-items-start justify-content-center rounded-circle text-white fw-bold" style="width: 40px; height: 40px; min-width: 40px; background: linear-gradient(135deg, var(--primary), var(--primary-light)); font-size: 0.9rem; font-family: 'Plus Jakarta Sans', sans-serif; line-height: 40px; text-align: center;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="flex-grow-1">
                    <textarea name="content" class="form-control mb-2" rows="2" placeholder="Tulis pertanyaan atau diskusi..." style="border-radius: 12px; border: 1px solid rgba(0,0,0,0.08); resize: none; font-size: 0.9rem;" required></textarea>
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-1">
                        <small class="text-muted" style="font-size: 0.7rem;">
                            <i class="fas fa-shield-alt me-1 text-warning"></i>
                            Harap gunakan bahasa yang sopan. Hindari konten kasar atau menyinggung.
                        </small>
                        <button type="submit" class="btn btn-sm px-4 py-2 text-white fw-bold" style="background-color: #3f51b5; border: none; border-radius: 10px; font-family: 'Plus Jakarta Sans', sans-serif;">
                            <i class="fas fa-paper-plane me-1"></i> Kirim
                        </button>
                    </div>
                </div>
            </div>
        </form>


        {{-- Posts List --}}
        @if(isset($discussionPosts) && $discussionPosts->count() > 0)
            @foreach($discussionPosts as $post)
                <div class="border rounded-3 p-3 mb-3" style="border-color: rgba(0,0,0,0.06) !important; background-color: #fafbfc;">
                    {{-- Post Header --}}
                    <div class="d-flex align-items-start gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle text-white fw-bold" style="width: 36px; height: 36px; min-width: 36px; background: {{ $post->user->role === 'guru' ? 'linear-gradient(135deg, #f9a825, #fbc02d)' : 'linear-gradient(135deg, var(--primary), var(--primary-light))' }}; font-size: 0.8rem; font-family: 'Plus Jakarta Sans', sans-serif; line-height: 36px; text-align: center;">
                            {{ strtoupper(substr($post->user->name, 0, 2)) }}
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <div>
                                    <span class="fw-bold text-dark" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.9rem;">{{ $post->user->name }}</span>
                                    @if($post->user->role === 'guru')
                                        <span class="badge bg-warning-subtle text-warning ms-1 rounded-pill" style="font-size: 0.65rem;">Guru</span>
                                    @endif
                                    <span class="text-muted ms-2" style="font-size: 0.75rem;">{{ $post->created_at->diffForHumans() }}</span>
                                </div>
                                @if(auth()->id() === $post->user_id || auth()->user()->role === 'guru')
                                    <form action="{{ route('meeting.discussion.destroy', $post) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-link text-muted p-0" title="Hapus" onclick="return confirm('Hapus diskusi ini?')">
                                            <i class="fas fa-trash-alt" style="font-size: 0.75rem;"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                            <p class="mb-2 text-dark" style="font-size: 0.9rem; line-height: 1.5; white-space: pre-line;">{{ $post->content }}</p>

                            {{-- Comments --}}
                            @if($post->comments && $post->comments->count() > 0)
                                <div class="ms-0 mt-3 pt-2" style="border-top: 1px solid rgba(0,0,0,0.05);">
                                    @foreach($post->comments as $comment)
                                        @include('partials.discussion-comment-node', ['comment' => $comment, 'post' => $post, 'depth' => 1])
                                    @endforeach
                                </div>
                            @endif

                            {{-- Reply Form --}}
                            <div class="mt-2">
                                <button class="btn btn-sm btn-link text-muted p-0 toggle-reply" data-target="reply-form-{{ $post->id }}" style="font-size: 0.8rem; text-decoration: none; font-weight: 600;">
                                    <i class="fas fa-comment me-1"></i> Komentar
                                </button>
                                <form action="{{ route('meeting.discussion.comment', $post) }}" method="POST" class="mt-2 d-none" id="reply-form-{{ $post->id }}">
                                    @csrf
                                    <div class="d-flex gap-2">
                                        <input type="text" name="content" class="form-control form-control-sm" placeholder="Tulis komentar..." style="border-radius: 8px; font-size: 0.85rem;" required>
                                        <button type="submit" class="btn btn-sm px-3 text-white" style="background-color: #3f51b5; border: none; border-radius: 8px; white-space: nowrap;">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center py-4 text-muted">
                <i class="fas fa-comments fa-3x mb-3" style="opacity: 0.15;"></i>
                <p class="mb-0">Belum ada diskusi untuk pertemuan ini. Mulai diskusi pertama!</p>
            </div>
        @endif
    </div>
</div>

{{-- Toggle reply form script --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toggle-reply').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var target = document.getElementById(this.getAttribute('data-target'));
            if (target) {
                target.classList.toggle('d-none');
                if (!target.classList.contains('d-none')) {
                    target.querySelector('input[name="content"]').focus();
                }
            }
        });
    });
});
</script>
