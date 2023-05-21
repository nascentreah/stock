<?php

namespace App\Models\Fields;

interface EnumUserRole {
    const ROLE_USER     = 'USER';
    const ROLE_ADMIN    = 'ADMIN';
    const ROLE_BOT      = 'BOT';
}