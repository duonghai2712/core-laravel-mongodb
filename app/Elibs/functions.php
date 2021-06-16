<?php

function access_link($router = '')
{
    $router = '/' . $router;

    return url(str_replace('//', '/', '/' . $router));
}

function public_link($router, $withoutMember = FALSE)
{
    if (!$withoutMember && \App\Http\Models\Member::$currentMember) {
        $_router = explode('?', $router);
        if (isset($_router[1])) {
            $router .= '&uid=' . \App\Http\Models\Member::$currentMember['_id'];
        } else {
            $router .= '?uid=' . \App\Http\Models\Member::$currentMember['_id'];
        }
    }

    return url(str_replace('//', '/', '/' . $router));
}

function js_link($link, $attrs = '')
{
    return '<script type="text/javascript" ' . $attrs . ' src="' . asset($link) . '?v=' . config('app.version') . '"></script>';
}

if(!function_exists('sdebug')){
    function sdebug($o)
    {

        echo "<pre style='padding: 100px 200px'>\n";
        $debug = debug_backtrace();
        echo ($debug[0]['file'] . ':' . $debug[0]['line']) . '<br/>';
        print_r($o);
        echo "</pre>\n";
    }
}

function short_link($slug,$full_link){
    if(!$full_link){
        return [];
    }
    $token = get_token_get_link();
    $token = isset($token['access']) ? $token['access'] : '';
    if(!$token){
        return [];
    }
    $params = [
        'short_path' => $slug,
        'long_url' => $full_link,
    ];
    $header[] = 'Authorization: Bearer ' . $token;
    $header[] = 'Api-version: 1.0';
    $result = \App\Elibs\Helper::_makeRequest2(config('crm-client.short_link_sec').'url/', $params, 'POST', '', [], $header);
    $result = json_decode($result, true);
    return $result;
}

function get_token_get_link(){
    $params = ['username' => config('crm-client.short_link_user'),'password' => config('crm-client.short_link_password')];
    $result = \App\Elibs\Helper::_makeRequest2(config('crm-client.short_link_sec').'jwt/token/', $params, 'POST', '', [], []);
    $result = json_decode($result, true);
    return $result;
}
