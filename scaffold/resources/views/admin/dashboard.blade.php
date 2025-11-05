@extends('adminlte::page')

@section('title', '管理ダッシュボード')

@section('content_header')
    <h1>管理ダッシュボード</h1>
@endsection

@section('content')
    <form method="get" class="mb-3">
        <label class="form-label">期間</label>
        <select name="period" class="form-select w-auto d-inline-block">
            <option value="month" @selected($period==='month')>当月</option>
            <option value="30days" @selected($period==='30days')>直近30日</option>
        </select>
        <button class="btn btn-secondary">更新</button>
    </form>
    <div class="row">
        <div class="col-md-4">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $kpi['applications'] }}</h3>
                    <p>協賛申込数</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $kpi['approval_rate'] }}<sup style="font-size: 20px">%</sup></h3>
                    <p>承認率</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>¥{{ number_format($kpi['total_amount']) }}</h3>
                    <p>総協賛額</p>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">地域別件数</div>
        <div class="card-body">
            <canvas id="prefectureChart"></canvas>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('prefectureChart');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($kpi['by_prefecture']->pluck('prefecture')),
                datasets: [{
                    label: '件数',
                    data: @json($kpi['by_prefecture']->pluck('total')),
                    backgroundColor: '#0d6efd'
                }]
            }
        });
    </script>
@endsection
