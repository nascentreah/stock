<?php

namespace App\Http\Requests\Backend;

use App\Models\Asset;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateAsset extends FormRequest
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
            'symbol'        => 'required|string|max:30|unique:assets,symbol,' . $this->asset->id . ',id,market_id,' . $this->market, // ensure (symbol, market_id) pair is unique
            'symbol_ext'    => 'required|string|max:30|unique:assets,symbol_ext,' . $this->asset->id . ',id', // don't validate symbol if it's not updated
            'name'          => 'required|string|max:150',
            'logo'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price'         => 'required|numeric|min:0|max:999999999999.99999999',
            'change_abs'    => 'required|numeric|min:-999999999999.99999999|max:999999999999.99999999',
            'change_pct'    => 'required|numeric|min:-9999999999.99|max:9999999999.99',
            'volume'        => 'required|numeric|min:0|max:9223372036854775807', // bigint
            'supply'        => 'required|numeric|min:0|max:9223372036854775807', // bigint
            'market_cap'    => 'required|numeric|min:0|max:9223372036854775807', // bigint
            'status' => [
                'required',
                Rule::in(Asset::getStatuses()),
            ],
        ];
    }
}