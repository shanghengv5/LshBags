<?php
/*
 * @Date: 2020-12-07 15:59:48
 * @LastEditors: LiShangHeng
 * @LastEditTime: 2020-12-19 18:38:17
 * @FilePath: /api/app/Http/Tools/Traits/Paginate.php
 */
namespace Lsh\Core\Traits\BaseService;

// 分页相关
trait Paginate {
    /**
     * 是否分页
     * @var 
     */
    protected $hasPage = 1;

    /**
     * 分页数目
     * @var 
     */
    protected $pageNum = 15;
    /**
     * 判断是否需要分页
     * @return
     */
    public function decidePage(&$data, $dropData = true) 
    {
        if(isset($data['no_page']) && $data['no_page'] == 1) {
            $this->hasPage = 0;
        }
        $this->pageNum = $data['page_num'] ?? 15;
        
        if ($dropData) {
            unset($data['no_page']);
            unset($data['page_num']);
        }
        return $data;
    }

    /**
     * @name: LiShangHeng
     * @msg: 是否需要分页, 1代表需要.0代表不需要分页
     * @param {*}
     * @return {*}
     */
    public function hasPage() {
        return $this->hasPage;
    }
}