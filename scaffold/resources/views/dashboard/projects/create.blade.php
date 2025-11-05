@extends('adminlte::page')

@section('title', '募集作成')

@section('content_header')
    <h1>募集作成</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="post" action="{{ route('dashboard.projects.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">所属組織</label>
                        <select name="organization_id" class="form-select">
                            @foreach(auth()->user()->organizations as $organization)
                                <option value="{{ $organization->id }}">{{ $organization->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">タイトル</label>
                        <input type="text" name="title" class="form-control" required maxlength="120">
                    </div>
                    <div class="col-12">
                        <label class="form-label">概要</label>
                        <textarea name="summary" class="form-control" maxlength="300"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">詳細説明</label>
                        <textarea name="description" class="form-control" rows="6" maxlength="20000"></textarea>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">開始日</label>
                        <input type="date" name="start_at" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">終了日</label>
                        <input type="date" name="end_at" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">目標金額</label>
                        <input type="number" name="target_amount" class="form-control" min="0" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">都道府県</label>
                        <input type="text" name="prefecture" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">市区町村</label>
                        <input type="text" name="city" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">スポーツカテゴリ</label>
                        <select name="sport_category_id" class="form-select">
                            <option value="">未選択</option>
                            @foreach($categories['sport'] as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">文化カテゴリ</label>
                        <select name="culture_category_id" class="form-select">
                            <option value="">未選択</option>
                            @foreach($categories['culture'] as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">タグ</label>
                        <select name="tags[]" class="form-select" multiple>
                            @foreach($tags as $tag)
                                <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Ctrl/Cmdキーで複数選択</small>
                    </div>
                </div>
                <div class="mt-4 text-end">
                    <button class="btn btn-primary">保存</button>
                </div>
            </form>
        </div>
    </div>
@endsection
