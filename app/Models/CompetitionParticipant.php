<?php

namespace App\Models;

use App\Models\Formatters\Formatter;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CompetitionParticipant extends Pivot
{
    use Formatter;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id', 'competition_id', 'user_id', 'created_at', 'updated_at'
    ];

    /**
     * The accessors to append to the model's array form (selected fields will be automatically added to JSON).
     *
     * @var array
     */
    protected $appends = ['pnl'];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'start_balance'     => 'float',
        'current_balance'   => 'float',
    ];

    protected $formats = [
        'start_balance'     => 'decimal',
        'current_balance'   => 'decimal',
        'pnl'               => 'decimal',
    ];

    public function getPnlAttribute() {
        return $this->current_balance - $this->start_balance;
    }
}