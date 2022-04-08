<?php

use Carbon\Carbon;
use App\Model\TicketAndReview\Review\FullReview;


function convert_unicode($string)
{
    if (isset($string))
        return Normalizer::normalize($string, Normalizer::FORM_C);
}

function is_function($callable)
{
    return $callable && !is_string($callable) && !is_array($callable) && is_callable($callable);
}

function convert_unicode_utf8($str)
{
    if (!$str) return false;
    $unicode = array(
        'a' => array('á', 'à', 'ả', 'ã', 'ạ', 'ă', 'ắ', 'ặ', 'ằ', 'ẳ', 'ẵ', 'â', 'ấ', 'ầ', 'ẩ', 'ẫ', 'ậ'),
        'A' => array('Á', 'À', 'Ả', 'Ã', 'Ạ', 'Ă', 'Ắ', 'Ặ', 'Ằ', 'Ẳ', 'Ẵ', 'Â', 'Ấ', 'Ầ', 'Ẩ', 'Ẫ', 'Ậ'),
        'd' => array('đ'),
        'D' => array('Đ'),
        'e' => array('é', 'è', 'ẻ', 'ẽ', 'ẹ', 'ê', 'ế', 'ề', 'ể', 'ễ', 'ệ'),
        'E' => array('É', 'È', 'Ẻ', 'Ẽ', 'Ẹ', 'Ê', 'Ế', 'Ề', 'Ể', 'Ễ', 'Ệ'),
        'i' => array('í', 'ì', 'ỉ', 'ĩ', 'ị'),
        'I' => array('Í', 'Ì', 'Ỉ', 'Ĩ', 'Ị'),
        'o' => array('ó', 'ò', 'ỏ', 'õ', 'ọ', 'ô', 'ố', 'ồ', 'ổ', 'ỗ', 'ộ', 'õ', 'ớ', 'ờ', 'ở', 'ỡ', 'ợ'),
        'O' => array('Ó', 'Ò', 'Ỏ', 'Õ', 'Ọ', 'Ô', 'Ố', 'Ồ', 'Ổ', 'Ỗ', 'Ộ', 'Õ', 'Ớ', 'Ờ', 'Ở', 'Ỡ', 'Ợ'),
        'u' => array('ú', 'ù', 'ủ', 'ũ', 'ụ', 'ý', 'ứ', 'ừ', 'ử', 'ữ', 'ự'),
        'U' => array('Ú', 'Ù', 'Ủ', 'Ũ', 'Ụ', 'Ý', 'Ứ', 'Ừ', 'Ử', 'Ữ', 'Ự'),
        'y' => array('ý', 'ỳ', 'ỷ', 'ỹ', 'ỵ'),
        'Y' => array('Ý', 'Ỳ', 'Ỷ', 'Ỹ', 'Ỵ'),
    );
    foreach ($unicode as $nonUnicode => $uni) {
        foreach ($uni as $value)
            $str = str_replace($value, $nonUnicode, $str);
    }

    return preg_replace('/[\x00-\x1F\x7F]/', '', convert_unicode($str));
}

function convert_vi_to_en($str)
{

    $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", "a", $str);
    $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", "e", $str);
    $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", "i", $str);
    $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", "o", $str);
    $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", "u", $str);
    $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", "y", $str);
    $str = preg_replace("/(đ)/", "d", $str);
    $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", "A", $str);
    $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", "E", $str);
    $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", "I", $str);
    $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", "O", $str);
    $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", "U", $str);
    $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", "Y", $str);
    $str = preg_replace("/(Đ)/", "D", $str);
    $str = preg_replace("/[^\x9\xA\xD\x20-\x7F]/u", "", $str);

    return $str;
}

function is_array_not_object($data)
{
    $data = json_decode(json_encode($data));
    return is_array($data);
}

function is_json($data)
{
    return is_string($data) && is_array(json_decode($data, true));
}
