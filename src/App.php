<?php

namespace think\tools;
class App
{
    public function getRootPath()
    {
        return str_replace('\\','/',realpath(dirname(__FILE__).'/../../../../'));
    }
}
