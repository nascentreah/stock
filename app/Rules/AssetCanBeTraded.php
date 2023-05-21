<?php

namespace App\Rules;

use App\Models\Asset;
use App\Models\Competition;
use Illuminate\Contracts\Validation\Rule;

class AssetCanBeTraded implements Rule
{
    private $competition;
    private $asset;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Competition $competition, Asset $asset)
    {
        $this->competition = $competition;
        $this->asset = $asset;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // get allowed assets for current competition
        $allowedAssets = $this->competition->assetsIds();

        return $this->asset->status == Asset::STATUS_ACTIVE && $this->asset->price > 0 &&
            // there are no assets limitations or asset is allowed to be traded
            ($allowedAssets->count() == 0 || in_array($this->asset->id, $allowedAssets->toArray()));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('app.asset_not_tradeable');
    }
}
