@extends('main-layout')

@section('CONTENT_REGION')
    <div class="nk-block nk-block-middle nk-auth-body">
        <div class="brand-logo pb-5">
            <a href="#" class="logo-link">
                <img class="logo-light logo-img logo-img-lg" src="{{public_link('backend-ui/images/logo.png')}}" srcset="{{public_link('backend-ui/images/logo2x.png')}} 2x" alt="logo">
                <img class="logo-dark logo-img logo-img-lg" src="{{public_link('backend-ui/images/logo-dark.png')}}" srcset="{{public_link('backend-ui/images/logo-dark2x.png')}} 2x" alt="logo-dark">
            </a>
        </div>
        <div class="nk-block-head">
            <div class="nk-block-head-content">
                <h5 class="nk-block-title">Đăng nhập</h5>
            </div>
        </div><!-- .nk-block-head -->
        <form name="postInputFormLogin" autocomplete="off"
              onsubmit="return MNG_POST.update('{{public_link('/auth/access-login')}}', '#postInputFormLogin','', {callback: access_login});"
              id="postInputFormLogin" method="post">
            <div class="form-group">
                <div class="form-label-group">
                    <label class="form-label">Email hoặc tên đăng nhập</label>
                </div>
                <input type="text" class="form-control form-control-lg" name="username" placeholder="Email hoặc tên đăng nhập" >
            </div><!-- .foem-group -->
            <div class="form-group">
                <div class="form-label-group">
                    <label class="form-label" for="password">Mật khẩu</label>
                    <a class="link link-primary link-sm" tabindex="-1" href="{{public_link('/auth/reset-password')}}">Quên mật khẩu?</a>
                </div>
                <div class="form-control-wrap">
                    <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch" data-target="password">
                        <em class="passcode-icon icon-show icon ni ni-eye"></em>
                        <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                    </a>
                    <input type="password" class="form-control form-control-lg" name="password" placeholder="Mật khẩu" >
                </div>
            </div><!-- .foem-group -->
            <div class="form-group">
                <button type="button" class="btn btn-lg btn-primary"
                        onclick="return MNG_POST.update('{{public_link('/auth/access-login')}}', '#postInputFormLogin','', {callback: access_login});"
                >Đăng nhập</button>
            </div>
        </form><!-- form -->
        <div class="form-note-s2 pt-4"><a href="{{public_link('/auth/registration')}}">Tạo tài khoản mới</a>
        </div>
        <div class="text-center pt-4 pb-3">
            <h6 class="overline-title overline-title-sap"><span>Hoặc</span></h6>
        </div>
        <ul class="nav justify-center gx-4">
            <li class="nav-item"><a class="nav-link" href="#">Facebook</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Google</a></li>
        </ul>
    </div><!-- .nk-block -->
@stop

@section('JS_BOTTOM_REGION')
    <script>
        function access_login(json){
            console.log('json', json);
            if (json.status === 1) {
                Toastr.success(json.msg);
                if (typeof json.data.redirect_url !== 'undefined') {
                    window.location.href = json.data.redirect_url;
                }
                if (typeof json.data.reload !== 'undefined') {
                    window.location.reload();
                }
            } else {
                Toastr.error(json.msg);
            }
        }
    </script>
@stop
