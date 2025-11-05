@extends('adminlte::page')

@section('title', '募集編集')

@section('content_header')
    <h1>{{ $project->title }}の編集</h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <form method="post" action="{{ route('dashboard.projects.update', $project) }}">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">タイトル</label>
                                <input type="text" name="title" class="form-control" value="{{ $project->title }}" maxlength="120" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">概要</label>
                                <textarea name="summary" class="form-control" maxlength="300">{{ $project->summary }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">詳細説明</label>
                                <textarea name="description" class="form-control" rows="6" maxlength="20000">{{ $project->description }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">開始日</label>
                                <input type="date" name="start_at" value="{{ optional($project->start_at)->format('Y-m-d') }}" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">終了日</label>
                                <input type="date" name="end_at" value="{{ optional($project->end_at)->format('Y-m-d') }}" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">目標金額</label>
                                <input type="number" name="target_amount" value="{{ $project->target_amount }}" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">スポーツカテゴリ</label>
                                <select name="sport_category_id" class="form-select">
                                    <option value="">未選択</option>
                                    @foreach($categories['sport'] as $category)
                                        <option value="{{ $category->id }}" @selected($project->sport_category_id === $category->id)>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">文化カテゴリ</label>
                                <select name="culture_category_id" class="form-select">
                                    <option value="">未選択</option>
                                    @foreach($categories['culture'] as $category)
                                        <option value="{{ $category->id }}" @selected($project->culture_category_id === $category->id)>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">タグ</label>
                                <select name="tags[]" class="form-select" multiple>
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->id }}" @selected($project->tags->contains($tag))>{{ $tag->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">都道府県</label>
                                <input type="text" name="prefecture" class="form-control" value="{{ $project->prefecture }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">市区町村</label>
                                <input type="text" name="city" class="form-control" value="{{ $project->city }}">
                            </div>
                        </div>
                        <div class="mt-4 d-flex gap-2">
                            <button class="btn btn-primary">保存</button>
                            <button name="submit_for_review" value="1" class="btn btn-warning">審査に提出</button>
                        </div>
                    </form>
                    <form method="post" action="{{ route('dashboard.projects.destroy', $project) }}" class="mt-3">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger" onclick="return confirm('削除しますか？')">削除</button>
                    </form>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">活動報告の追加</div>
                <div class="card-body">
                    <form method="post" action="{{ route('dashboard.projects.updates.store', $project) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">タイトル</label>
                            <input type="text" name="title" class="form-control" required maxlength="120">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">本文</label>
                            <textarea name="body" class="form-control" rows="4" maxlength="20000" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">公開日</label>
                            <input type="datetime-local" name="published_at" class="form-control" required>
                        </div>
                        <button class="btn btn-primary">追加</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">メディアの追加</div>
                <div class="card-body">
                    <form method="post" action="{{ route('dashboard.projects.media.store', $project) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label">タイプ</label>
                            <select name="type" class="form-select">
                                <option value="image">画像</option>
                                <option value="video">動画リンク</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">画像ファイル</label>
                            <input type="file" name="media" class="form-control" accept="image/*">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">動画URL</label>
                            <input type="url" name="url" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">キャプション</label>
                            <input type="text" name="caption" class="form-control" maxlength="255">
                        </div>
                        <button class="btn btn-primary">追加</button>
                    </form>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">協賛プランの追加</div>
                <div class="card-body">
                    <form method="post" action="{{ route('dashboard.projects.tiers.store', $project) }}">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label">プラン名</label>
                            <input type="text" name="name" class="form-control" maxlength="120" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">金額</label>
                            <input type="number" name="amount" class="form-control" min="1000" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">詳細</label>
                            <textarea name="description" class="form-control" rows="3" maxlength="1000"></textarea>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">上限数</label>
                            <input type="number" name="limit_qty" class="form-control" min="1">
                        </div>
                        <button class="btn btn-primary">追加</button>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">既存メディア</div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($project->media as $media)
                            <li class="list-group-item">{{ $media->caption ?? $media->path }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
