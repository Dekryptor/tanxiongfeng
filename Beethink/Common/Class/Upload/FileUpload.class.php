<?php
class FileUpload {
    /**
     * 默认上传配置
     * @var array
     */
    private static $config = array(
        'mimes'         =>  array(), //允许上传的文件MiMe类型
        'maxSize'       =>  0, //上传的文件大小限制 (0-不做限制)
        'exts'          =>  array(), //允许上传的文件后缀
        'autoSub'       =>  true, //自动子目录保存文件
        'subName'       =>  array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        'rootPath'      =>  './Uploads/', //保存根路径
        'savePath'      =>  '', //保存路径
        'saveName'      =>  array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
        'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
        'replace'       =>  false, //存在同名是否覆盖
        'hash'          =>  true, //是否生成hash编码
        'callback'      =>  false, //检测文件是否存在回调，如果存在返回文件信息数组
        'driver'        =>  '', // 文件上传驱动
        'driverConfig'  =>  array(), // 上传驱动配置
    );

    /**
     * 上传错误信息
     * @var string
     */
    private static $error = ''; //上传错误信息

    /**
     * 上传驱动实例
     * @var Object
     */
    private static $uploader;

    /**
     * 初始化文件上传配置信息
     * @param array  $config 配置
     * @param string $driver 要使用的上传驱动 LOCAL-本地上传驱动，FTP-FTP上传驱动
     */
    public function init($config=array(),$driver='',$driverConfig=null)
    {
        /* 获取配置 */
        self::$config   =   array_merge(self::$config, $config);

        /* 设置上传驱动 */
        self::setDriver($driver, $driverConfig);
        self::$config['mimes'] = array_map('strtolower', self::mimes);
        self::$config['exts'] = array_map('strtolower', self::exts);
    }
    /**
     * 获取最后一次上传错误信息
     * @return string 错误信息
     */
    public function getError(){
        return self::$error;
    }

    /**
     * 上传单个文件
     * @param  array  $file 文件数组
     * @return array        上传成功后的文件信息
     */
    public function uploadOne($file){
        $info = self::upload(array($file));
        return $info ? $info[0] : $info;
    }

    /**
     * 上传文件
     * @param 文件信息数组 $files ，通常是 $_FILES数组
     */
    public function upload($files='') {
        if('' === $files){
            $files  =   $_FILES;
        }
        if(empty($files)){
            self::$error = '没有上传的文件！';
            return false;
        }

        /* 检测上传根目录 */
        if(!self::$uploader->checkRootPath(self::$rootPath)){
            self::$error = self::$uploader->getError();
            return false;
        }

        /* 检查上传目录 */
        if(!self::$uploader->checkSavePath(self::savePath)){
            self::$error = self::$uploader->getError();
            return false;
        }

        /* 逐个检测并上传文件 */
        $info    =  array();
        if(function_exists('finfo_open')){
            $finfo   =  finfo_open ( FILEINFO_MIME_TYPE );
        }
        // 对上传文件数组信息处理
        $files   =  self::dealFiles($files);
        foreach ($files as $key => $file) {
            $file['name']  = strip_tags($file['name']);
            if(!isset($file['key']))   $file['key']    =   $key;
            /* 通过扩展获取文件类型，可解决FLASH上传$FILES数组返回文件类型错误的问题 */
            if(isset($finfo)){
                $file['type']   =   finfo_file ( $finfo ,  $file['tmp_name'] );
            }

            /* 获取上传文件后缀，允许上传无后缀文件 */
            $file['ext']    =   pathinfo($file['name'], PATHINFO_EXTENSION);

            /* 文件上传检测 */
            if (!self::check($file)){
                continue;
            }

            /* 获取文件hash */
            if(self::hash){
                $file['md5']  = md5_file($file['tmp_name']);
                $file['sha1'] = sha1_file($file['tmp_name']);
            }

            /* 调用回调函数检测文件是否存在 */
            $data = call_user_func(self::callback, $file);
            if( self::callback && $data ){
                if ( file_exists('.'.$data['path'])  ) {
                    $info[$key] = $data;
                    continue;
                }elseif(self::removeTrash){
                    call_user_func(self::removeTrash,$data);//删除垃圾据
                }
            }

            /* 生成保存文件名 */
            $savename = self::getSaveName($file);
            if(false == $savename){
                continue;
            } else {
                $file['savename'] = $savename;
            }

            /* 检测并创建子目录 */
            $subpath = self::getSubPath($file['name']);
            if(false === $subpath){
                continue;
            } else {
                $file['savepath'] = self::savePath . $subpath;
            }

            /* 对图像文件进行严格检测 */
            $ext = strtolower($file['ext']);
            if(in_array($ext, array('gif','jpg','jpeg','bmp','png','swf'))) {
                $imginfo = getimagesize($file['tmp_name']);
                if(empty($imginfo) || ($ext == 'gif' && empty($imginfo['bits']))){
                    self::$error = '非法图像文件！';
                    continue;
                }
            }

            /* 保存文件 并记录保存成功的文件 */
            if (self::$uploader->save($file,self::replace)) {
                unset($file['error'], $file['tmp_name']);
                $info[$key] = $file;
            } else {
                self::$error = self::$uploader->getError();
            }
        }
        if(isset($finfo)){
            finfo_close($finfo);
        }
        return empty($info) ? false : $info;
    }

