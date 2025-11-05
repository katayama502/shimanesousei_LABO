@extends('adminlte::page')

@section('title', '審査キュー')

@section('content_header')
    <h1>審査キュー</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>タイトル</th>
                    <th>クラブ</th>
                    <th>申請日</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($projects as $project)
                    <tr>
                        <td>{{ $project->title }}</td>
                        <td>{{ $project->organization->name }}</td>
                        <td>{{ $project->updated_at->format('Y-m-d') }}</td>
                        <td>
                            <form method="post" action="{{ route('admin.reviews.action', $project) }}" class="d-inline">
                                @csrf
                                <input type="hidden" name="action" value="approve">
                                <button class="btn btn-success btn-sm">承認</button>
                            </form>
                            <form method="post" action="{{ route('admin.reviews.action', $project) }}" class="d-inline">
                                @csrf
                                <input type="hidden" name="action" value="reject">
                                <input type="hidden" name="reason" value="要修正">
                                <button class="btn btn-danger btn-sm">差戻し</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $projects->links() }}
        </div>
    </div>
@endsection
