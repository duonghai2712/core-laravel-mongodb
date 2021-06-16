<?php
/**
 * Created by PhpStorm.
 * Member: ngannv
 * Date: 8/18/15
 * Time: 8:02 PM
 * {!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('public/ui/raovat.vn/js/cookie.js') !!}
 */

namespace App\Elibs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Jenssegers\Agent\Agent;

class eView
{
    static private $instance = false;
    static public $viewVar = [];


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

    public function setVar($var, $value)
    {
        self::$viewVar[$var] = $value;
    }

    public static function setView($dir, $template, $var = [], $render = false)
    {
        if ($dir) {
            View::addLocation($dir);
            $localtion = '/views/';
        } else {
            $localtion = '';
        }
        if (self::$viewVar) {
            $var = array_merge($var, self::$viewVar);
        }

        $var['HtmlHelper']['Seo'] = HtmlHelper::getInstance()->getSeoMeta();
        if (!isset($var['THEME_EXTEND'])) {
            $var['THEME_EXTEND'] = 'backend';
        }

        $var['agent'] = new Agent();
        $view = view($localtion . $template, $var);
        if ($render) {
            return $view->render();
        }
        return $view;
    }

    public function setViewBase($dir, $template, $var = [], $render = false)
    {
        if ($dir) {
            View::addLocation($dir);
            $localtion = '/views/';
        } else {
            $localtion = '';
        }
        if (self::$viewVar) {
            $var = array_merge($var, self::$viewVar);
        }
        $var['agent'] = new Agent();
        $view = view($localtion . $template, $var);
        if ($render) {
            return $view->render();
        }
        return $view;
    }

    public function getView($dir, $template, $var = [], $render = true)
    {
        return self::setView($dir, $template, $var, $render);
    }

    public function getBlank()
    {
        return self::setView('', 'blank', [], false);
    }

    public static function setView404($var = [], $template = '', $render = false)
    {
        if (self::$viewVar) {
            $var = array_merge($var, self::$viewVar);
        }
        if(!isset($var['description']) || !$var['description']){
            $var['description'] = 'Xin lỗi, trang bạn đang tìm kiếm không tồn tại, hoặc có thể đã bị xóa hoặc thay đổi.';
        }
        if (!$template) {
            $template = 'errors.404';
        }
        return response()->view($template, $var, 404);
    }

    public function showJson($json)
    {
        if (config('app.debug')) {
            $json['debug']['sql'] = DB::getQueryLog();
            $json['debug']['post'] = $_POST;
            $json['debug']['get'] = $_GET;
            $json['debug']['cookie'] = $_COOKIE;
            $json['debug']['raw'] = file_get_contents('php://input');
            $json['debug']['ip'] = [
              'SERVER_ADDR'=>  @$_SERVER['SERVER_ADDR'],
               'REMOTE_ADDR'=>  @$_SERVER['REMOTE_ADDR'],

            ] ;


        }
        app('debugbar')->disable();

        //tracking end over here
        header('Content-Type: application/json');

        die(json_encode($json));
    }

    public function getJsonError($msg, $keyerror = '', $data = [], $status = 0)
    {
        $json['status'] = $status;
        $json['msg'] = $msg;
        $json['key'] = $keyerror;
        $json['data'] = $data;

        return $this->showJson($json);
    }

    public function getJsonSuccess($msg, $data = [])
    {
        $json['status'] = 1;
        $json['msg'] = $msg;
        $json['data'] = $data;



        return $this->showJson($json);
    }

    private function _setMsg($content, $type = 'info')
    {
        $msg = '<div class="alert alert-' . $type . ' alert-styled-left alert-bordered">
                        ' . $content . '
                    </div>';
        self::$viewVar['_MSG'] = $msg;
    }

    public function setMsgError($content, $type = 'danger', $session = false)
    {
        $this->_setMsg($content, $type);
    }

    public function setMsgInfo($content, $type = 'info')
    {
        $this->_setMsg($content, $type);
    }

    public function setMsgWarning($content, $type = 'warning')
    {
        $this->_setMsg($content, $type);
    }

    public function setHtmlError($content, $type = 'danger')
    {
        if (config('app.debug')) {
            $json['debug']['sql'] = DB::getQueryLog();
            $json['debug']['post'] = $_POST;
            $json['debug']['get'] = $_GET;
            $json['debug']['cookie'] = $_COOKIE;
            $json['debug']['raw'] = file_get_contents('php://input');
        }
        app('debugbar')->disable();
        //tracking end over here
        $msg = '<div style="background:#fff;height: 100%;justify-content: center;align-items: center;display: flex;font-family: Arial, Helvetica, sans-serif">
                        <span>' . $content . '</span>
                    </div>';
        die($msg);
        // return response()->json($json);
    }

    public function cannotAccess($var = [], $template = '', $render = false)
    {
        if (self::$viewVar) {
            $var = array_merge($var, self::$viewVar);
        }

        if (request()->isMethod('get')) {
            if (!$template) {
                $template = 'errors.401';
            }
            return response()->view($template, $var, 401);
        } else {
            return response()->json([
                'status' => 0,
                'msg' => @$var['msg']? :"Bạn không có quyền này"
            ], 401);
        }

    }

    public function limitAccess($var = [], $template = '', $render = false)
    {
        if (self::$viewVar) {
            $var = array_merge($var, self::$viewVar);
        }

        if (request()->isMethod('get')) {
            if (!$template) {
                $template = 'errors.401_limit';
            }
            return response()->view($template, $var, 401);
        } else {
            return response()->json([
                'status' => 0,
                'msg' => @$var['msg']? :"Bạn không có quyền này"
            ], 401);
        }

    }
}
