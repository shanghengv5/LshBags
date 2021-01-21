<?php
/*
 * @Date: 2021-01-19 15:49:40
 * @LastEditors: LiShangHeng
 * @LastEditTime: 2021-01-21 10:50:38
 * @FilePath: /LshBags/src/Core/CoreServiceProvider.php
 */
namespace Lsh\Core;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;

class CoreServiceProvider extends ServiceProvider {
    
    /**
     * 
     * @var 
     */
    protected $configs = [
        // 'elasticsearch' => 'elasticsearch.php'
    ];

    /**
     * 
     * @var 
     */
    protected $bagPrefix = 'Lsh';

    /**
     * 
     * @var 
     */
    protected $delimiter = '_';

    /**
     * 启动应用服务
     *
     * @return void
     */
    public function boot() {
        // 发布配置文件
        // $this->publishesConfig();
        // 注册命令
        // if ($this->app->runningInConsole()) {
        //     $this->commands([
        //         FooCommand::class,
        //         BarCommand::class,
        //     ]);
        // }
        // 执行命令
        // Artisan::call('command');

    }

    /**
     * 注册应用服务
     *
     * @return void
     */
    public function register() {
        
    }

    /**
     * @name: LiShangHeng
     * @info: 发布配置到laravel的config目录
     * @param {*}
     * @return {*}
     */
    public function publishesConfig() {
        $configArr = [];
        $tag = 'config';
        
        foreach($this->configs as $key => $value) {
            $tag = $key;
            $configArr[$this->configPath($value)] = config_path($value);
        }
        $this->publishes([
            // array_merge($configArr, $otherArr)
            $configArr
        ], $this->bagPrefix . $this->delimiter . $tag);
    }

    /**
     * @name: LiShangHeng
     * @info: 加载路由
     * @param {*}
     * @return {*}
     */
    public function loads() {
        // 路由加载
        //$this->loadRoutesFrom(__DIR__.'/routes.php');
        // 迁移加载
        // $this->loadMigrationsFrom(__DIR__.'/path/to/migrations');
        // 语言包
        // $this->loadTranslationsFrom(__DIR__.'/path/to/translations', 'courier');
    }

    /**
     * @name: LiShangHeng
     * @info: 返回配置目录
     * @return string
     */
    private function configPath($filepath) {
        $dir = __DIR__.'../config';
        return $dir .'/'. $filepath;
    }
}