<?php

namespace App\Models;

use App\Models\Fields\Enum;
use App\Models\Fields\EnumAssetStatus;
use App\Models\Formatters\Formatter;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model implements EnumAssetStatus
{
    use Enum, Formatter;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = ['status','created_at','updated_at'];

    /**
     * The accessors to append to the model's array form (selected fields will be automatically added to JSON).
     *
     * @var array
     */
    protected $appends = ['logo_url', 'title'];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'price'             => 'float',
        'change_abs'        => 'float',
        'change_pct'        => 'float',
        'supply'            => 'float', // should be float to properly handle bigint datatype
        'volume'            => 'float', // should be float to properly handle bigint datatype
        'market_cap'        => 'float', // should be float to properly handle bigint datatype
    ];

    protected $formats = [
        'price'             => 'variableDecimal',
        'change_abs'        => 'variableDecimal',
        'change_pct'        => 'decimal',
        'supply'            => 'integer',
        'volume'            => 'integer',
        'market_cap'        => 'integer',
        'trades_count'      => 'integer',
    ];

    /**
     * Accessor for asset logo URL
     *
     * @return string
     */
    public function getLogoUrlAttribute()
    {
        $logo = asset('images/asset.png'); // default logo

        if ($this->logo) {
            $logo = asset('storage/assets/' . $this->logo);
        }/* elseif (is_file(public_path('storage/assets/' . $this->symbol . '.png'))) {
            $logo = asset('storage/assets/' . $this->symbol . '.png');
        } elseif (is_file(public_path('storage/assets/' . $this->symbol . '.jpg'))) {
            $logo = asset('storage/assets/' . $this->symbol . '.jpg');
        }*/

        return $logo;
    }

    /**
     * Accessor for title property
     * Title attribute is required so correct result value is passed to onSelect() callback (Semantic UI search)
     *
     * @return string
     */
    public function getTitleAttribute()
    {
        return $this->symbol_ext;
    }

    /**
     * Get asset statuses
     *
     * @return array
     */
    public static function getStatuses()
    {
        return self::getEnumValues('AssetStatus');
    }

    public function trades()
    {
        return $this->hasMany(Trade::class);
    }

    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Allowed assets many-to-many relationship (through competition_assets pivot table)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function competitions()
    {
        return $this->belongsToMany(Competition::class, 'competition_assets');
    }
}
