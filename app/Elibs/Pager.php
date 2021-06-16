<?php
/**
 * Created by PhpStorm.
 * Member: ngannv
 * Date: 9/13/15
 * Time: 12:11 AM
 */

namespace App\Elibs;


use Illuminate\Pagination\LengthAwarePaginator;

class Pager
{
    static private $instance = false;

    const PAGER_FULL_PAGE = 2;

    static $disableLink = false;

    public function __construct()
    {
        self::$instance = &$this;
    }

    public static function &getInstance()
    {
        if (!self::$instance) {
            new self();
        }

        return self::$instance;
    }

    function getPager($object, $item_per_page = 25, $request = 'GET', $type = self::PAGER_FULL_PAGE)
    {
        if ($type == self::PAGER_FULL_PAGE) {
            $object = $object->paginate($item_per_page);
        } else {
            $object = $object->simplePaginate($item_per_page);
        }
        $param_link = false;
        if (is_array($request)) {
            $param_link = $request;
        }
        switch (strtolower($request)) {
            case 'get': {
                $param_link = $_GET;
                break;
            }
            case 'post': {
                $param_link = $_POST;
                break;
            }
            case 'all': {
                $param_link = array_merge($_POST, $_GET);
                break;
            }
        }
        if ($param_link) {
            $object->appends($param_link);
        }
        if(!self::$disableLink) {
            $link = $object->render();
        }

        return $object;
    }



}
