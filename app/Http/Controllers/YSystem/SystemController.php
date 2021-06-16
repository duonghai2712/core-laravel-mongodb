<?php

namespace App\Http\Controllers\YSystem;
use App\Elibs\Debug;
use App\Elibs\eView;
use App\Elibs\Helper;
use App\Elibs\HtmlHelper;
use App\Http\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class SystemController extends Controller
{
    public function index($action = '')
    {
        $action = str_replace('-', '_', $action);

        if($action == 'logout'){
            return $this->logout();
        }

        if(Member::isLogin()){
            return Redirect(access_link());
        }elseif(method_exists($this, $action)) {
            return $this->$action();
        } else {
            return $this->login();
        }
    }

    public function login()
    {
        HtmlHelper::getInstance()->setTitle('Đăng nhập hệ thống');
        return eView::getInstance()->setView(__DIR__, 'login', []);
    }

    public function logout()
    {
        Member::setLogOut();
        return Redirect::to(public_link(''));

    }

    public function registration()
    {
        HtmlHelper::getInstance()->setTitle('Đăng kí tài khoản');
        return eView::getInstance()->setView(__DIR__, 'registration', []);
    }

    public function reset_password()
    {
        HtmlHelper::getInstance()->setTitle('Quên mật khẩu');
        return eView::getInstance()->setView(__DIR__, 'reset-password', []);
    }

    public function access_login(){
        HtmlHelper::getInstance()->setTitle('Đăng nhập hệ thống');

        if (!Request::capture()->isMethod('POST')) {
            return eView::getInstance()->getJsonError('Truy cập của bạn trái phép.');
        }

        $validator = Validator::make(Request::capture()->all(), [
            'username' => 'required|string|regex:/^[a-zA-Z0-9-_]+/u|max:50',
            'password' => 'required|string|regex:/^[a-zA-Z0-9-_]+/u|max:50',
        ], [
            'username.required' => 'Bạn chưa nhập tên tài khoản',
            'username.string' => 'Tên tài khoản phải là một chuỗi',
            'username.regex' => 'Tên tài khoản không có kí tự đặc biệt',
            'username.max' => 'Tên tài khoản không quá 50 kí tự',
            'password.required' => 'Bạn chưa nhập mật khẩu',
            'password.string' => 'Mật khẩu phải là một chuỗi',
            'password.regex' => 'Mật khẩu không có kí tự đặc biệt',
            'password.max' => 'Mật khẩu không quá 50 kí tự',
        ]);
        if ($validator->fails()) {
            return eView::getInstance()->getJsonError($validator->errors()->first());
        }

        $username = strip_tags(Request::capture()->input('username'));
        $password = strip_tags(Request::capture()->input('password'));

        if(empty($username) || empty($password)){
            return eView::getInstance()->getJsonError('Sai tên tài khoản hoặc mật khẩu');
        }

        //Check tài khoản
        if (Helper::isEmail($username)) {
            $member = Member::getMemberByEmail($username);
        } elseif (Helper::isPhoneNumber($username)) {
            $member = Member::getMemberByPhone($username);
        } else {
            $member = Member::getMemberByAccount($username);
        }

        if (empty($member)) {
            return eView::getInstance()->getJsonError('Không tìm thấy tài khoản "' . $username . '" trong hệ thống');
        }

        $password = Member::encodePassword($password);
        if ($password == $member['password']) {
            Member::setLogin($member);
            return eView::getInstance()->getJsonSuccess('Đang nhập thành công', ['redirect_url' => public_link('')]);//đúng thì cho vào chơi
        }

        return eView::getInstance()->getJsonError('Tài khoản hoặc mật khẩu không dúng. Vui lòng kiểm tra lại');

    }

    public function registration_member(){
        HtmlHelper::getInstance()->setTitle('Đăng kí tài khoản');

        if (!Request::capture()->isMethod('POST')) {
            return eView::getInstance()->getJsonError('Truy cập của bạn trái phép.');
        }

        $rules = [
            'fullname' => 'required|string|regex:/^[a-zA-Z]+/u',
            'username' => 'required|string|regex:/^[a-zA-Z0-9-_]+/u|max:50',
            'password' => 'required|string|regex:/^[a-zA-Z0-9-_]+/u|max:50',
        ];


        $validator = Validator::make(Request::capture()->all(), $rules, [
            'fullname.required' => 'Bạn chưa nhập họ và tên',
            'fullname.string' => 'Họ và tên phải là một chuỗi',
            'fullname.regex' => 'Họ và tên không có kí tự đặc biệt',

            'username.required' => 'Bạn chưa nhập tên tài khoản',
            'username.string' => 'Tên tài khoản phải là một chuỗi',
            'username.regex' => 'Tên tài khoản không có kí tự đặc biệt',
            'username.max' => 'Tên tài khoản không quá 50 kí tự',

            'password.required' => 'Bạn chưa nhập mật khẩu',
            'password.string' => 'Mật khẩu phải là một chuỗi',
            'password.regex' => 'Mật khẩu không có kí tự đặc biệt',
            'password.max' => 'Mật khẩu không quá 50 kí tự',
        ]);
        if ($validator->fails()) {
            return eView::getInstance()->getJsonError($validator->errors()->first());
        }

        $fullname = Request::capture()->input('fullname', '');
        $email = Request::capture()->input('email', '');
        $username = Request::capture()->input('username', '');
        $password = Request::capture()->input('password', '');
        $rePassword = Request::capture()->input('rePassword', '');

        if(empty($email) || !Helper::isEmail($email)){
            return eView::getInstance()->getJsonError('Email không được trống hoặc không đúng định dạng');
        }

        if ($password !== $rePassword){
            return eView::getInstance()->getJsonError('Mật khẩu nhập lại không chính xác');
        }

        $member = [
            'fullname' => $fullname,
            'email' => $email,
            'account' => $username,
            'password' => Member::encodePassword($password),
            'status' => Member::ACTIVE_MEMBER,
            'updated_at' => Helper::getMongoDate(),
            'created_at' => Helper::getMongoDate(),
        ];

        $insert = Member::insertGetId($member);
        if(!empty($insert)){
            return eView::getInstance()->getJsonSuccess('Tạo tài khoản thành công', ['redirect_url' => public_link('')]);
        }
        return eView::getInstance()->getJsonError('Tạo tài khoản thất bại');
    }

}
