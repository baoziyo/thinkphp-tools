<?php

namespace think\tools;
/**
 * 信息返回类
 * Class PromptTools
 * @package app\common\tools
 */
class PromptTools extends App
{
    /**
     * 普通提示
     * 2019/10/31 By:Baozi
     * @param int $iconCode 提示code
     * @param int $status 状态
     * @param string $info 提示语
     * @param array $datas 数据
     * @return false|string
     */
    public static function msg($iconCode, $status, $info, $datas = [])
    {
        $temporaryArray = [
            'iconCode' => $iconCode,
            'status' => $status,
            'msg' => $info,
        ];
        if (!empty($datas)) {
            return json_encode(array_merge($temporaryArray, array('datas' => $datas)));
        }
        return json_encode($temporaryArray);
    }

    /**
     * layui返回table专用方法
     * 2019/10/31 By:Baozi
     * @param int $code 状态码
     * @param int $count 总数
     * @param array $data 数据
     * @return false|string
     */
    public static function datasMsg($code, $count, $data)
    {
        return json_encode([
            "code" => $code,
            "msg" => "",
            "count" => $count,
            "data" => $data,
        ]);
    }
}
