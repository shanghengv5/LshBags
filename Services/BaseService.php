<?php
/*
 * @Date: 2020-10-26 16:16:08
 * @LastEditors: LiShangHeng
 * @LastEditTime: 2021-01-18 14:20:08
 * @FilePath: /api/app/Http/Tools/Services/BaseService.php
 */

namespace App\Http\Tools\Services;

use Illuminate\Database\Eloquent\Model;
use App\Http\Tools\Traits\BaseService\CurdOperator;
use App\Http\Tools\Traits\BaseService\Privilege;

use ReflectionClass;

class BaseService 
{
    use CurdOperator, Privilege;
    
    private $modelName;
    
    /**
     * 是否需要重置模型
     * @var 
     */
    protected $needRenew = 0;

    function __construct(Model $model, string $type)
    {
        /* 记录模型名字 */
        $reflect = new ReflectionClass($model);
        $this->modelName = $reflect->getName();
        
        /* 写入 */
        $this->model = $model;
        $this->type = $type;
        
        /* redis认证 */
        $this->beginRedis();
    }

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
    public function setNeedRenew($val = 1) {
        $this->needRenew = $val;
    }
    
}