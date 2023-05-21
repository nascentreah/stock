<div class="row">
    <div class="center aligned column">
        <div class="ui {{ $inverted }} segment">
            <div class="ui {{ $inverted }} statistic">
                <div class="value">
                    {{ $accounts_count }}
                </div>
                <div class="label">
                    {{ __('accounting::text.accounts_count') }}
                </div>
            </div>
        </div>
    </div>
    <div class="center aligned column">
        <div class="ui {{ $inverted }} segment">
            <div class="ui {{ $inverted }} green statistic">
                <div class="value">
                    {{ $positive_balance_accounts_count }}
                </div>
                <div class="label">
                    {{ __('accounting::text.positive_balance_accounts_count') }}
                </div>
            </div>
        </div>
    </div>
    <div class="center aligned column">
        <div class="ui {{ $inverted }} segment">
            <div class="ui {{ $inverted }} red statistic">
                <div class="value">
                    {{ $zero_balance_accounts_count }}
                </div>
                <div class="label">
                    {{ __('accounting::text.zero_balance_accounts_count') }}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="center aligned column">
        <div class="ui {{ $inverted }} segment">
            <div class="ui {{ $inverted }} statistic">
                <div class="value">
                    {{ $deposits_count }}
                </div>
                <div class="label">
                    {{ __('accounting::text.deposits_count') }}
                </div>
            </div>
        </div>
    </div>
    <div class="center aligned column">
        <div class="ui {{ $inverted }} segment">
            <div class="ui {{ $inverted }} green statistic">
                <div class="value">
                    {{ $completed_deposits_count }}
                </div>
                <div class="label">
                    {{ __('accounting::text.completed_deposits_count') }}
                </div>
            </div>
        </div>
    </div>
    <div class="center aligned column">
        <div class="ui {{ $inverted }} segment">
            <div class="ui {{ $inverted }} red statistic">
                <div class="value">
                    {{ $pending_deposits_count }}
                </div>
                <div class="label">
                    {{ __('accounting::text.pending_deposits_count') }}
                </div>
            </div>
        </div>
    </div>
</div>