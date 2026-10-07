@extends('layouts.app')

@section('title', 'Social Comments Tracker')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Social Comments Tracker</h3>
        <p class="text-muted small mb-0">Track and monitor comments grouped by Facebook & Instagram posts</p>
    </div>
    <div class="d-flex gap-2">
        <form method="GET" action="{{ route('inbox.comments') }}" class="d-flex gap-2">
            @if($selectedPostId)
                <input type="hidden" name="post_id" value="{{ $selectedPostId }}">
            @endif
            <select name="platform" class="form-select rounded-pill px-4 shadow-sm border" onchange="this.form.submit()">
                <option value="all" {{ request('platform') == 'all' ? 'selected' : '' }}>All Platforms</option>
                <option value="facebook" {{ request('platform') == 'facebook' ? 'selected' : '' }}>Facebook</option>
                <option value="instagram" {{ request('platform') == 'instagram' ? 'selected' : '' }}>Instagram</option>
            </select>
        </form>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Posts List -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white pt-4 pb-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="fa-solid fa-newspaper text-primary me-2"></i> Recent Posts
                </h6>
                <span class="badge bg-light text-dark border">{{ $posts->count() }} Posts</span>
            </div>
            <div class="list-group list-group-flush rounded-bottom-4 overflow-hidden">
                <a href="{{ route('inbox.comments') }}" class="list-group-item list-group-item-action p-3 border-bottom {{ !$selectedPostId ? 'bg-primary-subtle fw-bold text-primary border-start border-4 border-primary' : '' }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fa-solid fa-layer-group me-2"></i> All Posts
                        </div>
                        <span class="badge bg-secondary rounded-pill">Total {{ $comments->total() }}</span>
                    </div>
                </a>

                @forelse($posts as $post)
                    <a href="{{ route('inbox.comments', ['post_id' => $post->external_post_id, 'platform' => request('platform')]) }}" class="list-group-item list-group-item-action p-3 border-bottom {{ $selectedPostId === $post->external_post_id ? 'bg-primary-subtle fw-bold text-primary border-start border-4 border-primary' : '' }}">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <span class="text-truncate d-block" style="max-width: 190px;" title="Post ID: {{ $post->external_post_id }}">
                                <i class="fa-solid fa-file-lines me-1 text-muted"></i> Post #{{ substr($post->external_post_id, -8) }}
                            </span>
                            <span class="badge bg-primary rounded-pill ms-2">{{ $post->comments_count }} {{ Str::plural('comment', $post->comments_count) }}</span>
                        </div>
                        <div class="text-muted small fw-normal">
                            <i class="fa-regular fa-clock me-1"></i>{{ \Carbon\Carbon::parse($post->latest_comment_at)->diffForHumans() }}
                        </div>
                    </a>
                @empty
                    <div class="text-center p-4 text-muted">
                        <small>No post comments recorded yet.</small>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column: Comments Feed -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white pt-4 pb-3 px-4 d-flex justify-content-between align-items-center border-bottom">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="fa-solid fa-comments text-primary me-2"></i> 
                    @if($selectedPostId)
                        Comments on Post #{{ substr($selectedPostId, -8) }}
                    @else
                        All Social Comments ({{ $comments->total() }})
                    @endif
                </h6>
                <span class="badge bg-soft-success text-success px-3 py-2 rounded-pill">
                    <i class="fa-solid fa-robot me-1"></i> AI Auto-Reply Active
                </span>
            </div>
            
            <div class="card-body p-0">
                @forelse($comments as $comment)
                    <div class="p-4 border-bottom {{ $loop->even ? 'bg-light-subtle' : '' }}">
                        <div class="d-flex gap-3">
                            <img src="https://ui-avatars.com/api/?background=random&name={{ urlencode($comment->customer_name) }}" class="rounded-circle shadow-sm" width="46" height="46" alt="{{ $comment->customer_name }}">
                            
                            <div class="w-100">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-bold text-dark fs-6">{{ $comment->customer_name }}</span>
                                        <span class="badge bg-light text-muted border">
                                            <i class="fa-brands fa-{{ $comment->platform === 'instagram' ? 'instagram text-danger' : 'facebook text-primary' }} me-1"></i>
                                            {{ ucfirst($comment->platform) }}
                                        </span>
                                    </div>
                                    <small class="text-muted"><i class="fa-regular fa-clock me-1"></i>{{ $comment->created_at->diffForHumans() }}</small>
                                </div>
                                
                                <p class="mb-3 text-dark fs-6 fw-medium bg-white p-3 rounded-3 border">{{ $comment->comment_text }}</p>
                                
                                <!-- AI Reply Section -->
                                @if($comment->ai_reply_text)
                                    <div class="p-3 rounded-3 mb-3 border border-indigo-subtle position-relative" style="background-color: #EEF2FF; border-color: #C7D2FE !important;">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <small class="fw-bold text-primary">
                                                <i class="fa-solid fa-wand-magic-sparkles me-1"></i> AI Reply Published
                                            </small>
                                            <span class="badge bg-success rounded-pill px-3">Replied</span>
                                        </div>
                                        <p class="mb-0 text-dark small">{{ $comment->ai_reply_text }}</p>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center gap-2 mb-2 text-warning small">
                                        <i class="fa-solid fa-hourglass-half"></i>
                                        <span>AI processing reply for this comment...</span>
                                    </div>
                                @endif

                                <div class="d-flex justify-content-between align-items-center pt-2">
                                    <small class="text-muted">Post ID: <code>{{ $comment->external_post_id ?: 'Page Post' }}</code></small>
                                    <div class="d-flex gap-2">
                                        <a href="https://facebook.com/{{ $comment->external_comment_id }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> View on {{ ucfirst($comment->platform) }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="fa-regular fa-comments fs-1 mb-3 d-block text-primary opacity-50"></i>
                        <h5 class="fw-bold text-dark">No comments recorded for this post</h5>
                        <p class="text-muted small mb-3">Select another post or clear filters to view all comments.</p>
                        @if($selectedPostId)
                            <a href="{{ route('inbox.comments') }}" class="btn btn-sm btn-outline-primary rounded-pill px-4">View All Comments</a>
                        @endif
                    </div>
                @endforelse
            </div>
            
            @if($comments->hasPages())
                <div class="card-footer bg-white py-3 px-4 border-top">
                    {{ $comments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection


