<?php
namespace Lsh\Core\Traits\BaseService;
/*
 * @Date: 2021-01-16 12:23:50
 * @LastEditors: LiShangHeng
 * @LastEditTime: 2021-01-29 10:27:13
 * @FilePath: /LshBags/src/Core/Traits/BaseService.php/Renew.php
 */


// 用于自动设置缓存
Trait Renew {
    /**
     * @name: LiShangHeng
     * @msg: 重置模型
     * @return bool
     */
    public function renew() {
        $this->model = new $this->modelName;
        return true;
    }

    /**
     * @name: LiShangHeng
     * @msg: 设置另一个模型来使用这个基础类
     * @param Model $model
     * @return bool
     */
    public function setOtherModel(Model $model) {
        $this->model = $model;
        return true;
    }

    /**
     * @name: LiShangHeng
     * @info: 判断是否需要重置模型
     */
    public function autoNeedRenew() {
        if($this->needRenew != 0) {
            $this->renew();
        }
        $this->setNeedRenew();
    }

    /**
     * @name: LiShangHeng
     * @info: 设置需要重置属性值
     * @param integer $val
     */
    private function setNeedRenew($val = 1) {
        $this->needRenew = $val;
    }
}