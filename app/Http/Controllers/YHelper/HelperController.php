<?php

namespace App\Http\Controllers\YHelper;
use App\Elibs\Debug;
use App\Elibs\eView;
use App\Elibs\Helper;
use App\Elibs\HtmlHelper;
use App\Http\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class HelperController extends Controller
{
    public function index($action = '')
    {
        $action = str_replace('-', '_', $action);

        if (method_exists($this, $action)) {
            return $this->$action();
        } else {
            return $this->helper();
        }
    }

    public function helper(){
        HtmlHelper::getInstance()->setTitle('TRợ giúp');
        return eView::getInstance()->setView(__DIR__, 'helper', []);
    }

    public function policy(){
        HtmlHelper::getInstance()->setTitle('Chính sách bảo mật');
        return eView::getInstance()->setView(__DIR__, 'policy', []);
    }

    public function condition(){
        HtmlHelper::getInstance()->setTitle('Điều khoản điều kiện');
        return eView::getInstance()->setView(__DIR__, 'condition', []);
    }
}
