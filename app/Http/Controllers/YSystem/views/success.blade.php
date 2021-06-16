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
                <h5 class="nk-block-title">Cảm ơn bạn đã gửi cho chúng tôi!</h5>
            </div>
        </div>
    </div><!-- .nk-block -->
@stop
