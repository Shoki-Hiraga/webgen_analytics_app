<link rel="stylesheet" href="{{ asset('css/navi.css') }}">

<nav aria-label="breadcrumb">
    @php
        use App\Helpers\BreadcrumbHelper;

        $breadcrumbs = BreadcrumbHelper::generate();
    @endphp

    @if (!empty($breadcrumbs))
        <ol class="breadcrumb">
            {{-- TOPリンク --}}
            <li class="breadcrumb-item">
                <a href="{{ url('/') }}">TOP</a>
            </li>

            {{-- パンくずループ --}}
            @foreach ($breadcrumbs as $index => $crumb)
                @php $isLast = $loop->last; @endphp

                @if ($isLast)
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ $crumb['name'] }}
                    </li>
                @else
                    <li class="breadcrumb-item">
                        <a href="{{ $crumb['url'] }}">{{ $crumb['name'] }}</a>
                    </li>
                @endif
            @endforeach
        </ol>
    @endif
</nav>
