<?php
/*
 * @Date: 2021-01-12 16:49:10
 * @LastEditors: LiShangHeng
 * @LastEditTime: 2021-01-16 15:50:09
 * @FilePath: /api/app/Http/Tools/Services/ElasticSearchService.php
 */


namespace Lsh\Services;
use Elasticsearch\ClientBuilder;

class ElasticSearchService {
    
    /**
     * 配置
     * @var 
     */
    private $config;

    /**
     * es客户端
     * @var 
     */
    protected $client;

    function __construct()
    {
        $env = config('elasticsearch.env');
        $this->config = config('elasticsearch.'.$env);
        $this->client = ClientBuilder::fromConfig($this->config);
        // echo $this->config;
        // echo $this->client;
       
    }

    /**
     * @name: LiShangHeng
     * @info: 索引操作
     * @param array $params
     * @return {*}
     */
    public function index($params) {
        return $this->client->index($params);
    }

    /**
     * @name: LiShangHeng
     * @info: get方法搜索
     * @param array $params
     * @return {*}
     */
    public function get($params) {
        return $this->client->get($params);
    }

    /**
     * @name: LiShangHeng
     * @info: 删除索引
     * @param array $params
     * @return {*}
     */
    public function deleteIndex($params) {
        return $this->client->indices()->delete($params);
    }

    /**
     * @name: LiShangHeng
     * @info: 批量操作
     * @param stirng $index
     * @param array $data
     * @return {*}
     */
    public function bulk($index, $data) {
        $result = [];
        foreach($data as  $value) {
            array_push($result, ["index" => ["_index" => $index, "_id" => $value['id']]]);
            array_push($result, $value);
        }
        $params = [
            'body' => $result
        ];
        return $this->client->bulk($params);
    }

    /**
     * @name: LiShangHeng
     * @info: 使用_search关键词获取
     * @param {*}
     * @return array
     */
    public function search($index, $data) {
        $params = [
            'index' => $index,
            'body' => $data
        ];
        return $this->client->search($params);
    }

    /**
     * @name: LiShangHeng
     * @info: 返回类似sql like用法的模糊查询字符,一般用于wildcard关键词
     * @param {*} $string
     * @return {*}
     */
    public function sqlLikeString($string) {
        return '*'.$string.'*';
    }

    /**
     * @name: LiShangHeng
     * @info: 全字段搜索
     * @param {*} $string
     * @return {*}
     */
    public function keywordString($string) {
        return $string.'.keyword';
    }
}