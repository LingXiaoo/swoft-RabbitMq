<?php
return [
    'queueLists' => [
        [
            'name' => 'test',
            'vhost' => '/',
            'exchangeName' => 'test', //交换机名称
            'queueName' => 'test',//队列名称
            'routeKey' => '',
            'producerClass' => \Lingxiao\Swoft\RabbitMq\Producer\MqProducer::class,
            'consumerClass' => \Lingxiao\Swoft\RabbitMq\Consumer\MqConsumer::class,
            'autoAck' => true,
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