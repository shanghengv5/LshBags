<?php
/*
 * @Date: 2020-12-07 15:59:48
 * @LastEditors: LiShangHeng
 * @LastEditTime: 2021-01-16 12:21:54
 * @FilePath: /api/app/Http/Tools/Traits/BaseService/Filter.php
 */
namespace Lsh\Core\Traits\BaseService;

// 过滤
trait Filter {
    /**
     * 模糊查询
     * @var 
     */
    protected $fuzzys = [];

    /**
     * 相等查询
     * @var 
     */
    protected $equals = [];

    /**
     * 范围查询
     * @var 
     */
    protected $betweens = [];

    /**
     * 范围查询取反
     * @var 
     */
    protected $notBetweens = [];

    /**
     * 范围查询
     * @var 
     */
    protected $ins = [];

    /**
     * 范围查询取反
     * @var 
     */
    protected $notIns = [];

    /**
     * @name: LiShangHeng
     * @msg: 模糊查询
     * @param string $string 
     * @return {*}
     */
    protected function fuzzyQuery($string)
    {
        return '%' . $string . '%';
    }

    /**
     * @name: LiShangHeng
     * @msg: 模糊查询
     * @param array $data 被过滤数据
     * @param array $filterNames 需要模糊搜索的名字
     * @return {*}
     */
    protected function filterFuzzy(array &$data, array $filterNames, $dropData = true)
    {
        foreach($filterNames as $name) {
            if(isset($data[$name])) {
                $this->model = $this->model->where($name, 'like', $this->fuzzyQuery($data[$name]));
            }
        }
    }

    /**
     * @name: LiShangHeng
     * @msg: 准确查询
     * @param array $data 筛选关联数组数据
     * @param array $filterNames 被筛选字段名
     */
    protected function filterEqual(array &$data, array $filterNames, $dropData = true) {
        foreach($filterNames as $name) {
            if(isset($data[$name])) {
                $this->model = $this->model->where($name, '=', $data[$name]);
                if($dropData) {
                    unset($data[$name]);
                }
                
            }
        }
    }

    /**
     * @name: LiShangHeng
     * @msg: 范围查询, eg. $filterNames = ['fieldName' => ['start_at', 'end_at']]
     * @param array $data 筛选关联数组数据
     * @param array $filterNames 被筛选字段名
     * @return void
     */
    protected function filterBetween(array &$data, array $filterNames, $flag = true, $dropData = true) {
        foreach($filterNames as $key => $names) {
            if(count($names) != 0 && isset($data[$names[0]]) && isset($data[$names[1]])) {
                $min = $data[$names[0]];
                $max = $data[$names[1]];
                $flag ? $this->model = $this->model->whereBetween($key, [$min, $max]) : $this->model = $this->model->whereNotBetween($key, [$min, $max]);

                if($dropData) {
                    unset($data[$names[0]]);
                    unset($data[$names[1]]);
                }
            }
        }
    }

    /**
     * @name: LiShangHeng
     * @msg: 不在这个范围中的查询, eg. $filterNames = ['fieldName' => ['start_at', 'end_at']]
     * @param array $data 筛选关联数组数据
     * @param array $filterNames 被筛选字段名
     * @return void
     */
    protected function filterNotBetween(array &$data, array $filterNames, $dropData = true) {
        $this->filterBetween($data, $filterNames, false, $dropData);
    }

    /**
     * @name: LiShangHeng
     * @msg: 批量In查询
     * @param array $data 筛选关联数组数据
     * @param array $filterNames 被筛选字段名
     */
    protected function filterIns(array &$data, array $filterNames, $flag = true, $dropData = true) {
        foreach($filterNames as $name) {
            if(isset($data[$name]) && is_array($data[$name])) {
                $flag ? $this->model = $this->model->whereIn($name, $data[$name]) : $this->model = $this->model->whereNotIn($name, $data[$name]);
                if($dropData) {
                    unset($data[$name]);
                }
            }
        }
    }

    /**
     * @name: LiShangHeng
     * @msg: 批量In查询
     * @param array $data 筛选关联数组数据
     * @param array $filterNames 被筛选字段名
     */
    protected function filterNotIns(array &$data, array $filterNames, $dropData = true) {
        $this->filterIns($data, $filterNames, false, $dropData);
    }

    /**
     * @name: LiShangHeng
     * @msg: 总过滤
     * @param {*}
     * @return {*}
     */
    protected function filters(array &$data) {
        /* 批量模糊查询 */
        $this->filterFuzzy($data, $this->fuzzys);
        /* 批量准确查询 */
        $this->filterEqual($data, $this->equals);
        /* 批量范围查询 */
        $this->filterBetween($data, $this->betweens);
        $this->filterNotBetween($data, $this->notBetweens);
        $this->filterIns($data, $this->ins);
        $this->filterNotIns($data, $this->notIns);
    }
}