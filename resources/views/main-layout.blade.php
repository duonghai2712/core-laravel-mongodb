<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
    <meta name="description" content="Website làm ra chỉ để chơi">
    <meta name="_token" content="{{csrf_token()}}">
    <title>{{$HtmlHelper['Seo']['title']}}</title>
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{{public_link('backend-ui/images/favicon.png')}}">
    <!-- Page Title  -->
    <!-- StyleSheets  -->
    @yield('CSS_REGION')
    @stack('CSS_REGION')
    {!! \App\Elibs\HtmlHelper::getInstance()->setCssLink('backend-ui/assets/css/dashlite.css') !!}
    {!! \App\Elibs\HtmlHelper::getInstance()->setCssLink('backend-ui/assets/css/theme.css') !!}

    {!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('backend-ui/assets/js/core/libraries/jquery.min.js') !!}
    {!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('backend-ui/assets/js/core/libraries/jquery_ui/core.min.js') !!}
    {!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('backend-ui/assets/js/core/libraries/bootstrap.min.js') !!}

    {!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('backend-ui/assets/io/io.js') !!}
    {!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('backend-ui/assets/js/app.js') !!}

    @yield('JS_REGION')
    @stack('JS_REGION')

    <style>
        @media (min-width: 1540px) {
            .nk-split .nk-auth-body, .nk-split .nk-auth-footer {
                margin-right: auto !important;
            }
        }
    </style>

</head>

<body class="nk-body npc-crypto ui-clean pg-auth">
<!-- app body @s -->
<div class="nk-app-root">
    <div class="nk-split nk-split-page nk-split-md">
        <div class="nk-split-content nk-block-area nk-block-area-column nk-auth-container">
            <div class="absolute-top-right d-lg-none p-3 p-sm-5">
                <a href="{{public_link('')}}" class="toggle btn-white btn btn-icon btn-light" data-target="athPromo"><em class="icon ni ni-info"></em></a>
            </div>
            @yield('CONTENT_REGION')
            <div class="nk-block nk-auth-footer">
                <div class="nk-block-between">
                    <ul class="nav nav-sm">
                        <li class="nav-item">
                            <a class="nav-link" href="{{public_link('/help/condition')}}" target="_blank">Điều khoản & Điều kiện</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{public_link('/help/policy')}}" target="_blank">Chính sách bảo mật</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{public_link('/help/helper')}}" target="_blank">Trợ giúp</a>
                        </li>
                    </ul><!-- .nav -->
                </div>
                <div class="mt-3">
                    <p>&copy; Bản quyền thuộc <a href="https://www.facebook.com/vocam.ht.0202/" target="_blank">ynhan</a>.</p>
                </div>
            </div><!-- .nk-block -->
        </div><!-- .nk-split-content -->
        <div class="nk-split-content nk-split-stretch bg-lighter d-flex toggle-break-lg toggle-slide toggle-slide-right" data-content="athPromo" data-toggle-screen="lg" data-toggle-overlay="true">
            <div class="slider-wrap w-100 w-max-550px p-3 p-sm-5 m-auto">
                <div class="slider-init" data-slick='{"dots":true, "arrows":false}'>
                    <div class="slider-item">
                        <div class="nk-feature nk-feature-center">
                            <div class="nk-feature-img">
                                <img class="round" src="{{public_link('backend-ui/images/slides/promo-a.png')}}" srcset="{{public_link('backend-ui/images/slides/promo-a2x.png')}} 2x" alt="">
                            </div>
                            <div class="nk-feature-content py-4 p-sm-5">
                                <h4>ynhan</h4>
                                <p>Bạn có thể được sử dụng công nghệ mới nhất để hỗ trợ tìm kiếm khách hàng cho sản phẩm của mình.</p>
                            </div>
                        </div>
                    </div><!-- .slider-item -->
                    <div class="slider-item">
                        <div class="nk-feature nk-feature-center">
                            <div class="nk-feature-img">
                                <img class="round" src="{{public_link('backend-ui/images/slides/promo-b.png')}}" srcset="{{public_link('backend-ui/images/slides/promo-b2x.png')}} 2x" alt="">
                            </div>
                            <div class="nk-feature-content py-4 p-sm-5">
                                <h4>ynhan</h4>
                                <p>Bạn có thể tìm kiếm được sản phẩm mong muốn ở chúng tôi.</p>
                            </div>
                        </div>
                    </div><!-- .slider-item -->
                    <div class="slider-item">
                        <div class="nk-feature nk-feature-center">
                            <div class="nk-feature-img">
                                <img class="round" src="{{public_link('backend-ui/images/slides/promo-c.png')}}" srcset="{{public_link('backend-ui/images/slides/promo-c2x.png')}} 2x" alt="">
                            </div>
                            <div class="nk-feature-content py-4 p-sm-5">
                                <h4>ynhan</h4>
                                <p>Bạn có thể quản lý đươc cửa hàng của mình một cách hiệu quả hơn với kênh người bán.</p>
                            </div>
                        </div>
                    </div><!-- .slider-item -->
                </div><!-- .slider-init -->
                <div class="slider-dots"></div>
                <div class="slider-arrows"></div>
            </div><!-- .slider-wrap -->
        </div><!-- .nk-split-content -->
    </div><!-- .nk-split -->
</div><!-- app body @e -->
<!-- JavaScript -->
@yield('JS_BOTTOM_REGION')
@stack('JS_BOTTOM_REGION')
{!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('backend-ui/assets/js/bundle.js') !!}
{!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('backend-ui/assets/js/scripts.js') !!}
{!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('backend-ui/assets/js/example-sweetalert.js') !!}
{!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('backend-ui/assets/js/example-toastr.js') !!}
</body>

</html>
