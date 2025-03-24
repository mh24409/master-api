<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    use ApiResponses;
    public function include(String $relationship): bool
    {
        $params = request()->get('include');
        if (!isset($params)) {
            return false;
        }
        $includeValues = explode(',', strtolower($params));
        return in_array(strtolower($relationship), $includeValues);
    }
}
