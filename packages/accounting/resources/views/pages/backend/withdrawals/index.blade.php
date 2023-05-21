@extends('layouts.backend')

@section('title')
    {{ __('accounting::text.withdrawals') }}
@endsection

@section('content')
    <div class="ui one column tablet stackable grid container">
        <div class="column">
            @if($withdrawals->isEmpty())
                <div class="ui segment">
                    <p>{{ __('accounting::text.withdrawals_empty2') }}</p>
                </div>
            @else
                <table class="ui selectable tablet stackable {{ $inverted }} table">
                    <thead>
                    <tr>
                        @component('components.tables.sortable-column', ['id' => 'user', 'sort' => $sort, 'order' => $order])
                            {{ __('accounting::text.user') }}
                        @endcomponent
                        @component('components.tables.sortable-column', ['id' => 'account', 'sort' => $sort, 'order' => $order])
                            {{ __('accounting::text.account') }}
                        @endcomponent
                        @component('components.tables.sortable-column', ['id' => 'withdrawal_method', 'sort' => $sort, 'order' => $order])
                            {{ __('accounting::text.withdrawal_method') }}
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
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($withdrawals as $withdrawal)
                        <tr>
                            <td data-title="{{ __('accounting::text.user') }}">
                                <a href="{{ route('backend.users.edit', [$withdrawal->user_id]) }}">
                                    {{ $withdrawal->user_name }}
                                </a>
                            </td>
                            <td data-title="{{ __('accounting::text.account') }}">{{ $withdrawal->account_code }}</td>
                            <td data-title="{{ __('accounting::text.withdrawal_method') }}">
                                {{ __('accounting::text.withdrawal_method_' . $withdrawal->withdrawal_method_id) }}
                                @if(!empty($withdrawal->payment_details))
                                    <span data-tooltip="{{ implode(', ', array_values($withdrawal->payment_details)) }}">
                                        <i class="info tooltip icon"></i>
                                    </span>
                                @endif
                            </td>
                            <td data-title="{{ __('accounting::text.status') }}" class="{{ $withdrawal->status == Packages\Accounting\Models\Withdrawal::STATUS_COMPLETED ? 'positive' : (in_array($withdrawal->status, [Packages\Accounting\Models\Withdrawal::STATUS_REJECTED, Packages\Accounting\Models\Withdrawal::STATUS_CANCELLED]) ? 'negative' : '') }}">{{ __('accounting::text.withdrawal_status_' . $withdrawal->status) }}</td>
                            <td data-title="{{ __('accounting::text.amount') }}" class="right aligned">
                                {{ $withdrawal->_amount }} {{ $withdrawal->account_currency_code }}
                            </td>
                            <td data-title="{{ __('accounting::text.created') }}" class="right aligned">
                                {{ $withdrawal->created_at->diffForHumans() }}
                                <span data-tooltip="{{ $withdrawal->created_at }}">
                                    <i class="calendar outline tooltip icon"></i>
                                </span>
                            </td>
                            <td data-title="{{ __('accounting::text.updated') }}" class="right aligned">
                                {{ $withdrawal->updated_at->diffForHumans() }}
                                <span data-tooltip="{{ $withdrawal->updated_at }}">
                                    <i class="calendar outline tooltip icon"></i>
                                </span>
                            </td>
                            <td class="right aligned tablet-and-below-center">
                                <a class="ui icon {{ $settings->color }} basic button" href="{{ route('backend.withdrawals.edit', $withdrawal) }}">
                                    <i class="edit icon"></i>
                                    {{ __('accounting::text.edit') }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        <div class="right aligned column">
            {{ $withdrawals->appends(['sort' => $sort])->appends(['order' => $order])->links() }}
        </div>
    </div>
@endsection