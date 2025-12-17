<!DOCTYPE html>
<html lang="ja">
<head>
    <title>GSC FullURL 期間指定 | @include('components.sitename')</title>
    @include('components.header')
</head>

<body>
<h1>@include('components.sitename')</h1>

<h2>GSC FullURL 期間指定</h2>

<form method="GET" action="{{ url()->current() }}">
    <label>
        開始月:
        <input type="month" name="start_month" value="{{ request('start_month') }}">
    </label>

    <label>
        終了月:
        <input type="month" name="end_month" value="{{ request('end_month') }}">
    </label>

    {{-- 通常表示 --}}
    <button type="submit">表示</button>

    {{-- 計算 --}}
    <button type="submit" name="calculate" value="1">
        計算する
    </button>

    {{-- CSV（計算モード専用） --}}
    @if(request()->filled('calculate'))
        <input type="hidden" name="calculate" value="1">

        <button type="submit" name="csv" value="1">
            CSV出力
        </button>
    @endif
</form>

{{-- ===== サマリー ===== --}}
@if(isset($summary))
<h3>合計（全URL）</h3>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>インプレッション</th>
        <th>クリック</th>
        <th>CTR</th>
        <th>平均掲載順位</th>
    </tr>
    <tr>
        <td>{{ number_format($summary->total_impressions) }}</td>
        <td>{{ number_format($summary->total_clicks) }}</td>
        <td>{{ number_format($summary->avg_ctr, 2) }}%</td>
        <td>{{ number_format($summary->avg_position, 2) }}</td>
    </tr>
</table>
@endif

{{-- ===== URL別 ===== --}}
<div class="table-container">
<table border="1" cellpadding="8" cellspacing="0">
    <thead>
    <tr>
        <th>ページURL</th>
        <th>インプレッション</th>
        <th>クリック</th>
        <th>CTR</th>
        <th>平均掲載順位</th>
    </tr>
    </thead>
    <tbody>
    @foreach($records as $record)
        <tr>
            <td>{{ Str::replace('https://www.qsha-oh.com', '', $record->page_url) }}</td>
            <td>{{ number_format($record->total_impressions) }}</td>
            <td>{{ number_format($record->total_clicks) }}</td>
            <td>
                {{ request()->filled('calculate')
                    ? number_format($record->avg_ctr, 2)
                    : number_format($record->avg_ctr * 100, 2)
                }}%
            </td>
            <td>{{ number_format($record->avg_position, 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>

{{ $records->links() }}

</body>
@include('components.footer')
</html>
