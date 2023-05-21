@extends('layouts.frontend')

@section('title')
    {{ __('app.markets') }}
@endsection

@section('content')
    <data-feed></data-feed>
    <div class="ui tablet stackable grid container">
        @if($assets->isEmpty())
            <div class="sixteen wide column">
                <div class="ui segment">
                    <p>{{ __('app.assets_empty') }}</p>
                </div>
            </div>
        @else
            @if($markets->count() > 1)
                <div class="five wide column">
                    <div id="market-dropdown" class="ui selection fluid dropdown">
                        <i class="dropdown icon"></i>
                        <div class="default text"></div>
                        <div class="menu">
                            @foreach($markets as $market)
                                <a href="{{ route('frontend.assets.index') }}?market={{ $market->code }}" data-value="{{ $market->code }}" class="item"><i class="{{ $market->country_code }} flag"></i>{{ $market->name }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            <div class="sixteen wide column">
                <assets-table :assets-list="{{ $assets->getCollection() }}" class="ui selectable {{ $inverted }} table">
                    <template slot="header">
                        @component('components.tables.sortable-column', ['id' => 'symbol', 'sort' => $sort, 'order' => $order, 'query' => ['market' => $selected_market]])
                        {{ __('app.name') }}
                        @endcomponent
                        @component('components.tables.sortable-column', ['id' => 'price', 'sort' => $sort, 'order' => $order, 'class' => 'right aligned', 'query' => ['market' => $selected_market]])
                        {{ __('app.price') }}
                        @endcomponent
                        @component('components.tables.sortable-column', ['id' => 'change_abs', 'sort' => $sort, 'order' => $order, 'class' => 'right aligned', 'query' => ['market' => $selected_market]])
                        {{ __('app.change_abs') }}
                        @endcomponent
                        @component('components.tables.sortable-column', ['id' => 'change_pct', 'sort' => $sort, 'order' => $order, 'class' => 'right aligned', 'query' => ['market' => $selected_market]])
                        {{ __('app.change_pct') }}
                        @endcomponent
                        @component('components.tables.sortable-column', ['id' => 'market_cap', 'sort' => $sort, 'order' => $order, 'class' => 'right aligned', 'query' => ['market' => $selected_market]])
                        {{ __('app.market_cap') }}
                        @endcomponent
                        @component('components.tables.sortable-column', ['id' => 'trades_count', 'sort' => $sort, 'order' => $order, 'class' => 'right aligned', 'query' => ['market' => $selected_market]])
                        {{ __('app.trades') }}
                        @endcomponent
                    </template>
                </assets-table>
            </div>
            <div class="sixteen wide right aligned column">
                {{ $assets->appends(['sort' => $sort])->appends(['order' => $order])->appends(['market' => $selected_market])->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    $('#market-dropdown').dropdown('set selected', '{{ $selected_market }}');
</script>
@endpush