<?php

namespace App\Http\Middleware;

use App\Elibs\Debug;
use App\Elibs\Helper;
use App\Http\Models\Member;
use Closure;
use Illuminate\Support\Facades\Route;

class YUserInit
{
    public function handle($request, Closure $next, $guard = NULL)
    {
        return $next($request);
    }
}
