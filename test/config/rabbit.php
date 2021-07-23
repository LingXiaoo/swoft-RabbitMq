<?php
return [
    'test' => [ //vhost
        [
            'name' => 'test',
            'exChange' => [
                'exchangeName' => 'test_queue', //交换机名称
                'direct' => 'direct', //路由类型
                'passive' => true, //是否检测同名队列
                'durable' => false, //是否开启持久化
                'auto_delete' => false //关闭后是否删除

            ],
            'queue' => [
                'queueName' => 'test_queue', //队列名称
                'passive' => true, //是否检测同名队列
                'durable' => false, //是否开启持久化
                'exclusive' => false, //队列是否可以被其他队列访问
                'auto_delete' => false//关闭后是否删除
            ],
            'routeKey' => '',
        ],
    ],

];