@extends('layouts.frontend')

@section('title')
    {{ $competition->title }}
@endsection

@section('content')

    @include('pages.frontend.competitions.header')

    <div class="ui one column tablet stackable grid container">
        <div class="column">
            <table id="rankings-table" class="ui selectable tablet stackable {{ $inverted }} table">
                <thead>
                <tr>
                    <th>{{ __('app.place') }}</th>
                    <th>{{ __('users.name') }}</th>
                    <th class="right aligned">{{ __('app.trades_count') }}</th>
                    <th class="right aligned">{{ __('app.max_loss') }}, {{ $competition->currency->code }}</th>
                    <th class="right aligned">{{ __('app.max_profit') }}, {{ $competition->currency->code }}</th>
                    <th class="right aligned">{{ __('app.total_volume') }}, {{ $competition->currency->code }}</th>
                    <th class="right aligned">{{ __('app.total_pnl') }}, {{ $competition->currency->code }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($leaderboard as $i => $participant)
                    <tr>
                        <td data-title="{{ __('app.place') }}">{{ $participant->data->place ?: (max(1, intval(Request::query('page'))) - 1) * $rows_per_page + $i + 1 }}</td>
                        <td data-title="{{ __('users.name') }}">
                            <a href="{{ route('frontend.users.show', $participant->id) }}">
                                <img class="ui avatar image" src="{{ $participant->avatar_url }}">
                                {{ $participant->name }}
                            </a>
                            @if($participant->id == auth()->user()->id)
                                <span class="ui {{ $settings->color }} left pointing label">{{ __('app.you') }}</span>
                            @endif
                        </td>
                        <td data-title="{{ __('app.trades_count') }}" class="right aligned">{{ $participant->_trades_count }}</td>
                        <td data-title="{{ __('app.max_loss') }}" class="right aligned">{!! $participant->_max_loss ?: '&mdash;' !!}</td>
                        <td data-title="{{ __('app.max_profit') }}" class="right aligned">{!! $participant->_max_profit ?: '&mdash;' !!}</td>
                        <td data-title="{{ __('app.total_volume') }}" class="right aligned">{!! $participant->_total_volume ?: '&mdash;' !!}</td>
                        <td data-title="{{ __('app.total_pnl') }}" class="{{ $participant->data->pnl > 0 ? 'positive' : ($participant->data->pnl < 0 ? 'negative' : '') }} right aligned">
                            {{ $participant->data->_pnl }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="right aligned column">
            {{ $leaderboard->links() }}
        </div>
    </div>
@endsection
