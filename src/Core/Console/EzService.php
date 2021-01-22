<?php
/*
 * @Date: 2021-01-21 16:24:43
 * @LastEditors: LiShangHeng
 * @LastEditTime: 2021-01-22 17:02:23
 * @FilePath: /LshBags/src/Core/Console/EzService.php
 */

namespace Lsh\Core\Console;

use Illuminate\Console\Command;
use Lsh\Core\Console\Traits\EzCommand;
class EzService extends Command
{
    use EzCommand;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ez:service {name} {--force=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    

    /**
     * 命名空间字符串
     * @var 
     */
    protected $namespaceString = 'Http\\Services';
    protected $dirString = 'Http/Services/';
    protected $type = 'Service';
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

        // replace by yourself
        $this->replaceMoreNamespace();
        
        $this->replaceNamespace();
        $this->saveStubContext();
    }


    /**
     * @name: LiShangHeng
     * @info: 替换命名空间
     */
    public function replaceMoreNamespace() {
        $this->namespaceString = 'Model';
        $modelNamespace = $this->getNamespace($this->nameArr, 0);
        $this->stub = str_replace(['EzModelNamespace'], [$modelNamespace], $this->stub);
    }

    /**
     * The stub file path.
     *
     * @return string 
     */
    public function getStub() {
        return __DIR__.'/stubs/EzService.stub';
    }

}
