<?php

namespace Packages\Accounting\Listeners;

use App\Events\BeforeSettingsSaved;
use Packages\Accounting\Models\PaymentMethod;
use Packages\Accounting\Models\WithdrawalMethod;

class AccountingEventsSubscriber
{
    public function beforeSettingsSaved(BeforeSettingsSaved $event) {
        $request = $event->request();

        // enable all deposit / withdrawal methods first
        PaymentMethod::query()->update(['status' => PaymentMethod::STATUS_ACTIVE]);
        WithdrawalMethod::query()->update(['status' => WithdrawalMethod::STATUS_ACTIVE]);

        // and then disable all not selected deposit / withdrawal methods
        PaymentMethod::whereNotIn('code', explode(',', $request->nonenv['deposit_methods']))
            ->update(['status' => PaymentMethod::STATUS_BLOCKED]);
        WithdrawalMethod::whereNotIn('code', explode(',', $request->nonenv['withdrawal_methods']))
            ->update(['status' => WithdrawalMethod::STATUS_BLOCKED]);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            BeforeSettingsSaved::class,
            'Packages\Accounting\Listeners\AccountingEventsSubscriber@beforeSettingsSaved'
        );
    }
}