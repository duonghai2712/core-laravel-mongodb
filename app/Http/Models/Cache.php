<?php

namespace App\Http\Models;

use App\Elibs\Debug;
use App\Elibs\eBug;
use App\Elibs\eView;
use App\Elibs\Helper;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\Mime\Encoder\Base64Encoder;

class Cache extends BaseModel
{
    //
    const table_name = 'y_cache';

    static $permision = [];

    protected $table = self::table_name;

    protected $fillable = [];
    static $unguarded = TRUE;
}
