<?php
/*
 * @Date: 2021-01-12 16:46:30
 * @LastEditors: LiShangHeng
 * @LastEditTime: 2021-01-13 16:31:38
 * @FilePath: /api/config/elasticsearch.php
 */


return [
    'env' => env('APP_ENV') == 'local' ? 'debug' : 'online',
    // 线下
    'debug' => [
        'hosts' => [
            'elasticsearch',
            'localhost:9200'
        ]
    ],
    // 开发
    'online' => [
        'hosts' => [
            'elasticsearch',
            'localhost:9200'
        ]
    ]
];