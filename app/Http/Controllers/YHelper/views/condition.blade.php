<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
    <meta name="description" content="Website làm ra chỉ để chơi">
    <!-- Fav Icon  -->
    <title>{{$HtmlHelper['Seo']['title']}}</title>
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{{public_link('backend-ui/images/favicon.png')}}">
    <!-- Page Title  -->
    <!-- StyleSheets  -->

    {!! \App\Elibs\HtmlHelper::getInstance()->setCssLink('backend-ui/assets/css/dashlite.css') !!}
    {!! \App\Elibs\HtmlHelper::getInstance()->setCssLink('backend-ui/assets/css/theme.css') !!}

</head>

<body class="nk-body bg-lighter npc-general has-sidebar ">
<div class="nk-app-root">
    <!-- main @s -->
    <div class="nk-main ">
        <!-- wrap @s -->
        <div class="nk-wrap ">
            <!-- content @s -->
            <div class="nk-content ">
                <div class="container-fluid">
                    <div class="nk-content-inner">
                        <div class="nk-content-body">
                            <div class="content-page wide-md m-auto">
                                <div class="nk-block-head nk-block-head-lg wide-xs mx-auto">
                                    <div class="nk-block-head-content text-center">
                                        <h2 class="nk-block-title fw-normal">Regular Page Title</h2>
                                        <div class="nk-block-des">
                                            <p class="lead">We love to share ideas! Visit our blog if you're looking for great articles or inspiration to get you going.</p>
                                        </div>
                                    </div>
                                </div><!-- .nk-block-head -->
                                <div class="nk-block">
                                    <div class="card card-bordered">
                                        <div class="card-inner card-inner-xl">
                                            <article class="entry">
                                                <h3>Fuga eius ipsama dolores asperiores</h3>
                                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Fuga eius ipsam blanditiis voluptatem mollitia dolores asperiores ipsum rerum repellendus. Ullam et, quam eos blanditiis ipsum tempore minus quis laborum praesentium.</p>
                                                <p>Popsam blanditiis voluptatem mollitia dolores asperiores ipsum rerum repellendus. Ullam et, quam eos blanditiis ipsum tempore.</p>
                                                <img src="{{public_link('backend-ui/images/slides/slide-b.jpg')}}" alt="">
                                                <h4>Mollitia dolores asperiores ipsum rerum repellendus</h4>
                                                <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illoveritatis et quasi architecto beatae vitae dicta sunt explicabo. </p>
                                                <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam.</p>
                                                <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illoveritatis et quasi architecto beatae vitae dicta sunt explicabo. </p>
                                                <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam.</p>
                                                <h5>Perspiciatis unde omnis iste natus error sit voluptatem</h5>
                                                <p>Mollitia dolores asperiores ipsum rerum repellendus Sed ut accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illoveritatis et quasi architecto beatae vitae dicta sunt explicabo. </p>
                                            </article>
                                        </div>
                                    </div>
                                </div><!-- .nk-block -->
                            </div><!-- .content-page -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- content @e -->
            <!-- footer @s -->
            <div class="nk-footer">
                <div class="container-fluid">
                    <div class="nk-footer-wrap">
                        <div class="nk-footer-copyright"> &copy; 2020 ynhan. Template by <a href="https://www.facebook.com/vocam.ht.0202/" target="_blank" style="color: blue !important;">thanyx</a>
                        </div>
                        <div class="nk-footer-links">
                            <ul class="nav nav-sm">
                                <li class="nav-item"><a class="nav-link" href="{{public_link('/help/condition')}}" target="_blank">Điều khoản & Điều kiện</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{public_link('/help/policy')}}" target="_blank">Chính sách bảo mật</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{public_link('/help/helper')}}" target="_blank">Trợ giúp</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- footer @e -->
        </div>
        <!-- wrap @e -->
    </div>
    <!-- main @e -->
</div>
<!-- app-root @e -->
<!-- JavaScript -->
{!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('backend-ui/assets/js/bundle.js') !!}
{!! \App\Elibs\HtmlHelper::getInstance()->setLinkJs('backend-ui/assets/js/scripts.js') !!}
</body>

</html>
