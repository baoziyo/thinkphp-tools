<?php

namespace think\tools;

class App
{
    public static function getRootPath()
    {
        return str_replace('\\', '/', realpath(dirname(__FILE__).'/../../../../'));
    }
}
