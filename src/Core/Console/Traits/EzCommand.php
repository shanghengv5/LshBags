<?php
/*
 * @Date: 2021-01-22 14:47:26
 * @LastEditors: LiShangHeng
 * @LastEditTime: 2021-02-24 10:04:23
 * @FilePath: /LshBags/src/Core/Console/Traits/EzCommand.php
 */

namespace Lsh\Core\Console\Traits;
use Illuminate\Support\Str;

Trait EzCommand {
    /**
     * 基本属性
     * @var 
     */
    protected $arguments;
    protected $name;
    protected $className;
    protected $config;
    protected $configSetType = [
        'service', 'model', 'controller'
    ];
    protected $force = 0;
    protected $fileExt = '.php';
    
    /**
     * @name: LiShangHeng
     * @info: 处理命令获取的基本信息
     */
    public function getCommandArguments($data = []) {
        // 初始化配置
        $this->initEzConfig();
        // 初始化变量
        if(count($data) == 0) {
            $this->arguments = $this->arguments();
            $this->name = $this->arguments['name'];
            if($this->hasOption('force')) {
                $this->force = $this->option('force');
            }
        } else {
            $this->arguments = $data;
            $this->name = $data['name'];
        }
    }

    /**
     * @name: LiShangHeng
     * @info: 获取ezbags配置
     */
    public function initEzConfig() {
        $this->config = config('ezbags')['Console'];
        // 设置命名空间
        if(in_array($this->type, $this->configSetType)) {
            $this->namespaceString = $this->config['namepace'][Str::lower($this->type)]; 
        }
    }

    /**
     * @name: LiShangHeng
     * @info: 处理参数
     * @param {*}
     * @return {*}
     */
    public function dealNameArguments()
    {
        $this->nameArr = explode('/', $this->name);
        // 驼峰法命名
        $this->name = $this->studlyName($this->nameArr);
        // 移除最后末尾的名字并且保存为类名
        $this->className = array_pop($this->nameArr);
        // 获取命名空间等基本信息
        $this->namespace = $this->getNamespace($this->nameArr);
    }

    /**
     * @name: LiShangHeng
     * @info: 创建目录
     * @param {*} $path
     * @return {*}
     */
    public function dirExistOrCreate($path) {
        if(file_exists($path) == false) {

            if(mkdir($path, 0777, true) == false) {
                $result = false;
            } else if(chmod($path, 0777) == false) {
                $result = false;
            }
        }
        return $result;
    }

    /**
     * @name: LiShangHeng
     * @info: 获取蛇形字符串
     */
    public function snakeName($str) {
        return Str::snake($str);
    }

    /**
     * @name: LiShangHeng
     * @info: 不允许名字小写
     * @param {*}
     * @return {*}
     */
    public function studlyName(&$nameArr) {
        array_walk($nameArr, function(&$value) {
            $value = Str::studly($value);
        });
        return implode('/', $nameArr);
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return 'App\\';
    }

    /**
     * @name: LiShangHeng
     * @info: get the class namespace
     * @param array $name
     * @return {*}
     */
    public function getNamespace($name, $needPre = 1) {
        $prefix = '';
        if($this->needPrefix == 1 && $needPre) {
            foreach($name as $value) {
                $prefix .= '\\' . Str::studly($value);
            }
        }
        return $this->rootNamespace() . $this->namespaceString . $prefix;
    }

    /**
     * @name: LiShangHeng
     * @info: 获取stub文件内容
     * @param {*}
     * @return {*}
     */
    public function getStubContext() {
        // 获取stub文件
        $stubPath = $this->getStub();
        $this->stub = file_get_contents($stubPath);
    }

    /**
     * Get the destination class save path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getSavePath() {
        if($this->needPrefix) {
            return app_path($this->dirString)  . $this->name . $this->type . $this->fileExt;
        }
        return app_path($this->dirString)  . $this->className . $this->type . $this->fileExt;
    }

    /**
     * @name: LiShangHeng
     * @info: 保存文件
     */
    public function saveStubContext() {
        // 保存到对应目录
        $filePath  = $this->getSavePath();
        // 判断是否存在
        if(!file_exists($filePath) || $this->force == 1) {
            $this->dirExistOrCreate(dirname($filePath));
            file_put_contents($filePath, $this->stub);
        } else {
            $this->warn($filePath.'文件已经存在');
        }
    }

    /**
     * @name: LiShangHeng
     * @info: 替换命名空间
     * @param {*} &$stub
     * @return {*}
     */
    public function replaceNamespace() {
       // 替换对应信息
        $this->stub = str_replace(['EzClassName', 'EzNamespace', ], [$this->className, $this->namespace], $this->stub);
    }
}