<?php

namespace Packages\Accounting\ViewComposers\Backend;

use Packages\Accounting\Models\PaymentMethod;
use Packages\Accounting\Models\WithdrawalMethod;
use Illuminate\View\View;

class SettingComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // get variables passed to the main app view
        $variables = $view->getData();

        $variables['deposit_methods'] = PaymentMethod::get();
        $variables['withdrawal_methods'] = WithdrawalMethod::get();

        $variables['enabled_deposit_methods'] =
            array_column(
                array_filter($variables['deposit_methods']->toArray(), function($method) {
                    return $method['status'] == PaymentMethod::STATUS_ACTIVE;
                }),
            'code');

        $variables['enabled_withdrawal_methods'] =
            array_column(
                array_filter($variables['withdrawal_methods']->toArray(), function($method) {
                    return $method['status'] == WithdrawalMethod::STATUS_ACTIVE;
                }),
            'code');

        if (!config('settings.openexchangerates_api_key')) {
            request()->session()->flash('warning', __('accounting::text.openexchangerates_api_key_missing', ['url' => 'https://openexchangerates.org/signup/free']));
        }

        $view->with($variables);
    }
}