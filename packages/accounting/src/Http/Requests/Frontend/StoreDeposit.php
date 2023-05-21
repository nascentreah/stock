<?php

namespace Packages\Accounting\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Packages\Accounting\Services\PaymentMethodService;

class StoreDeposit extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $paymentMethod = $request->route('payment_method');
        $paymentMethodService = new PaymentMethodService($paymentMethod);
        $paymentMethodCurrencies = $paymentMethodService->acceptedCurrencies();

        return [
            'amount'    => 'required|numeric|min:1|max:999999999',
            'currency'  => 'required|in:' . $paymentMethodCurrencies->implode(','),
            'country'   => 'sometimes|required|in:AT,BE,DE,NL,ES,IT', // Stripe SOFORT allowed countries
        ];
    }
}
