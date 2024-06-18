<?php

namespace App\Enums;

use App\Enums\Traits\Values;

enum EntityType: string {

    use Values;

    case EMPLOYEE = 'EMPLOYEE';
}
