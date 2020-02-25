<?php

namespace think\tools;

class UploadTools extends App
{
    /**
     * 上传ico
     * 2019/10/31 By:Baozi
     */
    public static function ico()
    {
        $file = request()->file('file');
        $info = $file->validate([
            'ext' => 'jpg,png,gif,ico',
            'size' => 1024 * 5
        ])->rule('uniqid')->move(getRootPath() . '/public/icon');
        if ($info) {
            $name = $info->getSaveName();
            try {
                FileTools::delete(getRootPath() . '/public/favicon.ico');
                FileTools::copy(getRootPath() . '/public/favicon.ico', getRootPath() . '/public/icon/' . $name, true);
            } catch (\Exception $exception) {
                FileTools::delete(getRootPath() . '/public/favicon.ico');
                FileTools::copy(getRootPath() . '/public/favicon.ico', getRootPath() . '/public/favicon.ico.old');
            }
            die(PromptTools::msg(1, 1, '修改成功!'));
        } else {
            die(PromptTools::msg(0, 1, $file->getError()));
        }
    }
}

