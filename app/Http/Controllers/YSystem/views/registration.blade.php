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
                <h5 class="nk-block-title">Đang kí</h5>
                <div class="nk-block-des">
                    <p>Tạo tài khoản mới</p>
                </div>
            </div>
        </div><!-- .nk-block-head -->
        <form name="postInputFormRegistration" autocomplete="off"
              onsubmit="return MNG_POST.update('{{public_link('/auth/registration-member')}}', '#postInputFormRegistration','', {callback: registration_member});"
              id="postInputFormRegistration" method="post">
            <div class="form-group">
                <label class="form-label" for="name">Họ và tên</label>
                <input type="text" class="form-control form-control-lg" name="fullname" placeholder="Họ và tên">
            </div>
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input type="text" class="form-control form-control-lg" name="email" placeholder="Email">
            </div>
            <div class="form-group">
                <label class="form-label" for="email">Tên đăng nhập</label>
                <input type="text" class="form-control form-control-lg" name="username" placeholder="Tên đăng nhập">
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Mật khẩu</label>
                <div class="form-control-wrap">
                    <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch" data-target="password">
                        <em class="passcode-icon icon-show icon ni ni-eye"></em>
                        <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                    </a>
                    <input type="password" class="form-control form-control-lg" name="password" placeholder="Mật khẩu">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Nhập lại mật khẩu</label>
                <div class="form-control-wrap">
                    <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch" data-target="password">
                        <em class="passcode-icon icon-show icon ni ni-eye"></em>
                        <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                    </a>
                    <input type="password" class="form-control form-control-lg" name="rePassword" placeholder="Mật khẩu">
                </div>
            </div>
            <div class="form-group">
                <div class="custom-control custom-control-xs custom-checkbox" id="js_checkbox">
                    <input type="checkbox" class="custom-control-input" name="checkbox" id="checkbox">
                    <label class="custom-control-label" for="checkbox">Tôi đồng ý <a tabindex="-1" href="#">Chính sách</a> &amp; <a tabindex="-1" href="#"> Điều khoản.</a></label>
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-lg btn-primary btn-block" id="add-registration"
                        onclick="return MNG_POST.update('{{public_link('/auth/registration-member')}}', '#postInputFormRegistration','', {callback: registration_member});"
                        type="button" disabled>Đăng kí</button>
            </div>
        </form><!-- form -->
        <div class="form-note-s2 pt-4"> Bạn đã có một tài khoản ? <a href="#"><strong>Đăng nhập</strong></a>
        </div>
        <div class="text-center pt-4 pb-3">
            <h6 class="overline-title overline-title-sap"><span>Hoặc</span></h6>
        </div>
        <ul class="nav justify-center gx-8">
            <li class="nav-item"><a class="nav-link" href="#">Facebook</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Google</a></li>
        </ul>
    </div><!-- .nk-block -->
@stop

@section('JS_BOTTOM_REGION')
    <script>

        $('#js_checkbox :checkbox').on('change', function(){
            if($('input[name="checkbox"]').prop('checked') == 1){
                $("#add-registration").attr("disabled", false);
            }else{
                $("#add-registration").attr("disabled", true);
            }
        });


        function registration_member(json){
            console.log('json', json);
            if (json.status === 1) {
                Toastr.success(json.msg);
                if (typeof json.data.redirect_url !== 'undefined') {
                    location.href = json.data.redirect_url;
                }
            } else {
                Toastr.error(json.msg);
            }
        }
    </script>
@stop
