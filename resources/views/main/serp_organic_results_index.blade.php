<!DOCTYPE html>
<html lang="ja">
<head>
    <title>SERPオーガニック結果一覧 | @include('components.sitename')</title>
    @include('components.header')
    <link rel="canonical" href="{{ url()->current() }}">
</head>

<body>
    <h1>@include('components.sitename')</h1>

    <h2>SERP オーガニック結果一覧</h2>
    <form method="GET" action="" style="margin:20px;">
        <input 
            type="text" 
            name="original_keyword" 
            placeholder="original_keyword を検索…" 
            value="{{ request('original_keyword') }}"
            style="padding:8px; width:260px;"
        >
        <button type="submit" style="padding:8px 16px;">検索</button>
    </form>

    <a href="{{ url('/serp-organic-results/csv') }}?{{ http_build_query(request()->query()) }}"
    style="display:inline-block; margin:10px 0; padding:8px 16px; background:#0070c9; color:#fff; text-decoration:none; border-radius:4px;">
    CSVダウンロード
    </a>

    {{-- 年月フォームが必要なら --}}
    @include('components.yyyy-mm-form')

    <div class="table-container">
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Original Keyword</th>
                    <th>Product</th>
                    <th>Priority</th>
                    <th>Fetched Date</th>
                    <th>Rank</th>
                    <th>Keyword</th>
                    <th>URL</th>
                    <th>Title</th>
                    <th>作成日</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($results as $row)
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->original_keyword }}</td>
                        <td>{{ $row->product }}</td>
                        <td>{{ $row->priority }}</td>
                        <td>{{ $row->fetched_date?->format('Y-m-d') }}</td>
                        <td>{{ $row->rank }}</td>
                        <td>{{ $row->keyword }}</td>
                        <td><a href="{{ $row->url }}" target="_blank">{{ $row->url }}</a></td>
                        <td>{{ $row->title }}</td>
                        <td>{{ $row->created_at?->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ページネーション --}}
    <div class="pagination">
        {{ $results->links() }}
    </div>

</body>

@include('components.footer')
</html>
