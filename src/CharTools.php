<?php

namespace think\tools;

class CharTools extends App
{
    /**
     * 随机生成字符串
     * 2019/10/31 By:Baozi
     *
     * @param $len
     *
     * @return string
     */
    public static function getRandChar($len)
    {
        $a = range('a', 'z');
        $b = range('A', 'Z');
        $c = range('0', '9');
        $chars = array_merge($a, $b, $c);
        $charslen = count($chars) - 1;
        shuffle($chars);
        $output = '';
        for ($i = 0; $i < $len; ++$i) {
            $output .= $chars[mt_rand(0, $charslen)];
        }

        return $output;
    }

    /**
     * 生成GUID
     * 2019/10/31 By:Baozi
     *
     * @return string
     */
    public static function getGuid()
    {
        mt_srand((float) microtime() * 10000);
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $uuid = substr($charid, 0, 8).substr($charid, 8, 4).substr($charid, 12, 4).substr($charid, 16, 4).substr($charid, 20, 12);

        return strtolower($uuid);
    }
}
