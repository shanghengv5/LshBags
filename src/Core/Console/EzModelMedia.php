<?php
/*
 * @Date: 2021-01-21 16:24:43
 * @LastEditors: LiShangHeng
 * @LastEditTime: 2021-01-23 19:02:13
 * @FilePath: /LshBags/src/Core/Console/EzModelMedia.php
 */

namespace Lsh\Core\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Lsh\Core\Console\Traits\EzCommand;
class EzModelMedia extends Command
{
    use EzCommand;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ez:modelmedia {name} {--force=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * 类型
     * @var 
     */
    protected $type = 'Model';

    /**
     * 命名空间字符串
     * @var 
     */
    protected $namespaceString = 'Model';
    protected $dirString = 'Model/';
    protected $needPrefix = 0;
    protected $mediaInfo = "";


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
        $this->getCommandArguments();
        $this->dealNameArguments();   
        
        $this->getStubContext();

        // replace by yourself
        $this->replaceMoreNamespace();
        
        $this->replaceNamespace();
        // $this->saveStubContext();
        $this->exportInfo();
    }

    /**
     * @name: LiShangHeng
     * @info: 打印可用信息
     */
    public function exportInfo($isString = false) {
        return var_dump($this->mediaInfo, $isString);
    }

    /**
     * @name: LiShangHeng
     * @info: 通过其他命令执行
     * @param array $data
     * @return {*}
     */
    public function runByCommand($data) {
        $this->getCommandArguments($data);
        $this->dealNameArguments();
        
        $this->getStubContext();
        $this->replaceMoreNamespace();
        
        $this->replaceNamespace();

        return $this->mediaInfo;
    }

    /**
     * @name: LiShangHeng
     * @info: 替换命名空间
     */
    public function replaceMoreNamespace() {
        
        $ezColumn = new EzColumn;
        $ezColumn->runByCommand($this->arguments);
        $modelMap = $ezColumn->exportAndReturn('model', false);
        foreach($modelMap as $key => $value) {
            if($value == 'media') {
                $nameArr = [$key];
                $this->mediaInfo .= str_replace(['EzMedia', 'EzNamespace', 'EzSnake'], [$this->studlyName($nameArr), $this->namespace, $key], $this->stub);
            }
        }
        
    }


    /**
     * The stub file path.
     *
     * @return string 
     */
    public function getStub() {
        return __DIR__.'/stubs/EzModelMedia.stub';
    }

}
