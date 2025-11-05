@extends('layouts.public')

@section('content')
<h2 class="mb-4">募集を探す</h2>
<form class="row g-3 mb-4" method="get">
    <div class="col-md-4">
        <label class="form-label">キーワード</label>
        <input type="text" name="q" value="{{ request('q') }}" class="form-control">
    </div>
    <div class="col-md-2">
        <label class="form-label">都道府県</label>
        <input type="text" name="prefecture" value="{{ request('prefecture') }}" class="form-control">
    </div>
    <div class="col-md-2">
        <label class="form-label">カテゴリ種別</label>
        <select name="type" class="form-select">
            <option value="">すべて</option>
            <option value="sport" @selected(request('type')==='sport')>スポーツ</option>
            <option value="culture" @selected(request('type')==='culture')>文化</option>
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label">カテゴリ</label>
        <select name="category" class="form-select">
            <option value="">選択してください</option>
            @foreach((request('type')==='culture' ? $cultureCategories : $sportCategories) as $category)
                <option value="{{ $category->slug }}" @selected(request('category')===$category->slug)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label">最低金額</label>
        <input type="number" name="min_amount" value="{{ request('min_amount') }}" class="form-control">
    </div>
    <div class="col-md-2">
        <label class="form-label">締切日</label>
        <input type="date" name="deadline" value="{{ request('deadline') }}" class="form-control">
    </div>
    <div class="col-12 text-end">
        <button class="btn btn-primary">検索</button>
    </div>
</form>
<div class="row g-3">
    @forelse($projects as $project)
        <div class="col-md-4">
            @include('public.partials.project-card', ['project' => $project])
        </div>
    @empty
        <p>条件に合致する募集が見つかりませんでした。</p>
    @endforelse
</div>
<div class="mt-4">
    {{ $projects->links() }}
</div>
@endsection
