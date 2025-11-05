@extends('layouts.public')

@section('content')
<div class="row mb-4">
    <div class="col-lg-8">
        <h2 class="mb-3">新着募集</h2>
        <div class="row g-3">
            @forelse($projects as $project)
                <div class="col-md-6">
                    @include('public.partials.project-card', ['project' => $project])
                </div>
            @empty
                <p>現在募集中の案件はありません。</p>
            @endforelse
        </div>
    </div>
    <div class="col-lg-4">
        <div class="bg-light p-4 rounded">
            <h3 class="h5 mb-3">注目の募集</h3>
            <ul class="list-unstyled">
                @foreach($featured as $project)
                    <li class="mb-3">
                        <a href="{{ route('projects.show', $project->slug) }}" class="fw-bold">{{ $project->title }}</a>
                        <div class="text-muted small">{{ $project->organization->name }}</div>
                    </li>
                @endforeach
            </ul>
            <a href="{{ route('projects.search') }}" class="btn btn-outline-primary w-100">すべての募集を見る</a>
        </div>
    </div>
</div>
@endsection
