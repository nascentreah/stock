<a href="{{ route('backend.accounts.index') }}" class="item{{ strpos(Route::currentRouteName(),'backend.accounts.')!==FALSE ? ' active' : '' }}">
    {{ __('accounting::text.accounts') }}
    <i class="list alternate outline icon"></i>
</a>
<a href="{{ route('backend.deposits.index') }}" class="item{{ strpos(Route::currentRouteName(),'backend.deposits.')!==FALSE ? ' active' : '' }}">
    {{ __('accounting::text.deposits') }}
    <i class="arrow alternate circle down outline icon"></i>
</a>
<a href="{{ route('backend.withdrawals.index') }}" class="item{{ strpos(Route::currentRouteName(),'backend.withdrawals.')!==FALSE ? ' active' : '' }}">
    {{ __('accounting::text.withdrawals') }}
    <i class="arrow alternate circle up outline icon"></i>
</a>