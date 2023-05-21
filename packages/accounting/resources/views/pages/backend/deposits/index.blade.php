@extends('layouts.backend')

@section('title')
    {{ __('accounting::text.deposits') }}
@endsection

@section('content')
    <div class="ui one column tablet stackable grid container">
        <div class="column">
            @if($deposits->isEmpty())
                <div class="ui segment">
                    <p>{{ __('accounting::text.deposits_empty2') }}</p>
                </div>
            @else
                <table class="ui selectable tablet stackable {{ $inverted }} table">
                    <thead>
                    <tr>
                        @component('components.tables.sortable-column', ['id' => 'user', 'sort' => $sort, 'order' => $order])
                            {{ __('accounting::text.user') }}
                        @endcomponent
                        @component('components.tables.sortable-column', ['id' => 'payment_method', 'sort' => $sort, 'order' => $order])
                            {{ __('accounting::text.payment_method') }}
                        @endcomponent
                        @component('components.tables.sortable-column', ['id' => 'payment_id', 'sort' => $sort, 'order' => $order])
                        {{ __('accounting::text.payment_id') }}
                        @endcomponent
                        @component('components.tables.sortable-column', ['id' => 'status', 'sort' => $sort, 'order' => $order])
                        {{ __('accounting::text.status') }}
                        @endcomponent
                        @component('components.tables.sortable-column', ['id' => 'amount', 'sort' => $sort, 'order' => $order, 'class' => 'right aligned'])
                            {{ __('accounting::text.amount') }}
                        @endcomponent
                        @component('components.tables.sortable-column', ['id' => 'created', 'sort' => $sort, 'order' => $order, 'class' => 'right aligned'])
                            {{ __('accounting::text.created') }}
                        @endcomponent
                        @component('components.tables.sortable-column', ['id' => 'updated', 'sort' => $sort, 'order' => $order, 'class' => 'right aligned'])
                            {{ __('accounting::text.updated') }}
                        @endcomponent
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($deposits as $deposit)
                        <tr>
                            <td data-title="{{ __('accounting::text.user') }}">
                                <a href="{{ route('backend.users.edit', [$deposit->user_id]) }}">
                                    {{ $deposit->user_name }}
                                </a>
                            </td>
                            <td data-title="{{ __('accounting::text.payment_method') }}">{{ __('accounting::text.method_' . $deposit->payment_method_id) }}</td>
                            <td data-title="{{ __('accounting::text.payment_id') }}">{{ $deposit->external_id }}</td>
                            <td data-title="{{ __('accounting::text.status') }}" class="{{ $deposit->status == Packages\Accounting\Models\Deposit::STATUS_COMPLETED ? 'positive' : ($deposit->status == Packages\Accounting\Models\Deposit::STATUS_CANCELLED ? 'negative' : '') }}">{{ __('accounting::text.deposit_status_' . $deposit->status) }}</td>
                            <td data-title="{{ __('accounting::text.amount') }}" class="right aligned">
                                @if($deposit->account_currency_code != $deposit->payment_currency_code)
                                    <span data-tooltip="{{ __('accounting::text.deposit_amount_tooltip', ['amount' => $deposit->_payment_amount, 'ccy' => $deposit->payment_currency_code, 'ccy1' => $deposit->account_currency_code, 'ccy2' => $deposit->payment_currency_code, 'x' => $deposit->payment_fx_rate]) }}">
                                        <i class="calculator tooltip icon"></i>
                                    </span>
                                @endif
                                {{ $deposit->_amount }} {{ $deposit->account_currency_code }}
                            </td>
                            <td data-title="{{ __('accounting::text.created') }}" class="right aligned">
                                {{ $deposit->created_at->diffForHumans() }}
                                <span data-tooltip="{{ $deposit->created_at }}">
                                    <i class="calendar outline tooltip icon"></i>
                                </span>
                            </td>
                            <td data-title="{{ __('accounting::text.updated') }}" class="right aligned">
                                {{ $deposit->updated_at->diffForHumans() }}
                                <span data-tooltip="{{ $deposit->updated_at }}">
                                    <i class="calendar outline tooltip icon"></i>
                                </span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        <div class="right aligned column">
            {{ $deposits->appends(['sort' => $sort])->appends(['order' => $order])->links() }}
        </div>
    </div>
@endsection