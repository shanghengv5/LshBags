<?php
/*
 * @Date: 2021-01-21 16:24:43
 * @LastEditors: LiShangHeng
 * @LastEditTime: 2021-01-22 17:04:38
 * @FilePath: /LshBags/src/Core/Console/EzCurd.php
 */

namespace Lsh\Core\Console;

use Illuminate\Console\Command;
use Lsh\Core\Console\Traits\EzCommand;
class EzCurd extends Command
{
    use EzCommand;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ez:curd {name} {--force=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * 命令前缀
     * @var 
     */
    protected $commandPrefix = 'ez:';
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

        // 调动服务
        $this->callEzCommand('controller');
        $this->callEzCommand('service');
        $this->callEzCommand('model');
    }

    /**
     * @name: LiShangHeng
     * @info: 调用对应命令
     */
    public function callEzCommand($type) {
        $this->call($this->commandPrefix.$type, [
            'name' => $this->name,
            '--force' => $this->force
        ]);
    }


    
}
