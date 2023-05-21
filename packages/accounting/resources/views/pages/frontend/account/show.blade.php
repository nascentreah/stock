@extends('layouts.frontend')

@section('title')
    {{ __('accounting::text.account') }}
@endsection

@section('content')
    <div class="ui one column tablet stackable grid container">
        <div class="column">
            <table class="ui selectable tablet stackable {{ $inverted }} table">
                <thead>
                    <tr>
                        <th>{{ __('accounting::text.code') }}</th>
                        <th>{{ __('accounting::text.status') }}</th>
                        <th class="right aligned">{{ __('accounting::text.balance') }}, {{ $account->currency->code }}</th>
                        <th class="right aligned">{{ __('accounting::text.created') }}</th>
                        <th class="right aligned">{{ __('accounting::text.updated') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-title="{{ __('accounting::text.code') }}">{{ $account->code }}</td>
                        <td data-title="{{ __('accounting::text.status') }}"><i class="{{ $account->status == Packages\Accounting\Models\Account::STATUS_ACTIVE ? 'check green' : 'red ban' }} large icon"></i> {{ __('accounting::text.status_' . $account->status)  }}</td>
                        <td data-title="{{ __('accounting::text.balance') }}" class="right aligned">{{ $account->_balance }}</td>
                        <td data-title="{{ __('accounting::text.created') }}" class="right aligned">
                            {{ $account->created_at->diffForHumans() }}
                            <span data-tooltip="{{ $account->created_at }}">
                                <i class="calendar outline tooltip icon"></i>
                            </span>
                        </td>
                        <td data-title="{{ __('accounting::text.updated') }}" class="right aligned">
                            {{ $account->updated_at->diffForHumans() }}
                            <span data-tooltip="{{ $account->updated_at }}">
                                <i class="calendar outline tooltip icon"></i>
                            </span>
                        </td>
                        <td class="right aligned mobile-center">
                            @if(!$payment_methods->isEmpty())
                                <div class="ui small compact {{ $inverted }} menu">
                                    <div class="ui dropdown item">
                                        {{ __('accounting::text.deposit') }}
                                        <i class="dropdown icon"></i>
                                        <div class="menu">
                                            @foreach($payment_methods as $payment_method)
                                                <a href="{{ route('frontend.deposits.create', [Auth::user(), $payment_method]) }}" class="item">{{ __('accounting::text.method_' . $payment_method->id) }}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(!$withdrawal_methods->isEmpty())
                                <div class="ui small compact {{ $inverted }} menu">
                                    <div class="ui dropdown item">
                                        {{ __('accounting::text.withdraw') }}
                                        <i class="dropdown icon"></i>
                                        <div class="menu">
                                            @foreach($withdrawal_methods as $withdrawal_method)
                                                <a href="{{ route('frontend.withdrawals.create', [Auth::user(), $withdrawal_method]) }}" class="item">{{ __('accounting::text.withdrawal_method_' . $withdrawal_method->id) }}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        @if(!$transactions->isEmpty())
            <div class="column">
                <h2 class="ui header">{{ __('accounting::text.transactions') }}</h2>
                <table class="ui selectable tablet stackable {{ $inverted }} table">
                    <thead>
                    <tr>
                        <th>{{ __('accounting::text.type') }}</th>
                        <th>{{ __('accounting::text.reference') }}</th>
                        <th class="right aligned">{{ __('accounting::text.amount') }}, {{ $account->currency->code }}</th>
                        <th class="right aligned">{{ __('accounting::text.running_balance') }}, {{ $account->currency->code }}</th>
                        <th class="right aligned">{{ __('accounting::text.created') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td data-title="{{ __('accounting::text.type') }}">{{ __('accounting::text.transaction_type_' . $transaction->type) }}</td>
                            <td data-title="{{ __('accounting::text.reference') }}">
                                {{ $transaction->transactionable ? $transaction->transactionable->title : '' }}
                            </td>
                            <td data-title="{{ __('accounting::text.amount') }}" class="right aligned">
                                {{ $transaction->_amount }}
                            </td>
                            <td data-title="{{ __('accounting::text.running_balance') }}" class="right aligned">
                                <i class="long arrow alternate {{ $transaction->amount > 0 ? 'green up' : 'red down' }} icon"></i>
                                {{ $transaction->_balance }}
                            </td>
                            <td data-title="{{ __('accounting::text.created') }}" class="right aligned">
                                {{ $transaction->created_at->diffForHumans() }}
                                <span data-tooltip="{{ $transaction->created_at }}">
                                    <i class="calendar outline tooltip icon"></i>
                                </span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="right aligned column">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
@endsection