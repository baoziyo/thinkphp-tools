<?php

namespace think\tools;

class StrTools extends App
{
    protected static $snakeCache = [];
    protected static $camelCache = [];
    protected static $studlyCache = [];

    /**
     * 检查字符串中是否包含某些字符串
     * 2019/10/31 By:Baozi
     *
     * @param string       $haystack
     * @param string|array $needles
     *
     * @return bool
     */
    public static function contains($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ('' != $needle && false !== mb_strpos($haystack, $needle)) {
                return true;
            }
        }

        return false;
    }

    /**
     * 检查字符串是否以某些字符串结尾
     * 2019/10/31 By:Baozi
     *
     * @param string       $haystack
     * @param string|array $needles
     *
     * @return bool
     */
    public static function endsWith($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ((string) $needle === static::substr($haystack, -static::length($needle))) {
                return true;
            }
        }

        return false;
    }

    /**
     * 检查字符串是否以某些字符串开头
     * 2019/10/31 By:Baozi
     *
     * @param string       $haystack
     * @param string|array $needles
     *
     * @return bool
     */
    public static function startsWith($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ('' != $needle && 0 === mb_strpos($haystack, $needle)) {
                return true;
            }
        }

        return false;
    }

    /**
     * 获取指定长度的随机字母数字组合的字符串
     * 2019/10/31 By:Baozi
     *
     * @param int $length
     *
     * @return string
     */
    public static function random($length = 16)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return static::substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
    }

    /**
     * 字符串转小写
     * 2019/10/31 By:Baozi
     *
     * @param string $value
     *
     * @return string
     */
    public static function lower($value)
    {
        return mb_strtolower($value, 'UTF-8');
    }

    /**
     * 字符串转大写
     * 2019/10/31 By:Baozi
     *
     * @param string $value
     *
     * @return string
     */
    public static function upper($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    /**
     * 获取字符串的长度
     * 2019/10/31 By:Baozi
     *
     * @param string $value
     *
     * @return int
     */
    public static function length($value)
    {
        return mb_strlen($value);
    }

    /**
     * 截取字符串
     * 2019/10/31 By:Baozi
     *
     * @param string   $string
     * @param int      $start
     * @param int|null $length
     *
     * @return string
     */
    public static function substr($string, $start, $length = null)
    {
        return mb_substr($string, $start, $length, 'UTF-8');
    }

    /**
     * 驼峰转下划线
     * 2019/10/31 By:Baozi
     *
     * @param string $value
     * @param string $delimiter
     *
     * @return string
     */
    public static function snake($value, $delimiter = '_')
    {
        $key = $value;
        if (isset(static::$snakeCache[$key][$delimiter])) {
            return static::$snakeCache[$key][$delimiter];
        }
        if (!ctype_lower($value)) {
            $value = preg_replace('/\s+/u', '', $value);
            $value = static::lower(preg_replace('/(.)(?=[A-Z])/u', '$1'.$delimiter, $value));
        }

        return static::$snakeCache[$key][$delimiter] = $value;
    }

    /**
     * 下划线转驼峰(首字母小写)
     * 2019/10/31 By:Baozi
     *
     * @param string $value
     *
     * @return string
     */
    public static function camel($value)
    {
        if (isset(static::$camelCache[$value])) {
            return static::$camelCache[$value];
        }

        return static::$camelCache[$value] = lcfirst(static::studly($value));
    }

    /**
     * 下划线转驼峰(首字母大写)
     * 2019/10/31 By:Baozi
     *
     * @param string $value
     *
     * @return string
     */
    public static function studly($value)
    {
        $key = $value;
        if (isset(static::$studlyCache[$key])) {
            return static::$studlyCache[$key];
        }
        $value = ucwords(str_replace(['-', '_'], ' ', $value));

        return static::$studlyCache[$key] = str_replace(' ', '', $value);
    }

    /**
     * 转为首字母大写的标题格式
     * 2019/10/31 By:Baozi
     *
     * @param string $value
     *
     * @return string
     */
    public static function title($value)
    {
        return mb_convert_case($value, MB_CASE_TITLE, 'UTF-8');
    }
}
