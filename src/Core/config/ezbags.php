<?php
/*
 * @Date: 2021-01-22 18:25:05
 * @LastEditors: LiShangHeng
 * @LastEditTime: 2021-01-29 10:18:15
 * @FilePath: /LshBags/src/Core/config/ezbags.php
 */

return [
    // 命令配置
    'Console' => [
        // 命名空间
        'namepace' => [
            'model' => 'Model',
            'service' => 'Http\\Services',
            'controller' => 'Http\\Controllers'
        ]
    ],
    // BaseService 配置
    'BaseService' => [
        // 缓存
        'redis' => [
            'isOpen' => false,
        ]
    ]
];