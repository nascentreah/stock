<?php

namespace Packages\Accounting\Http\Requests\Backend;

use Packages\Accounting\Rules\WithdrawalIsEditable;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWithdrawal extends FormRequest
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
    public function rules()
    {
        return [
            'amount'                => 'required|numeric|min:1|max:999999999',
            'details.email'         => 'sometimes|required|email',
            'details.name'          => 'sometimes|required',
            'details.bank_iban'     => 'sometimes|required',
            'details.bank_swift'    => 'sometimes|required',
            'details.bank_name'     => 'sometimes|required',
            'details.bank_branch'   => 'sometimes|required',
            '*'                     => new WithdrawalIsEditable($this->withdrawal)
        ];
    }
}
