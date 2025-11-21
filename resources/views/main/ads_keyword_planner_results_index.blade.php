<!DOCTYPE html>
<html lang="ja">
<head>
    <title>Ads Keyword Planner 結果一覧 | @include('components.sitename')</title>
    @include('components.header')
    <link rel="canonical" href="{{ url()->current() }}">
</head>

<body>

    <h1>@include('components.sitename')</h1>

    <h2>Ads Keyword Planner 結果一覧</h2>

    {{-- 🔍 original_keyword で検索 --}}
    <form method="GET" action="" style="margin:20px;">
        <input 
            type="text" 
            name="original_keyword" 
            placeholder="original_keyword を検索…" 
            value="{{ request('original_keyword') }}"
            style="padding:8px; width:260px;">
        <button type="submit" style="padding:8px 16px;">検索</button>
    </form>

    {{-- CSV ダウンロード --}}
    <a href="{{ url('/ads-keyword-planner-results/csv') }}?{{ http_build_query(request()->query()) }}"
        style="display:inline-block; margin:10px 0; padding:8px 16px; background:#0070c9; color:#fff; border-radius:4px; text-decoration:none;">
        CSV ダウンロード
    </a>

    <div class="table-container">
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Original Keyword</th>
                    <th>Product</th>
                    <th>Priority</th>
                    <th>Keyword</th>
                    <th>Avg Monthly Search Volume</th>
                    <th>Competition Level</th>
                    <th>Competition Index</th>
                    <th>Low CPC</th>
                    <th>High CPC</th>
                    <th>作成日</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($results as $row)
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->original_keyword }}</td>
                        <td>{{ $row->product }}</td>
                        <td>{{ $row->priority }}</td>
                        <td>{{ $row->keyword }}</td>
                        <td>{{ $row->avg_monthly_search_volume }}</td>
                        <td>{{ $row->competition_level }}</td>
                        <td>{{ $row->competition_index }}</td>
                        <td>{{ $row->low_cpc }}</td>
                        <td>{{ $row->high_cpc }}</td>
                        <td>{{ $row->created_at?->format('Y-m-d') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11">データがありません</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ページネーション --}}
    <div class="pagination">
        {{ $results->links() }}
    </div>

@include('components.footer')

</body>
</html>
