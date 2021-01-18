<?php
namespace Lsh\Traits\BaseService;
/*
 * @Date: 2020-12-07 15:57:48
 * @LastEditors: LiShangHeng
 * @LastEditTime: 2020-12-08 11:54:01
 * @FilePath: /api/app/Http/Tools/Traits/Privilege.php
 */

 // 权限相关
 trait Privilege {
     
    /**
     * @name: LiShangHeng
     * @msg: 判断权限判断后缀的正确与合法性
     * @param string $funcName 函数全名
     * @param mixed $arguments 参数
     * @param string $identifierValue 验证后缀
     * @param bool $flag 如果是true,则代表当函数返回true时通过验证,不会抛错. 为false,结果反之.
     * @return bool 返回值是给判断是否进入了后缀的验证中,true是进入并且通过.false代表没有进入
     */
    public function identifyPrivilegeSuffix($funcName, $arguments, $identifierValue, $flag = true) {
        /* 函数和类的变量 */
        $funcLength = strlen($funcName);
        /* 关键词的变量 */
        $identifierLength = strlen($identifierValue);
        $negativeIdentifierLength = -$identifierLength;
        if(substr($funcName, $negativeIdentifierLength, $identifierLength) == $identifierValue) {
            $actualFuncName = substr($funcName, 0, $funcLength - $identifierLength);
            if(method_exists($this, $actualFuncName)) {
                $funcResult = $this->{$actualFuncName}(...$arguments);
                if($funcResult != $flag) {
                    $this->throwFunctionNotPrivilege($actualFuncName);
                }
                // 走进判断
                return true;
            } else {
                $this->throwFunctionNotExist($actualFuncName);
            }
        } 
        // 没有走进判断
        return false;
    }

    /**
     * @name: LiShangHeng
     * @msg: 抛出不存在函数异常
     * @param {*}
     * @return {*}
     */
    public function throwFunctionNotExist($funcName) {
        $className = class_basename($this); 
        throw new \Exception("类{$className}无此函数".$funcName);
    }

     /**
     * @name: LiShangHeng
     * @msg: 抛出无权限异常
     * @param {*}
     * @return {*}
     */
    public function throwFunctionNotPrivilege($funcName) {
        throw new \Exception('函数'.$funcName.'验证合法性失败,你无权执行这个操作');
    }


    /**
     * @name: LiShangHeng
     * @msg: 自动设置失败函数
     * @param {*}
     * @return {*}
     */
    function __call($funcName, $arguments)
    {
        if($this->identifyPrivilegeSuffix($funcName, $arguments, 'IfFail', false)) {
            return false;
        } else if($this->identifyPrivilegeSuffix($funcName, $arguments, 'IfSuccess')) {
            return true;
        } else {
            $this->throwFunctionNotExist($funcName);
        }
    }
 }