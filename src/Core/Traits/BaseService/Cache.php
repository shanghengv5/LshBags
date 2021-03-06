<?php
namespace Lsh\Core\Traits\BaseService;
/*
 * @Date: 2021-01-16 12:23:50
 * @LastEditors: LiShangHeng
 * @LastEditTime: 2021-03-09 14:58:32
 * @FilePath: /LshBags/src/Core/Traits/BaseService/Cache.php
 */

use Illuminate\Support\Facades\Redis;
// 用于自动设置缓存
Trait Cache {
    /**
     * 是否开启缓存
     * @var 
     */
    protected $isOpenCache = 1;

    /**
     * 分隔符
     * @var 
     */
    protected $delimiter = '_';

    /**
     * 缓存时间
     * @var 
     */
    protected $cacheTime = 60 * 3;

    /**
     * 缓存id
     * @var 
     */
    protected $cacheId = 0;

    /**
     * 自动删除缓存,数组内的值是type名
     * @var 
     */
    protected $autoDeleteCacheName = [
        'list'
    ];

    /**
     * @name: LiShangHeng
     * @info: 判断redis是否可用
     */
    public function beginRedis() {
        // $ret = Redis::auth(config('database.redis.default.password'));
        try {
            if($this->openCache) {
                $ret = Redis::ping();
                if(!$ret) {
                    logger('缓存关闭');
                    $this->closeCache();
                }
            }
        } catch(\Exception $e) {
            logger('redis设置错误');
        }
    }

    /**
     * @name: LiShangHeng
     * @info: 设置是否开启缓存
     * @return {*}
     */
    private function setOpenCache($val) {
        $this->isOpenCache = $val;
    }

    /**
     * @name: LiShangHeng
     * @info: 开启缓存
     */
    public function openCache() {
        $this->setOpenCache(1);
    }

    /**
     * @name: LiShangHeng
     * @info: 关闭缓存
     */
    public function closeCache() {
        $this->setOpenCache(0);
    }

    /**
     * @name: LiShangHeng
     * @info: 返回数据
     * @param {*} $type
     * @return {*}
     */
    public function getCache($type) {
        $cacheKey = $this->getCacheKey($type);
        // logger($cacheKey);
        $cacheData = Redis::get($cacheKey);
        return empty($cacheData) ? false : json_decode($cacheData, true);
    }

    /**
     * @name: LiShangHeng
     * @info: 自动设置和获取缓存
     * @param array $value
     * @param string $type
     * @return {*}
     */
    public function setCache(array $value, $type) {
        // 这是模型名前缀,有修改数据操作,将删除模型名开头的所有缓存
        $cacheKey = $this->getCacheKey($type);
        array_walk($this->autoDeleteCacheName, function($item) use($type, $cacheKey) {
            if(strpos($type, $item) !== false) {
                $autokeys = $this->getAutoKey($item);
                if(!$this->getCache($cacheKey)) {
                    // 用redis集合来管理自动删除的key
                    Redis::sadd($autokeys, $cacheKey);
                }
            }
        });
        Redis::set($cacheKey, json_encode($value));
        Redis::expire($cacheKey, $this->cacheTime);
        return $value;
    }

    /**
     * @name: LiShangHeng
     * @info: 获取缓存键
     * @param {*} $type
     * @return {*}
     */
    public function getCacheKey($type) {
        // logger('看看key');
        // logger($this->modelName);
        $modelNamePrefix = $this->modelName ?? 'all';
        $uniqueId = $this->cacheId ?? 0;
        return $modelNamePrefix . $this->delimiter . $uniqueId . $this->delimiter . $type;
    }

    /**
     * @name: LiShangHeng
     * @info: 获取自动维护的键值
     * @param {*}
     * @return {*}
     */
    public function getAutoKey($string) {
        $modelNamePrefix = $this->modelName ?? 'all';
        $uniqueId = $this->cacheId ?? 0;
        $auto = 'autokeys';
        return $modelNamePrefix . $this->delimiter . $uniqueId . $this->delimiter . $auto . $this->delimiter  . $string;
    }

    /**
     * @name: LiShangHeng
     * @info: 手动删除缓存
     * @param {*} $type
     * @return {*}
     */
    public function deleteCache($key) {        
        $result = Redis::del($key);
        return $result;
    }

    /**
     * @name: LiShangHeng
     * @info: 批量删除
     * @param {*}
     * @return {*}
     */
    public function batchDelete($item) {
        $autokeys = $this->getAutoKey($item);
        $set = Redis::smembers($autokeys);
        // logger($set);
        Redis::del($autokeys);
        foreach($set as $key) {
            // logger('批量删除中'.$key);
            $this->deleteCache($key);
        }

    }


    /**
     * @name: LiShangHeng
     * @info: 自动删除缓存
     * @param {*}
     * @return {*}
     */
    public function autoDeleteCache() {
        array_walk($this->autoDeleteCacheName, function($item) {
            // logger('删除缓存');
            $this->batchDelete($item);
        });
        return true;
    }

    /**
     * @name: LiShangHeng
     * @info: 获取翻页数据
     * @param {*}
     * @return array
     */
    public function getPageData() {
        $pageData = request()->validate(['page' => '']);
        return $pageData;
    }
}