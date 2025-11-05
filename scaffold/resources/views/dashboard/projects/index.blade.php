@extends('adminlte::page')

@section('title', '募集管理')

@section('content_header')
    <h1>募集管理</h1>
@endsection

@section('content')
    <div class="mb-3 text-end">
        <a href="{{ route('dashboard.projects.create') }}" class="btn btn-primary">新規募集</a>
    </div>
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                <tr>
                    <th>タイトル</th>
                    <th>ステータス</th>
                    <th>協賛申込</th>
                    <th>更新日時</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($projects as $project)
                    <tr>
                        <td>{{ $project->title }}</td>
                        <td><span class="badge bg-secondary">{{ $project->status }}</span></td>
                        <td>{{ $project->sponsorships_count }}</td>
                        <td>{{ $project->updated_at->format('Y-m-d H:i') }}</td>
                        <td class="text-end">
                            <a href="{{ route('dashboard.projects.edit', $project) }}" class="btn btn-sm btn-outline-primary">編集</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">
        {{ $projects->links() }}
    </div>
@endsection
