<?php
/**
 * Created by PhpStorm.
 * Member: ngannv
 * Date: 9/13/15
 * Time: 12:40 AM
 */

namespace App\Elibs;

use App\Http\Models\Member;

class Debug
{
    const DEBUG_ON = 1;
    private $dbInfo = '';


    static function show($obj, $label = '', $color = '#ffcebb')
    {
        echo "<pre style='border: 1px solid red;margin:5px;padding:5px;background-color:$color !important;max-height: 800px;overflow: auto'>";
        $debug = debug_backtrace();
        echo "<h2>$label</h2>";
        echo ($debug[0]['file'] . ':' . $debug[0]['line']) . '<br/>';
        print_r($obj);
        echo "</pre>";
    }

    static function print_r($o)
    {
        echo "<pre>\n";
        print_r($o);
        echo "</pre>\n";


    }

    static function startDebugTime($string)
    {
        if (\config('debugbar.enabled')) {
            start_measure($string);
        }
    }

    static function endDebugTime($string)
    {
        if (\config('debugbar.enabled')) {
            stop_measure($string);
        }
    }

    static function backTrack()
    {
        echo "<pre style='border: 1px solid red;margin:5px;padding:5px;background-color: !important;max-height: 800px;overflow: auto'>";
        $debug = debug_backtrace();
        echo (@$debug[1]['file'] . ':' . @$debug[1]['line']) . '<br/>';
        echo (@$debug[2]['file'] . ':' . @$debug[2]['line']) . '<br/>';
        echo (@$debug[3]['file'] . ':' . @$debug[3]['line']) . '<br/>';

        echo "</pre>";
        die();
    }

    static function pushNotification($msg='',$option=[]){

        $msg.="\nuid: ".@Member::getCurrentMemberId();
        $msg.="\n";
        $msg .= "\nREQUEST_URI: " .!isset($option['REQUEST_URI'])? @$_SERVER['REQUEST_URI']:'';
        $msg .= "\nREMOTE_ADDR: " . @$_SERVER['REMOTE_ADDR'];
        $msg .= "\nHTTP_X_ORIGINAL_FORWARDED_FOR: " . @$_SERVER['HTTP_X_ORIGINAL_FORWARDED_FOR'];
        $msg .= "\nHTTP_X_FORWARDED_FOR: " . @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $msg .= "\nHTTP_X_REAL_IP: " . @$_SERVER['HTTP_X_REAL_IP'];
        $msg .= "\n\nHTTP_USER_AGENT: " . @$_SERVER['HTTP_USER_AGENT'];
        $msg .= "\nHTTP_REFERER: " . @$_SERVER['HTTP_REFERER'];
        $msg .= "\nSERVER_NAME: " . @$_SERVER['SERVER_NAME'];
        $msg .= "\nHTTP_HOST: " . @$_SERVER['HTTP_HOST'];

        $ch = curl_init();
        // Set the URL
        curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot1448193324:AAGNjy1lCXXdCMdLDgHj5_44eBeMjyF-1gY/sendMessage?chat_id=@ynhannn&text='  . urlencode($msg));
        // Removes the headers from the output
        curl_setopt($ch, CURLOPT_HEADER, 0);
        // Return the output instead of displaying it directly
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // Execute the curl session
        curl_exec($ch);
        // Close the curl session
        curl_close($ch);
        // Return the output as a variable
        return true;
    }

}