    /**
     * 转换上传文件数组变量为正确的方式
     * @access private static
     * @param array $files  上传的文件变量
     * @return array
     */
    private static function dealFiles($files) {
        $fileArray  = array();
        $n          = 0;
        foreach ($files as $key=>$file){
            if(is_array($file['name'])) {
                $keys       =   array_keys($file);
                $count      =   count($file['name']);
                for ($i=0; $i<$count; $i++) {
                    $fileArray[$n]['key'] = $key;
                    foreach ($keys as $_key){
                        $fileArray[$n][$_key] = $file[$_key][$i];
                    }
                    $n++;
                }
            }else{
                $fileArray = $files;
                break;
            }
        }
        return $fileArray;
    }

    /**
     * 设置上传驱动
     * @param string $driver 驱动名称
     * @param array $config 驱动配置
     */
    private static function setDriver($driver = null, $config = null){
        $class=THINK_PATH.'Common/Class/Upload/Driver/'.ucfirst(strtolower($driver)).'.class.php';
        self::$uploader = new $class($config);
        if(!self::$uploader){
            Error::halt(FILE_NOTFOUND,'不存在上传驱动文件:'.$class);
        }
    }

    /**
     * 检查上传的文件
     * @param array $file 文件信息
     */
    private static function check($file) {
        /* 文件上传失败，捕获错误代码 */
        if ($file['error']) {
            self::error($file['error']);
            return false;
        }

        /* 无效上传 */
        if (empty($file['name'])){
            self::$error = '未知上传错误！';
        }

        /* 检查是否合法上传 */
        if (!is_uploaded_file($file['tmp_name'])) {
            self::$error = '非法上传文件！';
            return false;
        }

        /* 检查文件大小 */
        if (!self::checkSize($file['size'])) {
            self::$error = '上传文件大小不符！';
            return false;
        }

        /* 检查文件Mime类型 */
        //TODO:FLASH上传的文件获取到的mime类型都为application/octet-stream
        if (!self::checkMime($file['type'])) {
            self::$error = '上传文件MIME类型不允许！';
            return false;
        }

        /* 检查文件后缀 */
        if (!self::checkExt($file['ext'])) {
            self::$error = '上传文件后缀不允许';
            return false;
        }

        /* 通过检测 */
        return true;
    }
    /**
     * 获取错误代码信息
     * @param string $errorNo  错误号
     */
    private static function error($errorNo) {
        switch ($errorNo) {
            case 1:
                self::$error = '上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值！';
                break;
            case 2:
                self::$error = '上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值！';
                break;
            case 3:
                self::$error = '文件只有部分被上传！';
                break;
            case 4:
                self::$error = '没有文件被上传！';
                break;
            case 6:
                self::$error = '找不到临时文件夹！';
                break;
            case 7:
                self::$error = '文件写入失败！';
                break;
            default:
                self::$error = '未知上传错误！';
        }
    }

    /**
     * 检查文件大小是否合法
     * @param integer $size 数据
     */
    private static function checkSize($size) {
        return !($size > self::maxSize) || (0 == self::maxSize);
    }

    /**
     * 检查上传的文件MIME类型是否合法
     * @param string $mime 数据
     */
    private static function checkMime($mime) {
        return empty(self::$config['mimes']) ? true : in_array(strtolower($mime), self::mimes);
    }

    /**
     * 检查上传的文件后缀是否合法
     * @param string $ext 后缀
     */
    private static function checkExt($ext) {
        return empty(self::$config['exts']) ? true : in_array(strtolower($ext), self::exts);
    }

    /**
     * 根据上传文件命名规则取得保存文件名
     * @param string $file 文件信息
     */
    private static function getSaveName($file) {
        $rule = self::saveName;
        if (empty($rule)) { //保持文件名不变
            /* 解决pathinfo中文文件名BUG */
            $filename = substr(pathinfo("_{$file['name']}", PATHINFO_FILENAME), 1);
            $savename = $filename;
        } else {
            $savename = self::getName($rule, $file['name']);
            if(empty($savename)){
                self::$error = '文件命名规则错误！';
                return false;
            }
        }

        /* 文件保存后缀，支持强制更改文件后缀 */
        $ext = empty(self::$config['saveExt']) ? $file['ext'] : self::saveExt;

        return $savename . '.' . $ext;
    }

    /**
     * 获取子目录的名称
     * @param array $file  上传的文件信息
     */
    private static function getSubPath($filename) {
        $subpath = '';
        $rule    = self::subName;
        if (self::autoSub && !empty($rule)) {
            $subpath = self::getName($rule, $filename) . '/';

            if(!empty($subpath) && !self::$uploader->mkdir(self::savePath . $subpath)){
                self::$error = self::$uploader->getError();
                return false;
            }
        }
        return $subpath;
    }

    /**
     * 根据指定的规则获取文件或目录名称
     * @param  array  $rule     规则
     * @param  string $filename 原文件名
     * @return string           文件或目录名称
     */
    private static function getName($rule, $filename){
        $name = '';
        if(is_array($rule)){ //数组规则
            $func     = $rule[0];
            $param    = (array)$rule[1];
            foreach ($param as &$value) {
                $value = str_replace('__FILE__', $filename, $value);
            }
            $name = call_user_func_array($func, $param);
        } elseif (is_string($rule)){ //字符串规则
            if(function_exists($rule)){
                $name = call_user_func($rule);
            } else {
                $name = $rule;
            }
        }
        return $name;
    }

}