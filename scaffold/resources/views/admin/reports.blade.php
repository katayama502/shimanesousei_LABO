@extends('adminlte::page')

@section('title', '通報一覧')

@section('content_header')
    <h1>通報一覧</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>対象</th>
                    <th>理由</th>
                    <th>通報者</th>
                    <th>ステータス</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($reports as $report)
                    <tr>
                        <td>{{ $report->id }}</td>
                        <td>{{ class_basename($report->reportable_type) }} #{{ $report->reportable_id }}</td>
                        <td>{{ $report->reason }}</td>
                        <td>{{ optional($report->reporter)->name }}</td>
                        <td>{{ $report->status }}</td>
                        <td>
                            @if($report->status === 'open')
                                <form method="post" action="{{ route('admin.reports.resolve', $report) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-success">解決済みにする</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">
        {{ $reports->links() }}
    </div>
@endsection
