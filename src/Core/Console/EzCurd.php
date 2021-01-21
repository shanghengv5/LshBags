<?php
/*
 * @Date: 2021-01-21 16:24:43
 * @LastEditors: LiShangHeng
 * @LastEditTime: 2021-01-21 18:22:56
 * @FilePath: /LshBags/src/Core/Console/EzCurd.php
 */

namespace Lsh\Core\Console;

use Illuminate\Console\Command;
class EzCurd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ez:curd {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '简单的生成一套业务代码';

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

        // 调动服务
        $this->call('ez:controller', [
            'name' => $name
        ]);
        // 调动服务
        $this->call('ez:service', [
            'name' => $name
        ]);
        // 调动模型
        $this->call('ez:model', [
            'name' => $name
        ]);
    }


    
}
