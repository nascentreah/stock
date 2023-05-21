@extends('layouts.backend')

@section('title')
    {{ __('accounting::text.withdrawal') }} {{ $withdrawal->id }} ({{ __('accounting::text.withdrawal_method_' . $withdrawal->withdrawal_method_id) }}) :: {{ __('accounting::text.edit') }}
@endsection

@section('content')
        <div class="ui one column stackable grid container">
            <div class="column">
                <div class="ui {{ $inverted }} segment">
                    <form class="ui {{ $inverted }} form" method="POST" action="{{ route('backend.withdrawals.update', $withdrawal) }}">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="field {{ $errors->has('amount') ? 'error' : '' }}">
                            <label>{{ __('accounting::text.amount') }}</label>
                            <div class="ui right labeled input">
                                <input type="number" name="amount" placeholder="{{ __('accounting::text.amount') }}" value="{{ old('amount', $withdrawal->amount) }}" required autofocus {{ $editable ? '' : 'disabled' }}>
                                <div class="ui basic label">
                                    {{ $withdrawal->account->currency->code }}
                                </div>
                            </div>
                        </div>
                        @foreach($withdrawal->payment_details as $code => $detail)
                            <div class="field">
                                <label>{{ __('accounting::text.' . $code) }}</label>
                                <div class="ui right labeled input">
                                    <input type="text" value="{{ $detail }}" disabled>
                                </div>
                            </div>
                        @endforeach
                        <div class="field {{ $errors->has('status') ? 'error' : '' }}">
                            <label>{{ __('accounting::text.status') }}</label>
                            <div id="withdrawal-status-dropdown" class="ui selection {{ $editable ? '' : 'disabled' }} dropdown">
                                <input type="hidden" name="status">
                                <i class="dropdown icon"></i>
                                <div class="default text"></div>
                                <div class="menu">
                                    @if($editable)
                                        <div class="item" data-value="{{ \Packages\Accounting\Models\Withdrawal::STATUS_CREATED }}">{{ __('accounting::text.withdrawal_status_'.\Packages\Accounting\Models\Withdrawal::STATUS_CREATED) }}</div>
                                        <div class="item" data-value="{{ \Packages\Accounting\Models\Withdrawal::STATUS_IN_PROGRESS }}">{{ __('accounting::text.withdrawal_status_'.\Packages\Accounting\Models\Withdrawal::STATUS_IN_PROGRESS) }}</div>
                                        <div class="item" data-value="{{ \Packages\Accounting\Models\Withdrawal::STATUS_COMPLETED }}">{{ __('accounting::text.withdrawal_status_'.\Packages\Accounting\Models\Withdrawal::STATUS_COMPLETED) }}</div>
                                        <div class="item" data-value="{{ \Packages\Accounting\Models\Withdrawal::STATUS_REJECTED }}">{{ __('accounting::text.withdrawal_status_'.\Packages\Accounting\Models\Withdrawal::STATUS_REJECTED) }}</div>
                                        <div class="item" data-value="{{ \Packages\Accounting\Models\Withdrawal::STATUS_CANCELLED }}">{{ __('accounting::text.withdrawal_status_'.\Packages\Accounting\Models\Withdrawal::STATUS_CANCELLED) }}</div>
                                    @else
                                        <div class="item" data-value="{{ $withdrawal->status }}">{{ __('accounting::text.withdrawal_status_'.$withdrawal->status) }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="field {{ $errors->has('comments') ? 'error' : '' }}">
                            <label>{{ __('accounting::text.comments') }}</label>
                            <textarea rows="5" name="comments" placeholder="{{ __('accounting::text.comments') }}" autofocus {{ $editable ? '' : 'disabled' }}>{{ old('details.comments', $withdrawal->comments) }}</textarea>
                        </div>
                        <div class="field">
                            <label>{{ __('accounting::text.created') }}</label>
                            <div class="ui input">
                                <input value="{{ $withdrawal->created_at }} ({{ $withdrawal->created_at->diffForHumans() }})" disabled>
                            </div>
                        </div>
                        <div class="field">
                            <label>{{ __('accounting::text.created_by') }}</label>
                            <div class="ui input">
                                <input value="{{ $withdrawal->account->user->name }}" disabled>
                            </div>
                        </div>
                        <div class="field">
                            <label>{{ __('accounting::text.updated') }}</label>
                            <div class="ui input">
                                <input value="{{ $withdrawal->updated_at }} ({{ $withdrawal->updated_at->diffForHumans() }})" disabled>
                            </div>
                        </div>
                        <button class="ui large {{ $settings->color }} submit {{ $editable ? '' : 'disabled' }} button">
                            <i class="save icon"></i>
                            {{ __('accounting::text.save') }}
                        </button>
                    </form>
                </div>
            </div>
            <div class="column">
                <a href="{{ route('backend.withdrawals.index') }}"><i class="left arrow icon"></i> {{ __('accounting::text.back_all_withdrawals') }}</a>
            </div>
        </div>
@endsection

@push('scripts')
    <script>
        $('#withdrawal-status-dropdown').dropdown('set selected', '{{ old('status', $withdrawal->status) }}');
    </script>
@endpush