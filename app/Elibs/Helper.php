<?php

namespace App\Elibs;

use App\Http\Models\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use MongoDB\BSON\ObjectId;

/**
 * Created by PhpStorm.
 * Member: ngannv
 * Date: 8/16/15
 * Time: 7:58 PM
 */
class Helper
{
    /***
     * @param $email
     *
     * @return bool
     * @note: validate xem có phải là email hay không?
     */

    static function isEmail($email)
    {

        return (!preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', trim($email))) ? FALSE : TRUE;
//        return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", trim($email))) ? FALSE : TRUE;
    }

    static function isPhoneNumber($number)
    {
        $number =  trim($number);
        if(preg_match("/^\+0/i",$number))
        {
            return  false;
        }

        return preg_match("/^\+?\d{9,16}$/i",$number);
        //return preg_match("/^(01([0-9]{2})|09[0-9]|08[0-9])(\d{7})$/i", $number);
    }

    static function isAccount($string)
    {
        return preg_match('/^[a-z0-9]+[._]?[a-z0-9]+$/', $string);
    }

    public static function isDatetime($str, $format = 'd/m/Y')
    {

        try {
            $str = trim($str);
            $d = Carbon::createFromFormat($format, $str);
        } catch (\InvalidArgumentException $e) {
            return FALSE;
        }

        return $d && $d->format($format) == $str;
    }

    static function getFileType($file_name)
    {
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        // if()
    }

    static function convertTimeToInt($time, $split = '/', $hour = '')
    {
        if (!$time) {
            return FALSE;
        }
        $t = explode($split, trim($time));
        if ($hour) {
            $h = explode(':', trim($hour));
        }


        return mktime(isset($h[0]) ? $h[0] : 0, isset($h[1]) ? $h[1] : 0, isset($h[2]) ? $h[2] : 0, @$t[1], @$t[0], @$t[2]);
    }

    public static function convertStringToNumber($number, $point = [',', '.', ' '])
    {
        //todo: có thể bổ sung thêm việc xóa các ký tự khác "Không phải số"
        return str_replace($point, '', $number);
    }

    public static function replaceMQ($text)
    {
        $text = str_replace("\'", "'", $text);
        $text = str_replace("'", "''", $text);

        return $text;
    }

    public static function convertDateTime($strDate = "", $strTime = "")
    {
        //Break string and create array date time
        $strDate = str_replace("/", "-", $strDate);
        $strDateArray = explode("-", $strDate);
        $countDateArr = count($strDateArray);
        $strTime = str_replace("-", ":", $strTime);
        $strTimeArray = explode(":", $strTime);
        $countTimeArr = count($strTimeArray);
        //Get Current date time
        $today = getdate();
        $day = $today["mday"];
        $mon = $today["mon"];
        $year = $today["year"];
        $hour = $today["hours"];
        $min = $today["minutes"];
        $sec = $today["seconds"];
        //Get date array
        switch ($countDateArr) {
            case 2:
                $day = intval($strDateArray[0]);
                $mon = intval($strDateArray[1]);
                break;
            case $countDateArr >= 3:
                $day = intval($strDateArray[0]);
                $mon = intval($strDateArray[1]);
                $year = intval($strDateArray[2]);
                break;
        }
        //Get time array
        switch ($countTimeArr) {
            case 2:
                $hour = intval($strTimeArray[0]);
                $min = intval($strTimeArray[1]);
                break;
            case $countTimeArr >= 3:
                $hour = intval($strTimeArray[0]);
                $min = intval($strTimeArray[1]);
                $sec = intval($strTimeArray[2]);
                break;
        }
        //Return date time integer
        if (@mktime($hour, $min, $sec, $mon, $day, $year) == -1) return $today[0];
        else return mktime($hour, $min, $sec, $mon, $day, $year);
    }

    public static function removeAccent($mystring, $exclude = [])
    {
        $marTViet = [
            // Chữ thường
            "à", "á", "ạ", "ả", "ã", "â", "ầ", "ấ", "ậ", "ẩ", "ẫ", "ă", "ằ", "ắ", "ặ", "ẳ", "ẵ",
            "è", "é", "ẹ", "ẻ", "ẽ", "ê", "ề", "ế", "ệ", "ể", "ễ",
            "ì", "í", "ị", "ỉ", "ĩ",
            "ò", "ó", "ọ", "ỏ", "õ", "ô", "ồ", "ố", "ộ", "ổ", "ỗ", "ơ", "ờ", "ớ", "ợ", "ở", "ỡ",
            "ù", "ú", "ụ", "ủ", "ũ", "ư", "ừ", "ứ", "ự", "ử", "ữ",
            "ỳ", "ý", "ỵ", "ỷ", "ỹ",
            "đ", "Đ",
            // Chữ hoa
            "À", "Á", "Ạ", "Ả", "Ã", "Â", "Ầ", "Ấ", "Ậ", "Ẩ", "Ẫ", "Ă", "Ằ", "Ắ", "Ặ", "Ẳ", "Ẵ",
            "È", "É", "Ẹ", "Ẻ", "Ẽ", "Ê", "Ề", "Ế", "Ệ", "Ể", "Ễ",
            "Ì", "Í", "Ị", "Ỉ", "Ĩ",
            "Ò", "Ó", "Ọ", "Ỏ", "Õ", "Ô", "Ồ", "Ố", "Ộ", "Ổ", "Ỗ", "Ơ", "Ờ", "Ớ", "Ợ", "Ở", "Ỡ",
            "Ù", "Ú", "Ụ", "Ủ", "Ũ", "Ư", "Ừ", "Ứ", "Ự", "Ử", "Ữ",
            "Ỳ", "Ý", "Ỵ", "Ỷ", "Ỹ",
            "Đ", "Đ",
        ];
        $marKoDau = [
            /// Chữ thường
            "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a",
            "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e",
            "i", "i", "i", "i", "i",
            "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o",
            "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u",
            "y", "y", "y", "y", "y",
            "d", "D",
            //Chữ hoa
            "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A",
            "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E",
            "I", "I", "I", "I", "I",
            "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O",
            "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U",
            "Y", "Y", "Y", "Y", "Y",
            "D", "D",
        ];

        return str_replace($marTViet, $marKoDau, $mystring);
    }

    public static function convertToAlias($str, $option = [])
    {
        if (!is_array($option)) {
            $option = [];
        }
        //$str = strtolower(self::removeAccent($str));
        $str = self::removeAccent($str);
        $str = self::url_slug($str, $option);

        return $str;
        //return self::slugify($str);

        $str = trim($str);

        $str = str_replace("   ", " ", $str);
        $str = str_replace("ū", "u", $str);
        $str = str_replace("沐", " ", $str);
        $str = str_replace("轶", " ", $str);
        $str = str_replace("沐轶", " ", $str);
//        $str = preg_replace('/\P{Han}+/', '', $str);
//        $str = preg_replace('/[^\u4E00-\u9FFF]+/', '', $str);
        $str = str_replace("  ", " ", $str);
        $str = str_replace(" ", $replace, $str);

        return $str;
    }

    static function url_slug($str, $options = [])
    {
        // Make sure string is in UTF-8 and strip invalid UTF-8 characters
        // $str = strtolower($str);
        $str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());

        $defaults = [
            'delimiter' => '-',
            'limit' => NULL,
            'lowercase' => TRUE,
            'replacements' => [],
            'transliterate' => TRUE,
        ];

        // Merge options
        $options = array_merge($defaults, $options);

        $char_map = [
            // Latin
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
            'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
            'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
            'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
            'ß' => 'ss',
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
            'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
            'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
            'ÿ' => 'y',
            // Latin symbols
            '©' => '(c)',
            // Greek
            'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
            'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
            'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
            'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
            'Ϋ' => 'Y',
            'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
            'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
            'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
            'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
            'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
            // Turkish
            'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
            'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
            // Russian
            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
            'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
            'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
            'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
            'Я' => 'Ya',
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
            'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
            'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
            'я' => 'ya',
            // Ukrainian
            'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
            'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
            // Czech
            'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
            'Ž' => 'Z',
            'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
            'ž' => 'z',
            // Polish
            'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
            'Ż' => 'Z',
            'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
            'ż' => 'z',
            // Latvian
            'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
            'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
            'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
            'š' => 's', 'ū' => 'u', 'ž' => 'z',
        ];

        // Make custom replacements
        $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);

