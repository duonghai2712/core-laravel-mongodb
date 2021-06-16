<?php

namespace App\Http\Models;

//use Illuminate\Database\Eloquent\Model;
use App\Elibs\Debug;
use App\Elibs\Helper;
use Illuminate\Pagination\LengthAwarePaginator;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
class listObj extends LengthAwarePaginator
{
    function __construct($re,$total,$limit)
    {


    }
    function total()
    {
        //return0;
    }
    function count()
    {
       // return self::$total;
    }


}

class BaseModel extends Eloquent
{

    protected $table = '';

    protected $fillable = [];

    public $timestamps = FALSE;

    static function fetch($where,$get=[])
    {
        $re = self::where($where)->limit(1)->get($get);
        if($re && @$re[0])
        {
            return @$re[0]->toArray();
        }
        return [];

    }

}
