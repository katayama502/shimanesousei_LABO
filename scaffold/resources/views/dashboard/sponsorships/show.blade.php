@extends('adminlte::page')

@section('title', '協賛申込詳細')

@section('content_header')
    <h1>{{ $sponsorship->project->title }} 協賛申込</h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">申込情報</div>
                <div class="card-body">
                    <p>企業: {{ $sponsorship->company->name }}</p>
                    <p>プラン: {{ optional($sponsorship->tier)->name ?? '任意' }}</p>
                    <p>金額: ¥{{ number_format($sponsorship->amount) }}</p>
                    <p>支払方法: {{ $sponsorship->payment_method }}</p>
                    <p>ステータス: <span class="badge bg-info">{{ $sponsorship->status }}</span></p>
                    @can('updateStatus', $sponsorship)
                        <form method="post" action="{{ route('sponsorships.status', $sponsorship) }}" class="mt-3">
                            @csrf
                            <label class="form-label">ステータス更新</label>
                            <select name="status" class="form-select mb-2">
                                @foreach(['approved','rejected','canceled','completed'] as $status)
                                    <option value="{{ $status }}" @selected($sponsorship->status === $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-primary">更新</button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">メッセージ</div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @foreach($sponsorship->messages as $message)
                        <div class="mb-3">
                            <div class="fw-bold">{{ $message->sender->name }} <span class="text-muted small">{{ $message->created_at->format('Y-m-d H:i') }}</span></div>
                            <div>{!! nl2br(e($message->body)) !!}</div>
                        </div>
                    @endforeach
                </div>
                <div class="card-footer">
                    <form method="post" action="{{ route('sponsorships.messages.store', $sponsorship) }}">
                        @csrf
                        <div class="mb-2">
                            <textarea name="body" class="form-control" rows="3" maxlength="2000" required></textarea>
                        </div>
                        <button class="btn btn-primary">送信</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
