<div class="card h-100 shadow-sm">
    @if($project->thumbnail_path)
        <img src="{{ Storage::url($project->thumbnail_path) }}" class="card-img-top" alt="{{ $project->title }}">
    @else
        <div class="bg-secondary text-white text-center py-5">No Image</div>
    @endif
    <div class="card-body d-flex flex-column">
        <h5 class="card-title">{{ $project->title }}</h5>
        <p class="text-muted mb-1"><i class="fa fa-map-marker-alt"></i> {{ $project->prefecture }} {{ $project->city }}</p>
        <p class="text-muted">目標: ¥{{ number_format($project->target_amount) }}</p>
        <div class="progress mb-2">
            @php $rate = $project->target_amount > 0 ? min(100, round($project->current_amount / $project->target_amount * 100)) : 0; @endphp
            <div class="progress-bar" style="width: {{ $rate }}%">{{ $rate }}%</div>
        </div>
        <p class="small text-muted">残り日数: {{ $project->remaining_days ?? '未設定' }}</p>
        <a href="{{ route('projects.show', $project->slug) }}" class="btn btn-primary mt-auto">詳細を見る</a>
    </div>
</div>
