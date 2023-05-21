<?php

namespace Packages\Accounting\Models;

use App\Models\User as ParentUser;

class User extends ParentUser
{
    public function account()
    {
        return $this->hasOne(Account::class);
    }
}