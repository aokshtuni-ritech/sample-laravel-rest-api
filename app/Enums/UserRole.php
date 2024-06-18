<?php

namespace App\Enums;

use App\Enums\Traits\Values;

enum UserRole: string {

    use Values;

    case ADMIN = 'ADMIN';
    case USER = 'USER';
}
