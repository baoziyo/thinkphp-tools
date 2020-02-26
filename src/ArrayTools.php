<?php

namespace think\tools;
class ArrayTools extends App
{
    /**
     * 将数组内的某个key提取出来
     * 2019/10/31 By:Baozi
     * @param $array
     * @param $key
     * @return array
     */
    public static function column($array, $key)
    {
        if (function_exists('array_column')) {
            return array_column($array, $key);
        }
        if (empty($array)) {
            return array();
        }
        $column = array();
        foreach ($array as $item) {
            if (isset($item[$key])) {
                $column[] = $item[$key];
            }
        }
        return $column;
    }

    /**
     * 判断数组键是否存在
     * 2019/10/31 By:Baozi
     * @param array $array
     * @param array $keys
     * @param bool $strictMode true 严格模式 判断是否为null|''|0
     * @param bool $tips true 提示某个字段不存在
     * @return array|bool
     */
    public static function requireds(array $array, array $keys, $strictMode = false, $tips = false)
    {
        foreach ($keys as $key) {
            if (!array_key_exists($key, $array)) {
                return $tips ? [false, $key] : false;
            }
            if ($strictMode && (is_null($array[$key]) || '' === $array[$key] || 0 === $array[$key])) {
                return $tips ? [false, $key] : false;
            }
        }
        return true;
    }

    /**
     * 添加字段 level
     * 2019/10/31 By:Baozi
     * @param $array
     * @param int $pid
     * @param int $level
     * @param int $toLavel
     * @return array
     */
    public static function arrayInlevel($array, $pid = 0, $level = 1, $toLavel = 999)
    {
        static $list = [];
        foreach ($array as $v) {
            if ($v['pId'] == $pid && $level <= $toLavel) {
                $v['level'] = $level;
                $list[] = $v;
                self::arrayInlevel($array, $v['id'], $level + 1, $toLavel);
            }
        }
        return $list;
    }

    /**
     * 子元素计算器
     * 2019/10/31 By:Baozi
     * @param $array
     * @param $pId
     * @return array
     */
    public static function childrenCount($array, $pId)
    {
        $counter = [];
        foreach ($array as $item) {
            $count = isset($counter[$item[$pId]]) ? $counter[$item[$pId]] : 0;
            $count++;
            $counter[$item[$pId]] = $count;
        }
        return $counter;
    }

    /**
     * 把元素插入到对应的父元素$childKeyName字段
     * 2019/10/31 By:Baozi
     * @param $parent
     * @param $pid
     * @param $child
     * @param $childKeyName
     * @return mixed
     */
    public static function childAppend($parent, $pid, $child, $childKeyName)
    {
        foreach ($parent as &$item) {
            if ($item['id'] == $pid) {
                if (!isset($item[$childKeyName])) {
                    $item[$childKeyName] = [];
                }
                $item[$childKeyName][] = $child;
            }
        }
        return $parent;
    }

    public static function index(array $array, $name)
    {
        $indexedArray = array();

        if (empty($array)) {
            return $indexedArray;
        }

        foreach ($array as $item) {
            if (isset($item[$name])) {
                $indexedArray[$item[$name]] = $item;
                continue;
            }
        }

        return $indexedArray;
    }
}