        // Transliterate characters to ASCII
        if ($options['transliterate']) {
            $str = str_replace(array_keys($char_map), $char_map, $str);

        }
        // Replace non-alphanumeric characters with our delimiter
        $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);

        // Remove duplicate delimiters
        if( ! (isset($options['not_remove_duplicate']) && $options['not_remove_duplicate'] ))
        {
            $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);

        }

        // Truncate slug to max. characters
        $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');

        // Remove delimiter from ends
        if( ! (isset($options['not_trim']) && $options['not_trim'] ))
        {
            $str = trim($str, $options['delimiter']);

        }

        return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
    }

    static public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        return $text;
    }

    public static function safeText($string)
    {
        $key = '<=';
        if(strpos($string,$key) !== false){
            $stringE = explode($key,$string);
            foreach($stringE as $k => $v){
                $stringE[$k] = strip_tags($v);
            }
            return join($key,$stringE);
        }else{
            return strip_tags($string);
        }
    }

    public static function safeInput($p)
    {
        return Helper::safeText(request($p));
    }

    public static function trimAllSpace($string)
    {
        $string = mb_convert_encoding($string, "HTML-ENTITIES", "UTF-8");
        $string = str_replace('&nbsp;', '', $string);
        $string = str_replace(' ', '', $string);
        $string = preg_replace('/\s+/', '', $string);

        return $string;
    }

    public static function randomString($length = 8)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public static function randomStringWithoutNumber($length = 8)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public static function getFileExtension($file)
    {
        $pos = strrpos($file, '.');
        if (!$pos) {
            return FALSE;
        }
        $str = substr($file, $pos, strlen($file));

        return strtolower($str);
    }

    public static function numberFormat($stringNumber)
    {
        return number_format($stringNumber, 0, '', '.');
    }

    static function getDomainByLink($link)
    {

        $rex = '/^https?\:\/\/([^\/?#]+)(?:[\/?#]|$)/';
        if (preg_match($rex, $link, $matches)) {
            return rtrim($matches[0], '/');
        } else {
            return FALSE;
        }
    }

    static public function isLink($link = '')
    {
        return filter_var($link, FILTER_VALIDATE_URL);
    }

    static public function joinAreaContent($content, $string_split = ',')
    {
        $content = preg_split('/\r\n|[\r\n]/', $content);

        return implode($string_split, $content);
    }

    static public function removeAllSpecialChar($item)
    {
        return preg_replace('/[^A-Za-z0-9\-\+\.\@\_]/' ,' ' , $item);
    }

    static function setSession($key, $val, $prefix = 'y_')
    {
        if ($prefix) {
            $key = $prefix . $key;
        }
        if(is_array($val) && isset($val['created_at']))
        {
            unset($val['created_at']);
            unset($val['updated_at']);

        }


        Session::put($key, $val);
        Session::save();
        //$_SESSION[$key] = $val;
    }

    static function getSession($key, $default = '', $prefix = 'y_')
    {
        if ($prefix) {
            $key = $prefix . $key;
        }

        return Session::get($key, $default);

        /*if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        } else {
            return $default;
        }*/
    }

    static function delSession($key, $prefix = 'y_')
    {
        if ($prefix) {
            $key = $prefix . $key;
        }
        session()->forget($key);
    }

    static function setCookie($name, $value, $minutes = 840000)
    {
        Cookie::queue($name, $value, $minutes);
    }

    static function getCookie($key, $default = '')
    {
        return Cookie::get($key);
    }

    static function delCookie($key)
    {
//        Cookie::forget($key);
        Cookie::queue(Cookie::forget($key));
    }

    static function subString($message, $start = 0, $length)
    {
        $length = (int)$length;
        if (isset($message[$length + 1])) {
            return mb_substr($message, $start, $length) . "...";
        } else {
            return $message;
        }
    }

    static function formatTimestamp($time, $toString = FALSE)
    {
        if (!$time) {
            return $toString ? "" : [];
        }
        $diff = ['days' => 0, 'hours' => 0, 'minutes' => 0];
        $diffTime = TIME_NOW - $time;
        $days = intval($diffTime / (24 * 60 * 60));
        if ($days > 1) {
            if ($toString) {
                if ($days == 2) {
                    return date('H:i', $time) . " | Hôm qua";
                }

                return date('H:i:s d/m/Y', $time);
            }
            $diff['days'] = $days;
        } else {
            if ($toString) {
                return date('H:i', $time) . " | Hôm nay";
            }
            $hours = intval($diffTime / (60 * 60));
            if ($hours > 0) {
                $diff['hours'] = $hours;
            } else {
                $minutes = intval($diffTime / 60);
                $diff['minutes'] = $minutes;
            }
        }

        return $diff;
    }

    static function convertTimestamp($time, $format = 'd/m/Y', $MiniSecond = false)
    {
        $date = \DateTime::createFromFormat($format, $time);
        $time = strtotime($date->format('Y/m/d'));
        if($MiniSecond){
            return $time * 1000;
        }
        return $time;
    }

    static function durationTime($_time)
    {
        $time = time() - $_time;

        if ($time > 0) {
            if ($time < 4 * 86400) {
                /*if($time>(365*86400)){
                    return floor($time/(365*86400)).' năm trước';
                }

                if($time>(30*86400)){
                    return floor($time/(30*86400)).' tháng trước';
                }
                */
                if ($time > (7 * 86400)) {
                    return floor($time / (7 * 86400)) . ' tuần trước';
                }
                if ($time > 86400) {
                    return floor($time / (86400)) . ' ngày trước';
                }

                if ($time > 3600) {
                    return floor($time / (3600)) . ' giờ trước';
                }

                if ($time > 60) {
                    return floor($time / (60)) . ' phút trước';
                }
            } else {
                return date('d/m/Y', $_time);
            }
        }

        return ' vài giây trước';
    }

    static function isMobile()
    {
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
            return TRUE;
        }

        return FALSE;
    }

    static function mkdir($path)
    {
        @mkdir($path, 0777, TRUE);
    }

    static function validateLinkHaveToken(){
        $tkey = request('tkey');
        $ktoken = request('ktoken');
        if($tkey && $ktoken && Helper::validateTokenWithSessionId($ktoken,$tkey)){
            return true;
        }
        return false;
    }

    static function buildTokenStringWithSessionId($id, $extra = '')
    {
        return $id . '-' . sha1($id . 'ynhan' . $id . 'thany' . $extra . Session::getId());
    }

    static function validateTokenWithSessionId($token, $id = '')
    {
        $obj = explode('-', $token);
        if (!isset($obj[1])) {
            return FALSE;
        }
        if (!$id) {
            $id = $obj[0];
        }
        if (self::buildTokenStringWithSessionId($id) == $token) {
            return $id;
        }

        return FALSE;
    }

    static function buildTokenString($id, $extra = '')
    {
        return $id . '-' . sha1($id . 'ynhan' . $id . 'thany' . $extra);
    }

    static function validateToken($token, $id = '')
    {
        $obj = explode('-', $token);
        if (!isset($obj[1])) {
            return FALSE;
        }
        if (!$id) {
            $id = $obj[0];
        }
        if (self::buildTokenString($id) == $token) {
            return $id;
        }

        return FALSE;
    }

    static function buildLinkVoVan($domain, $param)
    {
        return $domain . "?" . http_build_query($param);
    }

    static function getUrlContent($url)
    {
        $agent = [
            "Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/47.0",
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)',
            "Mozilla/5.0 (Macintosh; Intel Mac OS X x.y; rv:42.0) Gecko/20100101 Firefox/42.0",
            "Mozilla/5.0 (iPhone; CPU iPhone OS 10_3_1 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/10.0 Mobile/14E304 Safari/602.1",
            'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
            'Googlebot/2.1 (+http://www.google.com/bot.html)',
        ];
        shuffle($agent);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_USERAGENT, $agent[0]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_COOKIE, 'ci_session=a%3A5%3A%7Bs%3A10%3A%22session_id%22%3Bs%3A32%3A%220132fd4e6d33e7f75942fbff1699fb10%22%3Bs%3A10%3A%22ip_address%22%3Bs%3A13%3A%22116.96.251.34%22%3Bs%3A10%3A%22user_agent%22%3Bs%3A115%3A%22Mozilla%2F5.0+%28Windows+NT+10.0%3B+Win64%3B+x64%29+AppleWebKit%2F537.36+%28KHTML%2C+like+Gecko%29+Chrome%2F61.0.3163.100+Safari%2F537.36%22%3Bs%3A13%3A%22last_activity%22%3Bi%3A1509558243%3Bs%3A9%3A%22user_data%22%3Bs%3A0%3A%22%22%3B%7D9a2ee928772e318fcc00198b3d23a3bb');

        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if (isset($_GET['ckbug'])) {
            echo $httpcode;
            echo $data;
            die();
        }

        //echo $httpcode;
        return ($httpcode >= 200 && $httpcode < 300) ? $data : FALSE;
    }

    static function showDate($date, $format = 'd/m/Y')
    {
        return self::showMongoDate($date, $format);
    }

    static function showMongoDate($date, $format = 'd/m/Y')
    {
        if (!$date) {
            return NULL;
        }

        if (is_object($date)) {
            if (get_class($date) === 'DateTime') {
                return $date->setTimezone(new \DateTimeZone(config('app.timezone')))->format($format);
            } else {
                return $date->toDateTime()->setTimezone(new \DateTimeZone(config('app.timezone')))->format($format);
            }
            //
            //return $date->toDateTime()->format($format);
        } else {
            if (is_string($date)) {

            }
        }

    }

    static function convertMongoDateToTimeStamp($date)
    {
        if (!$date) {
            return NULL;
        }

        if (is_object($date)) {
            if (get_class($date) === 'DateTime') {
                return $date->getTimestamp();
            } else {
                return $date->toDateTime()->getTimestamp ();
            }
            //
            //return $date->toDateTime()->format($format);
        } else {
            if (is_string($date)) {

            }
        }
    }

    static function validateDateTime($date, $format = 'd/m/Y H:i:s')
    {
        $d = \DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }

    static function getMongoDateTime($date_time_string = FALSE, $format = 'd/m/Y H:i:s')
    {
        if (!$date_time_string) {
            return new \MongoDB\BSON\UTCDateTime(strtotime('now') * 1000);
        }
        $date = strtotime(Carbon::createFromFormat($format, $date_time_string)->toDateTimeString());
        return new \MongoDB\BSON\UTCDateTime($date * 1000);
    }

    static function getMongoDateTimeWithTimeZone($date_time_string = FALSE, $format = 'd/m/Y H:i:s')
    {
        if (!$date_time_string) {
            return new \MongoDB\BSON\UTCDateTime(strtotime('now') * 1000);
        }
        $date = strtotime(Carbon::createFromFormat($format, $date_time_string,'UTC')->setTimezone('Asia/Phnom_Penh')->toDateTimeString());
        return new \MongoDB\BSON\UTCDateTime($date * 1000);
    }

    static function getMilliSecondFromMongoDateTime($date)
    {
        return (int)strval($date);
    }

    static function getMongoDateTimeMilliSecond($ms='')
    {
        if(!$ms)
        {
            $ms =   (int)(microtime(true) * 1000);
        }
        return new \MongoDB\BSON\UTCDateTime($ms);

    }

    static function getTimeMilliSecond()
    {
        return (int)(microtime(true) * 1000);
    }

    static function getMongoDate($date = NULL, $dimiter = '/', $start = TRUE)
    {
        if ($date) {
            $time = explode($dimiter, $date);
            if (!isset($time[1])) {
                return new \MongoDB\BSON\UTCDateTime(strtotime($date) * 1000);
            }
            if ($start) {
                $time = mktime(0, 0, 0, (int)$time[1], (int)$time[0], (int)$time[2]);
            } else {
                $time = mktime(23, 59, 59, (int)$time[1], (int)$time[0], (int)$time[2]);
            }

            return new \MongoDB\BSON\UTCDateTime($time * 1000);
        }

        return new \MongoDB\BSON\UTCDateTime(strtotime('now') * 1000);
    }

    static function isMongoDate($date)
    {
        if(!is_object($date))
        {
            return false;
        }
        $class = get_class($date);
        return $class == 'MongoDB\BSON\UTCDateTime' || $class == 'DateTime';
    }

    static function getMongoDateMktime($mktime = NULL)
    {
        if ($mktime) {
            return new \MongoDB\BSON\UTCDateTime($mktime * 1000);
        }
        return new \MongoDB\BSON\UTCDateTime(strtotime('now') * 1000);
    }

    static function getMongoDateFromMiniSecond($mktime = NULL)
    {
        if ($mktime) {
            return new \MongoDB\BSON\UTCDateTime($mktime);
        }
        return new \MongoDB\BSON\UTCDateTime(strtotime('now') * 1000);
    }

    static function uploadImageFromUrl($url, $to = '')
    {
        $linkParse = parse_url($url);
        $path = $linkParse['path'];
        $path = explode('/', $path);
        $fileName = end($path);
        array_pop($path);

        if (!$to) {
            $to = public_path("media/tutorial") . implode('/', $path);
            $dest = $linkParse['path'];
        } else {
            $dest = $to . $fileName;
            $to = public_path("media/tutorial/") . $to;
        }
        if (file_exists(public_path("media/tutorial") . $linkParse['path'])) {
            return '/tutorial' . $linkParse['path'];
        }
        Helper::mkdir($to);
        if (file_put_contents(public_path("media/tutorial") . $dest, file_get_contents($url))) {
            return '/tutorial' . $dest;
        }

        return NULL;

    }

    static function getNumberOnlyInString($str)
    {
        preg_match_all('!\d+!', $str, $matches);

        return implode('', $matches[0]);
        //return filter_var($str, FILTER_SANITIZE_NUMBER_INT);// cái này nó trả về cả số âm
    }

    static function convertToObjectId($id)
    {
        return new \MongoDB\BSON\ObjectId($id);
    }

    static function _makeRequest($url, $params, $method = 'POST', $type = '', $options = [], $header = [], $debug = false)
    {
        $url_debug = $url;
        eBug::startDebugTime(__METHOD__.$url_debug);

        if (is_array($options)) {
            $no_query_string = @$options['no_query_string'];
        };
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, @$_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);

        if ($type === 'json') {
            $header[] = 'Expect:';
            $header[] = 'Content-Type: application/json';
            $header[] = 'charset: utf-8';
            $params = json_encode($params);

        }
        if ($header) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }

        if ($method === 'POST') {
            if (!(isset($no_query_string) && $no_query_string)) {
                $params = http_build_query($params);
            }
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        }
        if ($debug) {
            $filep = fopen("dump.txt", "wb");
            curl_setopt($ch, CURLOPT_STDERR, $filep);

        }
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        $result = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        eBug::endDebugTime(__METHOD__.$url_debug);

        if (curl_error($ch)) {
            return FALSE;
        }
        if ($status != 200) {
            curl_close($ch);

            return FALSE;
        }
        curl_close($ch);

        return $result;
    }

    static function _makeRequest2($url, $params = [], $method = 'POST', $type = '', $options = [], $header = [], $debug = false)
    {
        $url_debug = $url;
        eBug::startDebugTime(__METHOD__.$url_debug);
        if (is_array($options)) {
            $no_query_string = @$options['no_query_string'];
        };
        $params = $params?$params:[];

        if ($method == 'GET') {
            $params = http_build_query($params);
            $url .= (strpos($url, '?') === false ? '?' : '&') . $params;
        }
        $CURLOPT_TIMEOUT = isset($options['timeout']) ? $options['timeout'] : 10;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, @$_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);

        $headers = [];

        if ($type === 'json') {
            if(@$options['json_header'])
            {
                foreach ($options['json_header'] as $h)
                {
                    $headers[] = $h;
                }

            }
            else{
                $headers[] = 'Expect:';
                $headers[] = 'Content-Type: application/json';
                $headers[] = 'charset: utf-8';
            }

            $params = json_encode($params);
        }
        if ($header) {
            $headers = array_merge($header,$headers);
        }
        if($headers)
        {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        }

        if ($method === 'POST' ) {
            if (!(isset($no_query_string) && $no_query_string) && $type !== 'json') {
                $params = http_build_query($params);
            }
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, $CURLOPT_TIMEOUT);
        }
        else if ($method === 'PUT') {
            if (!(isset($no_query_string) && $no_query_string) && $type !== 'json') {
                $params = http_build_query($params);
            }
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, $CURLOPT_TIMEOUT);
        }
        else if ($method == 'DELETE') {
            if (!(isset($no_query_string) && $no_query_string)) {
                $params = http_build_query($params);
            }
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, $CURLOPT_TIMEOUT);
        } else {
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, $CURLOPT_TIMEOUT);
        }

        if ($debug) {
            $filep = fopen("dump.txt", "wb");
            curl_setopt($ch, CURLOPT_STDERR, $filep);

        }
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        $result = curl_exec($ch);
        /*        if (config('app.debug')) {
                    Helper::setSession('PHPDEBUGBAR_STACK_DATA', $result);
                }
                if (config('debugbar.enabled')) {
                    $time_end = microtime(true);
                    //Ghi session lấy url làm key
                    $api_call_old = (array)Helper::getSession('API_CALL');
                    $debug = [
                        'date' => date('d-m-y H:i:s.u'),
                        'time' => $time_end - $time_start,
                        'url' => $url,
                        'result' => $result,
                        'run_at' => time(),
                        'param' => $params
                    ];
                    $api_call_old [] = $debug;

                    uasort($api_call_old, function ($a, $b) {
                        return @$a['run_at'] < @$b['run_at'];
                    });
                    $api_call_old = array_slice($api_call_old, 0, 5);

                    Helper::setSession('API_CALL', $api_call_old);
                }*/
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        eBug::endDebugTime(__METHOD__.$url_debug);

        if (curl_error($ch)) {
            return FALSE;
        }
        if ($status > 400) {
            curl_close($ch);
            return $result;
        }
        curl_close($ch);
        return $result;
    }

    static function toArray($obj)
    {

        return json_decode(json_encode($obj), true);
    }

    static function getDiffObject($old_data, $new_data, $options = [])
    {
        //Hàm cần đầu vào là 2 biến có thể convert về array (php) để qua đó , options chứa nhiều kiểu thông tin,
        /*trả về
        [
            'updated_at' => 'dữ liệu date lúc được diff',
            'updated_by' => 'email của người update',
            'data' => [
                ['field' =>'tên trường được thay đổi' ,
                 'old_value'=>"dữ liệu cũ (có thể là bất cử kiểu dữ liệu gì)",
                 'new_value'=>"dữ liệu mới (có thể là bất cử kiểu dữ liệu gì)"]
             ],

        ]

        */
        /*Muốn không so sánh các trường nào thì bổ sung thêm $options['ignore'] =['tên trường 1', 'tên trường 2']*/
        //todo cần Viết lại
        return $diffObj = [
            'updated_at' => Helper::getMongoDateTime(),
            'updated_by' => '',
            'data' => [],
        ];

        $default_ignore_keys = [
            'updated_at', 'created_at', '_id', '_v', 'customer_objectId', 'customer_ObjectId', 'status', 'history', 'linking', "created_by"
        ];
        if (isset($options['ignore']) && is_array($options['ignore'])) {
            $ignore_keys = array_merge($default_ignore_keys, $options['ignore']);
        } else {
            $ignore_keys = $default_ignore_keys;
        }

        $compare_keys_old = array_keys($old_data);
        $compare_keys_new = array_keys($new_data);

        $compare_keys = collect()
            ->concat($compare_keys_new)
            ->concat($compare_keys_old)
            ->unique()
            ->filter(function ($item) use ($ignore_keys) {
                return !in_array($item, $ignore_keys);
            });


        $changing_data = $compare_keys->map(function ($item) use ($old_data, $new_data) {
            $fieldChangeObj = ['field' => $item];
            if (isset($old_data[$item])) {
                $fieldChangeObj ['old_value'] = $old_data[$item];
                if (isset($old_data[$item]['$date']) && isset($old_data[$item]['$date']['$numberLong'])) {
                    $fieldChangeObj ['old_value'] = Helper::getMongoDateMktime($old_data[$item]['$date']['$numberLong']);
                }
            } else {
                $fieldChangeObj ['old_value'] = '';
            }

            if (isset($new_data[$item])) {
                $fieldChangeObj ['new_value'] = $new_data[$item];
                if (isset($new_data[$item]['$date']) && isset($new_data[$item]['$date']['$numberLong'])) {
                    $fieldChangeObj ['new_value'] = Helper::getMongoDateMktime($new_data[$item]['$date']['$numberLong']);
                }

            } else {
                $fieldChangeObj ['new_value'] = '';
            }

            return $fieldChangeObj;
        })->filter(function ($item) {
            $temp1 = $item['old_value'];
            if (is_array($item['old_value'])) {
                $temp1 = json_encode($item['old_value']);

            }
            $temp2 = $item['new_value'];
            if (is_array($item['new_value'])) {
                $temp2 = json_encode($item['new_value']);
            }
            return $temp1 != $temp2;
        })->toArray();

        foreach ($changing_data as $key1 => $value1) {

            if (is_array($value1)) {
                foreach ($value1 as $key2 => $value2) {

                    if (is_array($value2)) {

                        foreach ($value2 as $key3 => $value3) {
                            if (is_array($value3)) {
                                foreach ($value3 as $key4 => $value4) {
                                    if (isset($value4['$date']) && isset($value4['$date']['$numberLong'])) {
                                        $changing_data [$key1][$key2][$key3][$key4] = Helper::getMongoDateMktime($value4['$date']['$numberLong']);
                                    }
                                }
                                if (isset($value3['$date']) && isset($value3['$date']['$numberLong'])) {
                                    $changing_data [$key1][$key2][$key3] = Helper::getMongoDateMktime($value3['$date']['$numberLong']);
                                }
                            }
                        }
                    }
                }
            }
        }


        $diffObj = [
            'updated_at' => Helper::getMongoDateTime(),
            'updated_by' => '',
            'data' => array_values($changing_data),
        ];

        if (isset($options['updated_by'])) {
            $diffObj ['updated_by'] = $options['updated_by'];
        }
        return $diffObj;

    }

    static function isMongoId($id)
    {
        if ($id instanceof \MongoDB\BSON\ObjectID
            || is_string($id) && preg_match('/^[a-f\d]{24}$/i', $id)
        ) {
            return true;
        }
        return false;
    }

    static function getMongoId($id=null)
    {
        if($id===null)
        {
            return new ObjectId($id);
        }

        if ($id instanceof \MongoDB\BSON\ObjectID
            || is_string($id) && preg_match('/^[a-f\d]{24}$/i', $id)
        ) {
        }
        else{
            return '';
        }


        return new ObjectId($id);
    }

    static function getValue($arr, $value = 'value')
    {

        if (is_array($arr)) {
            if (isset($arr[$value])) {
                return Helper::getStringValue($arr[$value]);

            } else {
                if (isset($arr[0]) && isset($arr[0][$value])) {
                    return Helper::getStringValue($arr[0][$value]);
                }

            }
        } elseif (is_string($arr)) {
            return $arr;
        } else {
            return '';
        }


        return '';
    }

    static function getVal($arr,$key='value')
    {

        if (is_array($arr)) {
            if (isset($arr[$key])) {
                return Helper::getStringValue($arr[$key]);

            } else {
                if (isset($arr[0]) && isset($arr[0][$key])) {
                    return Helper::getStringValue($arr[0][$key]);
                }

            }
        } elseif (is_string($arr)) {
            return $arr;
        } elseif (is_object($arr) && Helper::isMongoDate($arr)) {
            return Helper::showMongoDate($arr, 'd-m-Y H:i:s');
        } else {
            if(Helper::isMongoId($arr))
            {
                return strval($arr);
            }
            if(is_numeric($arr))
            {
                return $arr;
            }

            return '';
        }


        return '';
    }

    static function _getStringValue($arr)
    {
        if(is_string($arr))
        {
            return strip_tags($arr);
        }
        else
            if(is_array($arr))
            {
                if(isset($arr['value'])){
                    return ($arr['value']);

                }
                else{
                    if(isset($arr[0]) && isset($arr[0]['value']))
                    {
                        return @strip_tags(@$arr[0]['value']);
                    }

                }
            }
            elseif(is_string($arr))
            {
                return strip_tags($arr);
            }

            elseif(is_object($arr) && \App\Elibs\Helper::isMongoDate($arr))
            {
                return \App\Elibs\Helper::showMongoDate($arr,'d-m-Y H:i:s');
            }

            else if(!is_array($arr) && !is_object($arr)) {
                return strval($arr);
            }




        return '';
    }

    static function getStringValue($arr)
    {
        $re = self::_getStringValue($arr);
        return is_string($re)?trim($re):'';
    }

    static function BsonDocumentToArray($item)
    {
        return \MongoDB\BSON\toPHP(\MongoDB\BSON\fromPHP($item), ['root' => 'array', 'document' => 'array']);

    }

    static function array_diff_assoc($newObj, $oldObj)
    {

        $diffOld = [];
        $diffNew = [];
        foreach ($newObj as $key => $value) {
            if(!in_array($key,['_id','__id']))
            {
                if (is_array($value)) {
                    if (!is_array(@$oldObj[$key]) || !@$oldObj[$key]) {
                        if(!(Helper::getVal($value)))
                        {
                            /* nếu value là rỗng và trước đó là null hoặc là array */
                        }
                        else{
                            $diffNew[$key] = $value;
                            $diffOld[$key] = @$oldObj[$key];
                        }

                    } else {
                        foreach($value as $_key => $rowValue){
                            if(isset($value[$_key]['encrypt'])){
                                unset($value[$_key]['encrypt']);
                            }
                            if(isset($value[$_key]['encrypt_aes'])){
                                unset($value[$_key]['encrypt_aes']);
                            }
                        }
                        foreach($oldObj[$key] as  $_key => $rowValue){
                            if(isset($oldObj[$key][$_key]['encrypt'])){
                                unset($oldObj[$key][$_key]['encrypt']);
                            }
                            if(isset($oldObj[$key][$_key]['encrypt_aes'])){
                                unset($oldObj[$key][$_key]['encrypt_aes']);
                            }
                        }

                        if ( $value !== @$oldObj[$key]) {
                            /*check chinh xac*/
                            $diffNew[$key] = $value;
                            $diffOld[$key] = @$oldObj[$key];
                        }
                    }
                } else if ( @$oldObj[$key] !== $value) {
                    /* check nếu value là kiểu số */



                    if(Helper::getVal(@$oldObj[$key]) !=  Helper::getVal($value))
                    {
                        $diffNew[$key] = $value;
                        $diffOld[$key] = @$oldObj[$key];
                    }
                    else{
                        if($key!='_is_converted')
                        {

                            if(is_numeric($value)  || is_numeric(@$oldObj[$key])){
                                /* nếu là kiểu số mới check chính xác, null,[],'' thì coi như ko đổi */
                                $diffNew[$key] = $value;
                                $diffOld[$key] = @$oldObj[$key];
                            }

                        }

                    }

                }
            }

        }


        return ['from' => $diffOld, 'to' => $diffNew];
    }

    static function convert_json_recursive($obj)
    {
        if (is_array($obj)) {
            foreach ($obj as $key => $value) {
                if (isset($value['$date']['$numberLong'])) {
                    $obj[$key] = Helper::getMongoDateFromMiniSecond($value['$date']['$numberLong']);
                } elseif (isset($value['$oid'])) {
                    $obj[$key] = $value['$oid'];
                } else {
                    if (is_array($value)) {
                        $obj[$key] = self::convert_json_recursive($value);
                    }
                }
            }
        }
        return $obj;
    }

    static function encrypt($str,$secret='',$method='')
    {
        $secret = $secret?$secret:config('app.ykey');
        $encryptionMethod = $method?$method:config('app.ycipher');
        $iv = substr($secret, 0, 16);

        return openssl_encrypt($str, $encryptionMethod, $secret, 0, $iv);

    }

    static function decrypt($str,$secret='',$method='')
    {
        $secret = $secret?$secret:config('app.ykey');
        $encryptionMethod = $method?$method:config('app.ycipher');
        $iv = substr($secret, 0, 16);

        return openssl_decrypt($str, $encryptionMethod, $secret, 0, $iv);

    }

    static function setCache($key, $value)
    {
        $id = Cache::where(['key' => $key])->select('_id')->first();
        if ($id) {
            Cache::where(['_id' => $id['_id']])->update(['value' => $value]);
        } else {
            Cache::where(['_id' => $id['_id']])->insertGetId([
                'key' => $key,

                'value' => $value
            ]);

        }

    }

    static function getCache($key)
    {
        $re = Cache::where(['key' => $key])->first();
        if (!$re) {
            return null;
        } else {
            return @$re['value'];
        }

    }

    static function deleteCache($key)
    {
        Cache::where(['key' => $key])->delete();

    }

    public static function processRangeDate($time, $spliter = '-', $range = true)
    {
        if (is_string($time) && $time) {
            $updated_at_arr = explode($spliter, $time);
            if (!isset($updated_at_arr[1]) && $range == true) {
                $updated_at_arr[1] = $updated_at_arr[0];//tìm trong ngày
            }
            if ($updated_at_arr && isset($updated_at_arr[0]) && isset($updated_at_arr[1])) {
                $timeStart = trim($updated_at_arr[0]);
                $timeEnd = trim($updated_at_arr[1]);
                if (Helper::validateDateTime($timeStart, 'd/m/Y') && Helper::validateDateTime($timeEnd, 'd/m/Y')) {
                    $timeStart = Helper::getMongoDate($timeStart, '/', true);
                    $timeEnd = Helper::getMongoDate($timeEnd, '/', false);
                    return [
                        'time_start' => $timeStart,
                        'time_end' => $timeEnd,
                    ];
                }
            }
        }
    }

    public static function compareValueTable($value1, $value2)
    {
        $type1 = gettype($value1);
        $type2 = gettype($value2);
        if ($type1 !== $type2) {
            return false;
        }
        if ($type1 === 'string' || $type1 === 'boolean' || $type1 == 'double' || $type1 == 'int') {
            return $value1 == $value2;
        }
        if ($type1 === 'array') {
            if (isset($value1['id'])) {
                return @$value1['id'] === @$value2['id'];
            }
            if (isset($value1['value'])) {
                return $value1['value'] == $value2['value'];
            }
            if (isset($value1[0]) && isset($value2[0])) {
                $isEqual = true;
                foreach ($value1 as $_value1) {
                    foreach ($value2 as $_value2) {
                        $isEqual = $isEqual && (@$_value1['id'] == @$_value2['id'] || @$_value1['value'] == @$_value2['value']);
                    }
                }
                return $isEqual;
            }
        }
        if ($type1 == 'object') {
            if (get_class($value1) === get_class($value2)) {
                return strval($value1) === strval($value2);
            } else {
                return false;
            }
        }
        return false;
    }

    public static function convertToMongoObject($obj)
    {
        if (is_array($obj)) {
            foreach ($obj as $key => $value) {
                if (is_array($value)) {
                    if (isset($value['$date'])) {
                        $obj[$key] = Helper::getMongoDateFromMiniSecond($value['$date']['$numberLong']);
                    } else if (isset($value['$oid'])) {
                        $obj[$key] = self::getMongoId($value['$oid']);
                    } else {
                        $obj[$key] = self::convertToMongoObject($value);

                    }

                }

            }
        }
        return $obj;
    }

    public static function convertToJsonOut($obj)
    {
        if (is_array($obj)) {
            foreach ($obj as $key => $value) {
                if (is_array($value)) {
                    if (isset($value['$date']['$numberLong'])) {
                        $obj[$key] = strval($value['$date']['$numberLong']);
                    } else if (isset($value['$oid'])) {
                        $obj[$key] = $value['$oid'];
                    }
                    else {
                        $obj[$key] = self::convertToJsonOut($value);
                    }

                }else if(Helper::isMongoId($value)){
                    $obj[$key] = strval($value);
                }else  if(Helper::isMongoDate($value)){
                    $obj[$key] = strval($value);
                }

            }
        }
        return $obj;
    }

    static public function parseCookie($cookie,$partner='Admicro')
    {
        if(is_string($cookie))
        {
            $cookie_arr = json_decode($cookie,true);

        }
        else{
            $cookie_arr = $cookie;

        }
        if(isset($cookie_arr['key']) && isset($cookie_arr['value']) && is_array($cookie_arr['value'])){
            $partner = isset($cookie_arr['value'][0]['partner']) ? $cookie_arr['value'][0]['partner'] : $partner;
            $save_cookie['payload'] = @$cookie_arr['value'][0]['payload'];
            $save_cookie['partner'] = $partner;
            $save_cookie['value'] =  trim(Helper::getVal(@$cookie_arr['value'][0]['value']));
            $save_cookie['domain'] = Helper::getVal(@$cookie_arr['value'][0]['domain']);
        }else{
            $save_cookie['payload'] = @$cookie_arr['payload'];
            $save_cookie['partner'] = $partner;
            $save_cookie['value'] =  trim(Helper::getVal(@$cookie_arr['value']));
            $save_cookie['domain'] = Helper::getVal(@$cookie_arr['domain']);
        }
        return $save_cookie;
    }

    static function convertEmail($email,$project_id='')
    {
        $email =  preg_replace('`[\-\+\.]+$`','',mb_strtolower(trim($email, "!@#$%^&*()_+-={}[]|\\'\":?>< \t\n\r\0\x0B.")));
        $email = preg_replace('`^[\-\+\.]+`','',$email);
        return $email;

    }

    static function  decodePhone($phone)
    {
        return $phone;
    }

    static function convertOption($value)
    {
        $value = preg_replace('!\s+!', ' ', $value);
        return trim(mb_strtolower($value));
    }

    static function getHostName($url)
    {
        if(!(strpos($url,'http:')===0)  && !(strpos($url,'https:')===0) )
        {
            $url = 'https://'.$url;
        }
        $host = @parse_url($url,PHP_URL_HOST);

        return $host;
    }

    static function getEmail($emails)
    {
        if(is_array($emails))
        {
            if(@$emails['0']['value']  && Helper::isEmail(@$emails['0']['value'] ))
            {
                return @$emails['0']['value'] ;
            }
        }
        else{
            if(is_string($emails) && Helper::isEmail($emails) )
            {
                return $emails;
            }
        }
        return '';
    }

    static function getPhone($phones)
    {
        if(is_array($phones))
        {
            if(@$phones['0']['value']  && Helper::isPhoneNumber($phones['0']['value'] ))
            {
                return $phones['0']['value'] ;
            }
        }
        else{
            if(is_string($phones) && Helper::isPhoneNumber($phones) )
            {
                return $phones;
            }
        }
        return '';
    }

    static public function getJsonError($msg, $keyerror = '', $data = [], $status = 0,$return_json = true)
    {
        $json['status'] = $status;
        $json['msg'] = $msg;
        $json['key'] = $keyerror;
        $json['data'] = $data;

        if($return_json)
        {
            return json_encode($json);

        }
        else{
            return ($json);

        }
    }

    static public function getJsonSuccess($msg, $data = [],$return_json = true)
    {
        $json['status'] = 1;
        $json['msg'] = $msg;
        $json['data'] = $data;

        if($return_json)
        {
            return json_encode($json);

        }
        else{
            return ($json);

        }
    }

    static function getOffset($page,$item_per_page)
    {
        $offset = (($page?$page:1) -1) * $item_per_page;
        return $offset;

    }

    static function safeUrl($url)
    {
        $url = str_replace('\'',urlencode('\''),$url);
        $url = str_replace('\\',urlencode('\\'),$url);
        return $url;

    }

    static function redirect($url,$code=307)
    {
        header("Location: ".$url,true,$code);
        die();

    }

    static function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    static function validateUrl($url){
        return  filter_var($url,FILTER_VALIDATE_URL);
    }
}
