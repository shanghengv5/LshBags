<?php
/*
 * @Date: 2021-01-21 10:38:02
 * @LastEditors: LiShangHeng
 * @LastEditTime: 2021-01-21 18:26:03
 * @FilePath: /LshBags/src/Core/BaseController.php
 */

namespace Lsh\Core;

class BaseController {

    /**
     * 
     * @var 
     */
    protected $pageRule = [
        'no_page' => 'integer',
        'page_num' => 'integer',
    ];

    /**
     * @name: LiShangHeng
     * @msg: 列表验证数据
     * @param  Request $request 
     * @return array 
     */
    private function listData(Request $request)
    {
        return $request->validate($this->pageRule + $this->listRule);
    }

    /**
     * @name: LiShangHeng
     * @msg: 保存验证数据
     * @param  Request $request 
     * @return array 
     */
    private function storeData(Request $request)
    {
        $requestData = $request->validate($this->storeRule);
        return $requestData;
    }

    /**
     * @name: LiShangHeng
     * @msg: 更新验证数据
     * @param  Request $request 
     * @return array 
     */
    private function changeData(Request $request)
    {
        $requestData = $request->validate($this->changeRule);
        return $requestData;
    }
    
    /**
     * @name: LiShangHeng
     * @info: 返回一个laravel响应数据
     * @param array $data
     * @return {*}
     */
    public function response($data) {
        return;
    }
}