@extends('adminlte::page')

@section('title', '協賛申込')

@section('content_header')
    <h1>協賛申込一覧</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                <tr>
                    <th>募集タイトル</th>
                    <th>申込金額</th>
                    <th>ステータス</th>
                    <th>支払方法</th>
                    <th>申込日</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($sponsorships as $sponsorship)
                    <tr>
                        <td>{{ $sponsorship->project->title }}</td>
                        <td>¥{{ number_format($sponsorship->amount) }}</td>
                        <td><span class="badge bg-info">{{ $sponsorship->status }}</span></td>
                        <td>{{ $sponsorship->payment_method }}</td>
                        <td>{{ $sponsorship->created_at->format('Y-m-d') }}</td>
                        <td class="text-end">
                            <a href="{{ route('sponsorships.show', $sponsorship) }}" class="btn btn-sm btn-outline-primary">詳細</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">
        {{ $sponsorships->links() }}
    </div>
@endsection
