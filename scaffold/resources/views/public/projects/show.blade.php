@extends('layouts.public')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <h1 class="mb-3">{{ $project->title }}</h1>
        <p class="text-muted"><i class="fa fa-map-marker-alt"></i> {{ $project->prefecture }} {{ $project->city }}</p>
        <div class="mb-4">
            <div id="mediaCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach($project->media as $index => $media)
                        <div class="carousel-item @if($index===0) active @endif">
                            @if($media->type === 'image')
                                <img src="{{ asset('storage/'.$media->path) }}" class="d-block w-100" alt="{{ $media->caption }}">
                            @else
                                <div class="ratio ratio-16x9">
                                    <iframe src="{{ $media->path }}" allowfullscreen></iframe>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#mediaCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#mediaCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>
        <article class="mb-5">
            {!! nl2br(e($project->description)) !!}
        </article>
        <section class="mb-5">
            <h3 class="h5">活動報告</h3>
            @forelse($project->updates as $update)
                <div class="border rounded p-3 mb-3">
                    <h4 class="h6">{{ $update->title }}</h4>
                    <small class="text-muted">{{ $update->published_at->format('Y-m-d') }}</small>
                    <div class="mt-2">{!! nl2br(e($update->body)) !!}</div>
                </div>
            @empty
                <p>まだ活動報告はありません。</p>
            @endforelse
        </section>
        <section class="mb-5">
            <h3 class="h5">タグ</h3>
            @foreach($project->tags as $tag)
                <a href="{{ route('projects.search', ['tag' => $tag->slug]) }}" class="badge bg-secondary text-decoration-none">#{{ $tag->name }}</a>
            @endforeach
        </section>
        <section class="mb-5">
            <h3 class="h5">クラブ情報</h3>
            <div class="border rounded p-3">
                <h4 class="h6">{{ $project->organization->name }}</h4>
                <p>{{ $project->organization->description }}</p>
            </div>
        </section>
        <section class="mb-5">
            <h3 class="h5">類似の募集</h3>
            <div class="row g-3">
                @foreach($related as $relatedProject)
                    <div class="col-md-6">
                        @include('public.partials.project-card', ['project' => $relatedProject])
                    </div>
                @endforeach
            </div>
        </section>
    </div>
    <aside class="col-lg-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h3 class="card-title h5">目標額</h3>
                <p class="display-6">¥{{ number_format($project->target_amount) }}</p>
                <p>現在の協賛額: ¥{{ number_format($project->current_amount) }}</p>
                @php $rate = $project->target_amount > 0 ? min(100, round($project->current_amount / $project->target_amount * 100)) : 0; @endphp
                <div class="progress mb-2">
                    <div class="progress-bar" style="width: {{ $rate }}%">{{ $rate }}%</div>
                </div>
                <p class="text-muted">締切: {{ optional($project->end_at)->format('Y-m-d') ?? '未設定' }}</p>
            </div>
        </div>
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h4 class="h6">協賛プラン</h4>
                @foreach($project->tiers as $tier)
                    <div class="border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $tier->name }}</strong>
                            <span>¥{{ number_format($tier->amount) }}</span>
                        </div>
                        <p class="mb-2">{{ $tier->description }}</p>
                        @auth
                            @if(auth()->user()->role === 'company')
                                <form action="{{ route('projects.sponsor', $project) }}" method="post">
                                    @csrf
                                    <input type="hidden" name="tier_id" value="{{ $tier->id }}">
                                    <input type="hidden" name="amount" value="{{ $tier->amount }}">
                                    <input type="hidden" name="payment_method" value="invoice">
                                    <button class="btn btn-primary w-100" @disabled($project->isClosed())>申し込む</button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary w-100">ログインして申込</a>
                        @endauth
                    </div>
                @endforeach
                @auth
                    @if(auth()->user()->role === 'company')
                        <form action="{{ route('projects.sponsor', $project) }}" method="post" class="mt-3">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label">任意の金額</label>
                                <input type="number" name="amount" class="form-control" min="1000" value="1000">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">メッセージ</label>
                                <textarea name="message" class="form-control" rows="3" maxlength="1000"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">支払方法</label>
                                <select name="payment_method" class="form-select">
                                    <option value="invoice">請求書</option>
                                    <option value="bank">銀行振込</option>
                                    <option value="offline">その他</option>
                                </select>
                            </div>
                            <button class="btn btn-success w-100" @disabled($project->isClosed())>この内容で申し込む</button>
                        </form>
                    @endif
                @endauth
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="h6">不適切な内容を報告</h4>
                <form method="post" action="{{ route('reports.store') }}">
                    @csrf
                    <input type="hidden" name="reportable_type" value="{{ addslashes(App\Models\Project::class) }}">
                    <input type="hidden" name="reportable_id" value="{{ $project->id }}">
                    <div class="mb-2">
                        <textarea name="reason" class="form-control" rows="3" maxlength="2000" required></textarea>
                    </div>
                    <button class="btn btn-outline-danger w-100">通報する</button>
                </form>
            </div>
        </div>
    </aside>
</div>
@endsection
