@extends('layouts.backend')

@section('title')
    {{ __('accounting::text.accounts') }}
@endsection

@section('content')
    <div class="ui one column tablet stackable grid container">
        <div class="column">
            @if($accounts->isEmpty())
                <div class="ui message">{{ __('accounting::text.accounts_empty2') }}</div>
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
                            @component('components.tables.sortable-column', ['id' => 'status', 'sort' => $sort, 'order' => $order])
                            {{ __('accounting::text.status') }}
                            @endcomponent
                            @component('components.tables.sortable-column', ['id' => 'balance', 'sort' => $sort, 'order' => $order, 'class' => 'right aligned'])
                            {{ __('accounting::text.balance') }}
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
                        @foreach($accounts as $account)
                            <tr>
                                <td data-title="{{ __('accounting::text.user') }}">
                                    <a href="{{ route('backend.users.edit', [$account->user_id]) }}">
                                        {{ $account->user_name }}
                                    </a>
                                </td>
                                <td>{{ $account->code }}</td>
                                <td><i class="{{ $account->status == Packages\Accounting\Models\Account::STATUS_ACTIVE ? 'check green' : 'red ban' }} large icon"></i> {{ __('accounting::text.status_' . $account->status)  }}</td>
                                <td class="right aligned">{{ $account->_balance }} {{ $account->currency_code }}</td>
                                <td class="right aligned">
                                    {{ $account->created_at->diffForHumans() }}
                                    <span data-tooltip="{{ $account->created_at }}">
                                        <i class="calendar outline tooltip icon"></i>
                                    </span>
                                </td>
                                <td class="right aligned">
                                    {{ $account->updated_at->diffForHumans() }}
                                    <span data-tooltip="{{ $account->updated_at }}">
                                        <i class="calendar outline tooltip icon"></i>
                                    </span>
                                </td>
                                <td data-title="{{ __('accounting::text.user') }}">
                                    <a href="javascript:;">
                                        Fund balance
                                    </a>
                                </td>
                                <!--{{ route('backend.users.edit', [$account->user_id]) }}-->
                                 <tr>
                                
                            </tr>
                        @endforeach
                    </tbody>
            </table>
            @endif
        </div>
        <div class="right aligned column">
            {{ $accounts->appends(['sort' => $sort])->appends(['order' => $order])->links() }}
        </div>
    </div>
@endsection