<?php

return [
    'queueLists' => [
        [
            'name' => 'test',
            'vhost' => '/vhost',
            'exchangeName' => 'test', //交换机名称
            'queueName' => 'test',//队列名称
            'routeKey' => '',
            'producerClass' => \Lingxiao\Swoft\RabbitMq\Producer\MqProducer::class,//生产者
            'consumerHandle' => \Lingxiao\Swoft\RabbitMq\Consumer\ConsumerHandle::class,//消费者处理类
            'autoAck' => false,//是否自动应答
            'exChange' => [
                'direct' => 'direct', //路由类型
                'passive' => true, //是否检测同名队列
                'durable' => false, //是否开启持久化
                'auto_delete' => false //关闭后是否删除
            ],
            'queue' => [
                'passive' => true, //是否检测同名队列
                'durable' => false, //是否开启持久化
                'exclusive' => false, //队列是否可以被其他队列访问
                'auto_delete' => false//关闭后是否删除
            ],
        ],
    ],

];