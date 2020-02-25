<?php

namespace think\tools;

class TreeTools extends App
{
    /**
     * 生成菜单树
     * 2019/10/31 By:Baozi
     * @param $array
     * @param string $pidName
     * @param string $childKeyName
     * @return array|mixed
     */
    public static function menuTree($array, $pidName = 'pId', $childKeyName = 'children')
    {
        $counter = ArrayTools::childrenCount($array, $pidName);
        if (!isset($counter[0]) || $counter[0] == 0) {
            return $array;
        }
        $tree = [];
        while (isset($counter[0]) && $counter[0] > 0) {
            $temp = array_shift($array);
            if (isset($counter[$temp['id']]) && $counter[$temp['id']] > 0) {
                array_push($array, $temp);
            } else {
                if ($temp[$pidName] == 0) {
                    $tree[] = $temp;
                } else {
                    $array = ArrayTools::childAppend($array, $temp[$pidName], $temp, $childKeyName);
                }
            }
            $counter = ArrayTools::childrenCount($array, $pidName);
        }
        return $tree;
    }
}
