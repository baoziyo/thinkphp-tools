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
            'ext' => 'jpg,png,gif,ico,jpeg',
            'size' => 1024 * 500,
        ])->rule('uniqid')->move(self::getRootPath().'/public/icon');
        if ($info) {
            $name = $info->getSaveName();
            try {
                FileTools::delete(self::getRootPath().'/public/favicon.ico');
                FileTools::copy(self::getRootPath().'/public/favicon.ico', self::getRootPath().'/public/icon/'.$name, true);
            } catch (\Exception $exception) {
                FileTools::delete(self::getRootPath().'/public/favicon.ico');
                FileTools::copy(self::getRootPath().'/public/favicon.ico', self::getRootPath().'/public/favicon.ico.old');
            }

            return PromptTools::msg(1, 1, '修改成功!');
        } else {
            return PromptTools::msg(0, 1, $file->getError());
        }
    }

    /**
     * 上传单张图片
     *
     * @param int   $width
     * @param int   $height
     * @param array $validate
     *
     * @return false|string
     */
    public static function uploadImg($width = 500, $height = 300, $validate = [])
    {
        $file = request()->file('file');
        $upload_path = getRootPath().'/public/uploads';
        $info = $file->validate($validate)->move($upload_path);
        $image = \think\Image::open($upload_path.'/'.$info->getSaveName());
        $image->thumb($width, $height)->save($upload_path.'/'.$info->getSaveName());
        if ($info) {
            return PromptTools::msg(1, 1, '上传成功!', ['src' => '/uploads/'.$info->getSaveName()]);
        } else {
            return PromptTools::msg(0, 1, '上传失败!');
        }
    }

    /**
     * 上传多张图片
     *
     * @param int   $width
     * @param int   $height
     * @param array $validate
     *
     * @return false|string
     */
    public static function uploadImgs($width = 500, $height = 300, $validate = [])
    {
        $files = request()->file('file');
        if (empty($files)) {
            return PromptTools::msg(0, 1, '没有上传的图片!');
        }
        $srcs = [];
        foreach ($files as $file) {
            $upload_path = getRootPath().'/public/uploads';
            $info = $file->validate($validate)->move($upload_path);
            $image = \think\Image::open($upload_path.'/'.$info->getSaveName());
            $image->thumb($width, $height)->save($upload_path.'/'.$info->getSaveName());
            if ($info) {
                $srcs[] = '/uploads/'.$info->getSaveName();
            } else {
                return PromptTools::msg(0, 1, '上传失败!');
            }
        }

        return PromptTools::msg(1, 1, '上传成功!', ['src' => $srcs]);
    }
}
