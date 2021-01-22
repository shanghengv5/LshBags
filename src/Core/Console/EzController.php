<?php
/*
 * @Date: 2021-01-21 16:24:43
 * @LastEditors: LiShangHeng
 * @LastEditTime: 2021-01-22 17:01:31
 * @FilePath: /LshBags/src/Core/Console/EzController.php
 */

namespace Lsh\Core\Console;

use Illuminate\Console\Command;
use Lsh\Core\Console\Traits\EzCommand;
use Lsh\Core\Console\EzColumn;

class EzController extends Command
{
    use EzCommand;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ez:controller {name} {--force=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * 基本属性
     * @var 
     */
    protected $type = 'Controller';
    protected $namespaceString = 'Http\\Controllers';
    protected $dirString = 'Http/Controllers/';
    protected $needPrefix = 0;

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

        // diy part
        $this->replaceColumn();
        $this->replaceMoreNamespace();
        // div part end
        
        $this->replaceNamespace();        
        $this->saveStubContext();
        
    }

    /**
     * @name: LiShangHeng
     * @info: 替换字段信息
     * @param {*} &$stub
     * @return {*}
     */
    public function replaceColumn() {
        // 执行ez column
        $ezColumn = new EzColumn;
        $ezColumn->runByCommand($this->arguments);
        $this->stub = str_replace(['EzListRule', 'EzStoreRule', 'EzUpdateRule'], [$ezColumn->exportAndReturn('list'), $ezColumn->exportAndReturn('store'), $ezColumn->exportAndReturn('update'),], $this->stub);
    }

    /**
     * @name: LiShangHeng
     * @info: 替换命名空间
     * @param {*} &$stub
     * @return {*}
     */
    public function replaceMoreNamespace() {
        // 获取服务命名空间
        $this->namespaceString = 'Http\\Services';
        $servcieNameSpace = $this->getNamespace($this->nameArr, 0);
        // 替换对应信息
        $this->stub = str_replace(['EzServcieNamespace'], [$servcieNameSpace], $this->stub);
    }

    /**
     * The stub file path.
     *
     * @return string 
     */
    public function getStub() {
        return __DIR__.'/stubs/EzController.stub';
    }

    
    
}
