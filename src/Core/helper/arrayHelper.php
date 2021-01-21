<?php
/*
 * @Date: 2021-01-21 14:09:18
 * @LastEditors: LiShangHeng
 * @LastEditTime: 2021-01-21 15:05:30
 * @FilePath: /LshBags/src/Core/helper/arrayHelper.php
 */

if(!function_exists('array_insert')) {
    /**
     * @name: LiShangHeng
     * @info: 插入一个元素在索引
     * @param array &$array
     * @param integer $index 被插入的索引位置
     * @param integer $insert 被插入的元素
     */
    function array_insert(&$array, $index, $insert) {
        if($index < 0) {
            $index = count($array) + $index + 1;
        }
        // 取出对比元素到插入元素的前一位
        $compareArr = array_slice($array, $index);
        // 把插入元素放到头
        array_unshift($compareArr, $insert);
        // 替换这部分数组
        array_splice($array, $index, count($compareArr), $compareArr);
    }
}

/**
 * @name: LiShangHeng
 * @info: 插入一个元素在索引前
 * @param array &$array
 * @param integer $index 被插入的索引位置
 * @param integer $insert 被插入的元素
 * @return {*}
 */
if(!function_exists('array_insert_before'))  {
    function array_insert_before(&$array, $index, $insert) {
        array_insert($array, $index-1, $insert);
    }
}

if(!function_exists('function_insert_affer')) {
    /**
     * @name: LiShangHeng
     * @info: 插入一个元素在索引后
    * @param array &$array
    * @param integer $index 被插入的索引位置
    * @param integer $insert 被插入的元素
    * @return {*}
    */
    function array_insert_after(&$array, $index, $insert) {
        array_insert($array, $index+1, $insert);
    }
}

if(!function_exists('array_move')) {
    /**
     * @name: LiShangHeng
     * @info: 把一个位置的元素替换到一个索引,数组长度不变
     * @param array &$array
     * @param integer $index 被插入的索引位置
     * @param integer $insert 被插入的元素
     */
    function array_move(&$array, $index, $insert) {
        if($insert > count($array) || $index < 0) {
            return;
        }
        // 取出到插入元素的数组,不包括插入元素
        $compareArr = array_slice($array, $index, $insert-$index);
        // 把插入元素放到数组头
        array_unshift($compareArr, $array[$insert]);
        // 替换成新数组
        array_splice($array, $index, count($compareArr), $compareArr);
    }
}