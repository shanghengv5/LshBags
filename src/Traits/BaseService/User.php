<?php
namespace Lsh\Traits\BaseService;
/*
 * @Date: 2020-12-07 15:57:48
 * @LastEditors: LiShangHeng
 * @LastEditTime: 2021-01-16 12:06:00
 * @FilePath: /api/app/Http/Tools/Traits/BaseService/User.php
 */

 // 业务功能
trait User {
    /**
     * 用户id
     * @var int
     */
    public $userId = 0;

    /**
     * 服务类型
     * @var string
     */
    protected $type = 'admin';

    /**
     * 后缀组
     * @var string
     */
    public $IDKEY_SUFFIX = '_info';
    public $IDVALUE_SUFFIX = '_id';
    public $ID_CONNECT = '.';

    
    /**
     * @name: LiShangHeng
     * @msg: 设置初始用户id
     */
    public function setUserId()
    {
        $this->userId = $this->getJwtTypeId($this->type);
    }

    /**
     * @name: LiShangHeng
     * @msg: 获取某个类型的jwt数据的id
     * @param {*}
     * @return {*}
     */
    public function getJwtTypeId($type) {
        $data = \Jwt::get($type.$this->IDKEY_SUFFIX . $this->ID_CONNECT . $type.$this->IDVALUE_SUFFIX);
        return $data;
    }
    
    
}