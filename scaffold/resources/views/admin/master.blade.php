@extends('adminlte::page')

@section('title', 'マスター管理')

@section('content_header')
    <h1>カテゴリ・タグ管理</h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">カテゴリ追加</div>
                <div class="card-body">
                    <form method="post" action="{{ route('admin.master.categories.store') }}">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label">種別</label>
                            <select name="type" class="form-select">
                                <option value="sport">スポーツ</option>
                                <option value="culture">文化</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">名称</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">スラッグ</label>
                            <input type="text" name="slug" class="form-control" required>
                        </div>
                        <button class="btn btn-primary">追加</button>
                    </form>
                </div>
                <div class="card-body border-top">
                    <h5>スポーツカテゴリ</h5>
                    <ul class="list-group mb-3">
                        @foreach($sportCategories as $category)
                            <li class="list-group-item">{{ $category->name }} ({{ $category->slug }})</li>
                        @endforeach
                    </ul>
                    <h5>文化カテゴリ</h5>
                    <ul class="list-group">
                        @foreach($cultureCategories as $category)
                            <li class="list-group-item">{{ $category->name }} ({{ $category->slug }})</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">タグ追加</div>
                <div class="card-body">
                    <form method="post" action="{{ route('admin.master.tags.store') }}">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label">名称</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">スラッグ</label>
                            <input type="text" name="slug" class="form-control" required>
                        </div>
                        <button class="btn btn-primary">追加</button>
                    </form>
                </div>
                <div class="card-body border-top">
                    <ul class="list-group">
                        @foreach($tags as $tag)
                            <li class="list-group-item">{{ $tag->name }} ({{ $tag->slug }})</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
