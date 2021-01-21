<?php
/*
 * @Date: 2021-01-21 16:24:43
 * @LastEditors: LiShangHeng
 * @LastEditTime: 2021-01-21 18:23:05
 * @FilePath: /LshBags/src/Core/Console/EzController.php
 */

namespace Lsh\Core\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
class EzController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ez:controller {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成控制器代码';

    /**
     * 类型
     * @var 
     */
    protected $type = 'Controller';

    /**
     * 文件后缀
     * @var 
     */
    protected $fileExt = '.php';

    /**
     * 命名空间字符串
     * @var 
     */
    protected $namespaceString = 'Http\\Controllers';

    /**
     * 强制执行
     * @var 
     */
    protected $force = 0;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 处理命令获取的基本信息
        $arguments = $this->arguments();
        $name = $arguments['name'];

        $nameArr = explode('/', $name);
        // 驼峰法命名
        $name = $this->studlyName($nameArr);
        // 移除最后末尾的名字并且保存为类名
        $className = array_pop($nameArr);

        // 获取命名空间等基本信息
        $namespace = $this->getNamespace($nameArr);
        // 获取服务命名空间
        $this->namespaceString = 'Http\\Services';
        $servcieNameSpace = $this->getNamespace($nameArr, 0);

        // 获取stub文件
        $stubPath = $this->getStub();
        $stub = file_get_contents($stubPath);

        // 替换对应信息
        $stub = str_replace(['EzClassName', 'EzNamespace', 'EzServcieNamespace'], [$className, $namespace, $servcieNameSpace], $stub);
        
        // 保存到对应目录
        $filePath  = $this->getSavePath($name);
        // 判断是否存在
        if(!file_exists($filePath) || $this->force == 1) {
            $this->dirExistOrCreate(dirname($filePath));
            file_put_contents($filePath, $stub);
        } else {
            $this->warn($filePath.'文件已经存在');
        }
        
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
     * The stub file path.
     *
     * @return string 
     */
    public function getStub() {
        return __DIR__.'/stubs/EzController.stub';
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
        return $this->laravel->getNamespace();
    }

    /**
     * @name: LiShangHeng
     * @info: get the class namespace
     * @param array $name
     * @return {*}
     */
    public function getNamespace($name, $hasPrefix = 1) {
        $prefix = '';
        if($hasPrefix) {
            foreach($name as $value) {
                $prefix .= '\\' . Str::studly($value);
            }
        }
        return $this->rootNamespace() . $this->namespaceString . $prefix;
    }

    /**
     * Get the destination class save path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getSavePath($name) {
        return app_path('Http/Controllers/') . $name . $this->type . $this->fileExt;
    }
}
