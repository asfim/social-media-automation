@extends('layouts.app')

@section('title', $title ?? 'Page Module')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-5 text-center">
                <div class="mb-4">
                    <i class="fa-solid fa-person-digging text-primary" style="font-size: 4rem;"></i>
                </div>
                <h3 class="mb-3">{{ $title ?? 'Module Under Construction' }}</h3>
                <p class="text-muted mb-4">This section of the AI Assistant is currently being built by your AI developer. Check back soon!</p>
                <a href="{{ url('/') }}" class="btn btn-primary px-4 rounded-pill">
                    <i class="fa-solid fa-arrow-left me-2"></i> Return to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
