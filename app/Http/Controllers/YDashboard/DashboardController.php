<?php

namespace App\Http\Controllers\YDashboard;
use App\Elibs\Debug;
use App\Elibs\eView;
use App\Elibs\Helper;
use App\Elibs\HtmlHelper;
use App\Http\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function index($action = '')
    {
        $action = str_replace('-', '_', $action);
        if (method_exists($this, $action)) {
            return $this->$action();
        } else {
            return $this->dashboard();
        }

    }

    public function dashboard(){
        HtmlHelper::getInstance()->setTitle('Hệ thống bản trị');
        $tpl = [];
        $tpl['member'] = Member::$currentMember;
        return eView::getInstance()->setView(__DIR__, 'dashboard', $tpl);
    }

}
