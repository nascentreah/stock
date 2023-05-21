<a href="{{ route('frontend.account.show', [Auth::user()]) }}" class="item">
    <i class="list alternate outline icon"></i>
    {{ __('accounting::text.account') }}
</a>
<a href="{{ route('frontend.deposits.index', [Auth::user()]) }}" class="item">
    <i class="arrow alternate circle down outline icon"></i>
    {{ __('accounting::text.deposits') }}
</a>
<a href="{{ route('frontend.withdrawals.index', [Auth::user()]) }}" class="item">
    <i class="arrow alternate circle up outline icon"></i>
    {{ __('accounting::text.withdrawals') }}
</a>