<?php
namespace Lsh\Core\Traits\BaseService;
/*
 * @Date: 2021-01-16 12:23:50
 * @LastEditors: LiShangHeng
 * @LastEditTime: 2021-01-29 10:27:02
 * @FilePath: /LshBags/src/Core/Traits/BaseService.php/Config.php
 */


// 用于自动设置缓存
Trait Config {
    /**
     * @name: LiShangHeng
     * @info: 获取配置
     */
    public function setConfig() {
        $this->config = config('ezbags.BaseService');

        $this->setRedisConfig();
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
}