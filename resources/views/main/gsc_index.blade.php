<!DOCTYPE html>
<html lang="ja">
<head>
    <title>GSC集計データ一覧 | @include('components.sitename')</title>
    @include('components.header')
    <link rel="canonical" href="{{ url()->current() }}">
</head>
<body>
    <h1>@include('components.sitename')</h1>

    <h2>GSC 集計データ @isset($directory)（{{ $directory }}）@endisset</h2>

@include('components.yyyy-mm-form')


<h2>ページURL別 チャート</h2>
@include('components.chart.gsc-chart')


    <div class="table-container">
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>ページURL</th>
                    <th>インプレッション数</th>
                    <th>クリック数</th>
                    <th>CTR（%）</th>
                    <th>平均掲載順位</th>
                    <th>開始日</th>
                    <th>終了日</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($records as $record)
                    <tr>
                        <td>{{ Str::replace('https://www.qsha-oh.com', '', $record->page_url) }}</td>
                        <td>{{ number_format($record->total_impressions) }}</td>
                        <td>{{ number_format($record->total_clicks) }}</td>
                        <td>{{ number_format($record->avg_ctr * 100, 2) }}%</td>
                        <td>{{ number_format($record->avg_position, 2) }}</td>
                        <td>{{ $record->start_date->format('Y-m-d') }}</td>
                        <td>{{ $record->end_date->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>
@include('components.footer')
</html>
