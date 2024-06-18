<?php

namespace App\Enums;

use App\Enums\Traits\Values;

enum RequestType: string {

    use Values;

    case POST = 'POST';
    case PUT = 'PUT';
    case DELETE = 'DELETE';
}
