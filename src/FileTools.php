<?php

namespace think\tools;

class FileTools extends App
{
    /**
     * 修改thinkphp下config文件内容
     * 2019/10/31 By:Baozi
     * @param string $filename 文件名
     * @param string $data 修改数组
     * @param bool $path 是否为模块下config
     * @return bool|int|mixed
     */
    public static function configWite($filename, $data = "", $path = true)
    {
        if ('' == $filename) {
            return false;
        }
        if ($path) {
            $path = getRootPath() . '/config/';
        } else {
            $path = env('app_path');
            $module = request()->module();
            if ($module) $path = env('module_path') . 'config';
        }
        $file = $path . $filename . ".php";

        if (!self::has($file)) return false;

        if ($data && is_array($data)) {
            $data = $arrayData = array_merge(include($file), $data);
            $data = preg_replace(['/array \(/', '/\)/i'], ['[', ']'], $data);
            $data = var_export($data, true);
            self::write($file, '<?php ' . PHP_EOL . 'return ' . $data . ';');
        }

        return $arrayData ?? false;
    }

    /**
     * has 判断文件是否存在
     * 2019/10/31 By:Baozi
     * @param string $filename 文件名
     * @return  bool
     */
    public static function has($filename = '')
    {
        return is_file($filename);
    }

    /**
     * hasFolder 判断文件夹是否存在
     * 2019/10/31 By:Baozi
     * @param string $dir 目录
     * @return  bool
     */
    public static function hasFolder($dir = '')
    {
        return is_dir($dir);
    }

    /**
     * createFolder 创建目录
     * 2019/10/31 By:Baozi
     * @param string $dir 目录
     * @return  bool
     */
    public static function createFolder($dir = '')
    {
        if (!$dir || $dir == "." || $dir == "./") return false;
        if (!self::hasFolder($dir)) {
            return mkdir($dir, 0777, true);
        }
        return false;
    }

    /**
     * write 文件写入
     * 2019/10/31 By:Baozi
     * @param string $filename 文件路径
     * @param string $data 文件写入的内容
     * @return  bool
     */
    public static function write($filename = '', $data = '')
    {
        $pathinfo = pathinfo($filename);
        $dir = $pathinfo['dirname'];

        if (!self::hasFolder($dir)) {
            mkdir($dir, 0777, true);
        }
        return file_put_contents($filename, $data);
    }

    /**
     * read 文件读取
     * 2019/10/31 By:Baozi
     * @param string $filename 文件路径
     * @return  string
     */
    public static function read($filename = '')
    {
        if (self::has($filename)) {
            $content = file_get_contents($filename);
            return $content;
        }
        return false;
    }

    /**
     * readArray 读取文件内容，将读取的内容放入数组中，每个数组元素为文件的一行，内容包括换行
     * 2019/10/31 By:Baozi
     * @param string $filename 文件路径
     * @return  array|bool
     */
    public static function readArray($filename = "")
    {
        if (self::has($filename)) {
            return file($filename);
        }
        return false;
    }

    /**
     * delete 文件删除
     * 2019/10/31 By:Baozi
     * @param string $filename 文件路径
     * @return  array
     */
    public static function delete($filename = "")
    {
        if (self::has($filename)) {
            //chmod($filename , 0777);
            return @unlink($filename);
        }
    }

    /**
     * deleteFolder 文件夹删除
     * 2019/10/31 By:Baozi
     * @param string $dir
     * @return bool
     */
    public static function deleteFolder($dir = "")
    {
        //先删除目录下的文件：
        if (!self::hasFolder($dir)) return false;

        $dh = opendir($dir);
        while ($file = readdir($dh)) {
            if ($file != "." && $file != "..") {
                $fullPath = $dir . DIRECTORY_SEPARATOR . $file;
                if (!is_dir($fullPath)) {
                    @unlink($fullPath);
                } else {
                    self::deleteFolder($fullPath);
                }
            }
        }

        closedir($dh);
        //删除当前文件夹：
        if (rmdir($dir)) {
            return true;
        }
        return false;
    }


    /**
     * copy 拷贝文件或目录
     * 2019/10/31 By:Baozi
     * @param string $new 拷贝目录或者文件
     * @param string $old 目标目录或者文件
     * @param bool $delete true为删除拷贝目录 false为不删除拷贝目录
     * @return bool
     */
    public static function copy($new, $old, $delete = false)
    {
        $is = false;
        //if(substr($new,0,1) == "/") $new = substr($new,1,strlen($new)-1);
        //if(substr($old,0,1) == "/") $old = substr($old,1,strlen($old)-1);

        if (!file_exists($old) && !is_dir($old)) return false;
        $pathInfoNew = pathinfo($new);
        $path = isset($pathInfoNew['extension']) ? $pathInfoNew['dirname'] : $new;
        if (!is_dir($path)) mkdir($path, 0777, true);

        if (is_file($old)) {
            if (!isset($pathInfoNew['extension'])) {
                $pathInfo = pathinfo($old);
                $is = copy($old, $new . DIRECTORY_SEPARATOR . $pathInfo['basename']);
            } else {
                $is = copy($old, $new);
            }
            if ($delete == true) {
                self::delete($old);
            }
        } else {
            if (!isset($pathInfoNew['extension'])) {
                $dir = scandir($old);
                foreach ($dir as $filename) {
                    if (!in_array($filename, array('.', '..'))) {
                        if (is_dir($old . DIRECTORY_SEPARATOR . $filename)) {
                            $is = self::copy($new . DIRECTORY_SEPARATOR . $filename, $old . DIRECTORY_SEPARATOR . $filename, $delete);
                            if (!$is) return false;
                            continue;
                        } else {
                            $is = copy($old . DIRECTORY_SEPARATOR . $filename, $new . DIRECTORY_SEPARATOR . $filename);
                        }
                    }
                }

            }
        }
        return $is;
    }

    /**
     * findOwn 获取目录下的所有文件路径 包括子目录的文件
     * 2019/10/31 By:Baozi
     * @param string $dir 文件路径
     * @return array
     */
    public static function findOwn($dir = '')
    {
        $result = array();
        $handle = opendir($dir);
        if ($handle) {
            while (($file = readdir($handle)) !== false) {
                if ($file != '.' && $file != '..') {
                    $cur_path = $dir . DIRECTORY_SEPARATOR . $file;
                    if (is_dir($cur_path)) {
                        $files = self::findOwn($cur_path);
                        if ($files) $result = $result ? array_merge($result, $files) : $files;
                    } else {
                        $result[] = $cur_path;
                    }
                }
            }
            closedir($handle);
        }
        return $result;
    }
}
