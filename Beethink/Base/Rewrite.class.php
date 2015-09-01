<?php
/**
 * 重写规则
 */
class Rewrite
{
    /**
     * 常规重写
     */
    static function init()
    {
        define('MODULE', isset($_GET['M']) ? ucfirst($_GET['M']) : 'Index');
        define('ACTION', isset($_GET['A']) ? strtolower($_GET['A']) : 'index');
        self::isAjax() ? self::ajaxModule() : self::normalModule();
    }

    static function ajaxModule()
    {
        $m = MODULE . 'Ajax';
        $fpath = AJAX_PATH . $m . '.class.php';

        self::call($m, ACTION, $fpath);
    }

    /**
     * normal 模式
     */
    static function normalModule()
    {
        $m = MODULE . 'Action';
        $fpath = ACTION_PATH . $m . '.class.php';

        self::call($m, ACTION, $fpath);
    }

    static function call($m, $a, $fpath)
    {
        /**
         * 缓存有效性检查
         * 如果缓存有效,则直接调用缓存文件
         */
        $fileName = RUNTIME_PATH . '' . md5($m . $a) . Conf::param('tpl_cache_suffix');

        if (self::checkCache($fileName))   //如果缓存有效
        {
            exit(file_get_contents($fileName));
        }
        if (is_file($fpath) && require($fpath)) {
            if (class_exists($m, false)) {
                if (method_exists($m, $a)) {
                    $m::$a();
                } else {
                    Error::halt(METHOD_NOTFOUND, $m . '::' . $a);
                }
            } else {
                Error::halt(CLASS_NOTFOUND, $m);
            }
        } else {
            Error::halt(FILE_NOTFOUND, $fpath);
        }
    }

    //判断是否是ajax请求
    static function isAjax()
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            if ('xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH']))
                //设置页面不缓存
                header('Expires: Fri, 25 Dec 1980 00:00:00 GMT');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
            header('Cache-Control: no-cache, must-revalidate');
            header('Pragma: no-cache');
            define('AJAX_REQUEST', 1);
            return true;
        }

        return false;
    }

    /* 检查当前文件是否有效 */
    static function checkCache($tmpFile)
    {
        if (!Conf::param('tpl_cache_on') || APP_DEBUG) {
            return false;
        }
        if (!is_file($tmpFile)) {
            return false;
        } else if (filectime($tmpFile) > filemtime($tmpFile)) //如果文件被修改
        {
            return false;
        } else if (time() > filemtime($tmpFile) + Conf::param('tpl_cache_time'))  //检测文件是否在有效期内
        {
            return false;
        }
        return true;
    }
}