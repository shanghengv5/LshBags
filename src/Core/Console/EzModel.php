<?php
/*
 * @Date: 2021-01-21 16:24:43
 * @LastEditors: LiShangHeng
 * @LastEditTime: 2021-01-23 19:04:28
 * @FilePath: /LshBags/src/Core/Console/EzModel.php
 */

namespace Lsh\Core\Console;

use Illuminate\Console\Command;

use Lsh\Core\Console\Traits\EzCommand;
class EzModel extends Command
{
    use EzCommand;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ez:model {name} {--force=0}';

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
        
        $this->replaceMedia();
        $this->replaceNamespace();
        $this->saveStubContext();
    }

    /**
     * @name: LiShangHeng
     * @info: 替换命名空间
     */
    public function replaceMoreNamespace() {
        $ezTableName = $this->snakeName($this->className);
        $this->stub = str_replace(['EzTableName'], [$ezTableName], $this->stub);
    }

    /**
     * @name: LiShangHeng
     * @info: 替换media
     */
    public function replaceMedia() {
        $media = new EzModelMedia;
        $ezMedia = $media->runByCommand($this->arguments);
        // var_dump($ezMedia);
        $this->stub = str_replace(['EzAutoMedia'], [$ezMedia], $this->stub);
    }

    /**
     * The stub file path.
     *
     * @return string 
     */
    public function getStub() {
        return __DIR__.'/stubs/EzModel.stub';
    }

}
