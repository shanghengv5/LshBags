<?php
/*
 * @Date: 2020-10-26 16:16:08
 * @LastEditors: LiShangHeng
 * @LastEditTime: 2021-01-29 10:28:18
 * @FilePath: /LshBags/src/Core/BaseService.php
 */

namespace Lsh\Core;

use Illuminate\Database\Eloquent\Model;
use Lsh\Core\Traits\BaseService\CurdOperator;
use Lsh\Core\Traits\BaseService\Privilege;
use Lsh\Core\Traits\BaseService\Config;
use Lsh\Core\Traits\BaseService\Renew;
use ReflectionClass;

class BaseService 
{
    use CurdOperator, Privilege, Config, Renew;
    private $modelName;
    
    

    function __construct(Model $model, string $type)
    {
        /* 记录模型名字 */
        $reflect = new ReflectionClass($model);
        $this->modelName = $reflect->getName();
        
        /* 设置 */
        $this->model = $model;
        $this->type = $type;
        
        $this->setConfig();
    }

    

    
    
}