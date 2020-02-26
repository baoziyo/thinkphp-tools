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
        ])->rule('uniqid')->move(self::getRootPath() . '/public/icon');
        if ($info) {
            $name = $info->getSaveName();
            try {
                FileTools::delete(self::getRootPath() . '/public/favicon.ico');
                FileTools::copy(self::getRootPath() . '/public/favicon.ico', self::getRootPath() . '/public/icon/' . $name, true);
            } catch (\Exception $exception) {
                FileTools::delete(self::getRootPath() . '/public/favicon.ico');
                FileTools::copy(self::getRootPath() . '/public/favicon.ico', self::getRootPath() . '/public/favicon.ico.old');
            }
            die(PromptTools::msg(1, 1, '修改成功!'));
        } else {
            die(PromptTools::msg(0, 1, $file->getError()));
        }
    }
}

