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
                <h5 class="nk-block-title">Reset password</h5>
                <div class="nk-block-des">
                    <p>Nếu bạn quên mật khẩu của mình, chúng tôi sẽ gửi email hướng dẫn đặt lại mật khẩu cho bạn.</p>
                </div>
            </div>
        </div><!-- .nk-block-head -->
        <form action="#">
            <div class="form-group">
                <div class="form-label-group">
                    <label class="form-label" for="default-01">Email</label>
                </div>
                <input type="text" class="form-control form-control-lg" id="default-01" placeholder="Enter your email address">
            </div>
            <div class="form-group">
                <button class="btn btn-lg btn-primary btn-block">Gửi</button>
            </div>
        </form><!-- form -->
        <div class="form-note-s2 pt-5">
            <a href="{{public_link('/auth/login')}}"><strong>Quay lại đăng nhập</strong></a>
        </div>
    </div><!-- .nk-block -->
@stop

