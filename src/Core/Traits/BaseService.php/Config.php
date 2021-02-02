<?php
namespace Lsh\Core\Traits\BaseService;
/*
 * @Date: 2021-01-16 12:23:50
 * @LastEditors: LiShangHeng
 * @LastEditTime: 2021-02-02 15:09:11
 * @FilePath: /LshBags/src/Core/Traits/BaseService.php/Config.php
 */


// 用于自动设置缓存
Trait Config {
    protected $tableName = '';
    /**
     * @name: LiShangHeng
     * @info: 获取配置
     */
    public function setConfig() {
        $this->config = config('ezbags.BaseService');

        $this->setRedisConfig();
        
        $this->setTableName();
    }

    /**
     * @name: LiShangHeng
     * @info: 设置redis配置
     */
    public function setRedisConfig() {
        $isOpen = $this->config['redis']['isOpen'];
        if(!$isOpen) {
            $this->closeCache();
        } else {
            $this->openCache();
        }
        /* redis认证 */
        $this->beginRedis();
    }

    /**
     * @name: LiShangHeng
     * @info: 设置表名
     */
    public function setTableName() {
        $tableName = \Str::snake(class_basename($this));
        $tableName = str_replace('_service', '', $tableName);
        $this->tableName = $tableName;
    }
}