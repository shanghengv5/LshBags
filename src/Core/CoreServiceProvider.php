<?php
/*
 * @Date: 2021-01-19 15:49:40
 * @LastEditors: LiShangHeng
 * @LastEditTime: 2021-01-22 18:54:09
 * @FilePath: /LshBags/src/Core/CoreServiceProvider.php
 */
namespace Lsh\Core;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Lsh\Core\Console\EzColumn;
use Lsh\Core\Console\EzController;
use Lsh\Core\Console\EzCurd;
use Lsh\Core\Console\EzService;
use Lsh\Core\Console\EzModel;

class CoreServiceProvider extends ServiceProvider {

    /**
     * 
     * @var 
     */
    protected $bagPrefix = 'Lsh';
    protected $delimiter = '_';
    protected $tag = 'config';
    protected $ext = '.php';


    /**
     * 启动应用服务
     *
     * @return void
     */
    public function boot() {
        // 发布配置文件
        $this->publishesConfig('ezbags');
        
        // 注册命令
        if ($this->app->runningInConsole()) {
            $this->commands([
                // Ez业务功能,生成业务代码php文件
                EzController::class,
                EzCurd::class,
                EzService::class,
                EzModel::class,
                EzColumn::class,
            ]);
        }
        // 执行命令
        // Artisan::call();

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
    public function publishesConfig($value) {
        $this->publishes([
            $this->configPath($value.$this->ext) => config_path($value)
        ], $this->bagPrefix . $this->delimiter . $value . $this->delimiter . $this->tag);

        Artisan::call('vendor:publish --tag='.$this->bagPrefix . $this->delimiter . $value . $this->delimiter . $this->tag);
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