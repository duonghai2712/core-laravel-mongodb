<?php

namespace App\Http\Models;

use App\Elibs\Debug;
use App\Elibs\eCache;
use App\Elibs\Helper;
use Illuminate\Support\Facades\DB;

class LogLogin extends BaseModel
{
    public $timestamps = false;
    const table_name = 'y_log_login';
    protected $table = self::table_name;
    static $unguarded = true;

}
