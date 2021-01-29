<?php
namespace Lsh\Core\Traits\BaseService;
/*
 * @Date: 2020-12-07 15:57:48
 * @LastEditors: LiShangHeng
 * @LastEditTime: 2021-01-29 14:29:23
 * @FilePath: /LshBags/src/Core/Traits/BaseService.php/CurdOperator.php
 */
use Illuminate\Database\Eloquent\Model;

// 增删改查
Trait CurdOperator {
    use Paginate, Filter, User, Cache;

    /**
     * 默认字段,否则orm模型在保存的可能会报错
     * @var 
     */
    protected $defaultField = [
        
    ];

    /**
     * 设置限制字段
     * @var 
     */
    protected $constraintField = [
        'is_on' => 1
    ];

    /**
     * orm模型
     * @var 
     */
    public $model;

    /**
     * 获取列表
     * @param Model $model 传入模型
     * @param array $data 筛选数据
     * @return
     */
    public function list($data) 
    {
        /* 缓存相关 */
        if($this->isOpenCache) {
             // 提前设置变量,这里不能移动
            $cacheType = __FUNCTION__ . json_encode($data + $this->getPageData());
            $cache = $this->getCache($cacheType);
            if($cache) {                
                $result = $cache;
            } else {
                $result = $this->listInstance($data);
                $result = $this->setCache($result->toArray(), $cacheType);
            }
        } else {
            $result = $this->listInstance($data);  
        }
        return $result;
    }

    /**
     * @name: LiShangHeng
     * @info: 获取
     * @param {*}
     * @return {*}
     */
    protected function listInstance($data) {
        /* 分页paginate */
        $this->decidePage($data);
        /* 过滤 */
        $this->filters($data);
        /* 设置默认限制 */
        $this->setConstraintField();
        $list = $this->model;
        $result =  $this->hasPage ? $list->paginate($this->pageNum) : $list->get();
        return $result;
    }


    /**
     * 获取详情模型
     * @param Model $model 传入模型
     * @param int $id 活动id
     * @return Model $model 传入模型
     */
    protected function detailsInstance(int $id) {
        $this->setConstraintField();
        $data = $this->model->findOrFail($id);
        return $data;
    }

    /**
     * 获取详情
     * @param Model $model 传入模型
     * @param int $id 活动id
     * @return object
     */
    public function details(int $id) 
    {
        /* 缓存相关 */
        if($this->isOpenCache) {
            $cache = $this->getCache(__FUNCTION__);
            $this->cacheId = $id;
            if($cache) {
                $data = $cache;
            } else {
                $data = $this->detailsInstance($id);
                $this->setCache($data->toArray(), __FUNCTION__);
            }
        } else {
            $data = $this->detailsInstance($id);
        }
        return $data;
    }

    /**
     * 添加数据
     * @param Model $model 传入模型
     * @param array $data 添加数据
     * @return object $saveModel
     */
    public function add(array $data) 
    {
        $saveModel = $this->model->replicate();
        $saveModel = $this->setDefault($saveModel);
        $saveModel->fill($data);
        $saveModel->saveOrFail();
        /* 自动删除设定好的缓存 */
        if($this->isOpenCache) {
            $this->autoDeleteCache();
        }
       
        return $saveModel;
    }

    /**
     * 更新数据
     * @param array $data 修改数据
     * @param  int $id 
     * @return array $data 传入修改数据
     */
    public function change($id, array $data) 
    {
        $changeModel = $this->detailsInstance($id);
        $this->setUpdataModel($changeModel,$data);
        $changeModel->saveOrFail();
        $result = $changeModel;
        /* 缓存相关 */
        if($this->isOpenCache) {
            $this->autoDeleteCache();
            $this->cacheId = $id;
            $cache_result = $result->toArray();       
            $this->setCache($cache_result, __FUNCTION__);
        }
        return $result;
    }

    /**
     * 删除
     * @param Model $model 传入模型
     * @param int $id 
     * @return int $result
     */
    public function softDestroy($id) 
    {
        $result = $this->change($id, ['is_on' => 0]);
        /* 自动删除设定好的缓存 */
        if($this->isOpenCache) {
            $this->autoDeleteCache();
        }
        return $result;
    }

    /**
     * @name: LiShangHeng
     * @msg: 设置更新模型的属性
     * @param Model $model 传入模型
     * @param array $data 修改数据
     * @return Model $model 模型
     */
    private function setUpdataModel(Model $model,array $data) {
        foreach($data as $key => $value) {
            $model->{$key} = $value;
        }
        return $model;
    }


    /**
     * @name: LiShangHeng
     * @msg: 给模型设置默认值
     * @param {*}
     * @return {*}
     */
    public function setDefault($model) {
        foreach($this->defaultField as $key => $value) {
            $model->{$key} = $value;
        }
        return $model;
    }

    /**
     * @name: LiShangHeng
     * @msg: 设置默认限制字段
     * @param {*}
     * @return {*}
     */
    public function setConstraintField() {
        $keys = array_keys($this->constraintField);
        $this->filterEqual($this->constraintField, $keys);
        return;
    }

    /**
     * @name: LiShangHeng
     * @msg: 设置创建者才能创建的模型
     * @param {*}
     * @return {*}
     */
    public function setCreatorModel($key = 'user_id') {
        $this->setUserId();
        $this->constraintField[$key] = $this->userId;
        $this->defaultField[$key] = $this->userId;
    }

    /**
     * @name: LiShangHeng
     * @msg: 查询是否存在
     * @param array 查询数据
     * @param array 需要查询的key值数组,这里只支持相等的查找
     * @return {*}
     */
    public function checkModelExist(array $data, array $filterFields) {
        $copyData = $data;
        $this->filterEqual($copyData, $filterFields);
        $result = $this->model->exists();
        return $result;
    }

    
}