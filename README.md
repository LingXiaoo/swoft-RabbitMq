# swoft-RabbitMq
###### 配置rabbitMq信息`bean.php`
```angular2html
return [
    'config' => [
        'path' => __DIR__ . '/config',
    ],
    'rabbit'            => [
        'class'         => \Lingxiao\Swoft\RabbitMq\RabbitMq::class,
        'host'          => '127.0.0.1',
        'port'          => 5672,
        'user'          => '',
        'password'      => '',
        "queueLists"  => config("rabbit.queueLists"),
    ],
    'rabbit.pool'     => [
        'class'       => \Lingxiao\Swoft\RabbitMq\Pool::class,
        'rabbitMq'    => \bean('rabbit'),
        'minActive'   => 10,
        'maxActive'   => 20,
        'maxWait'     => 0,
        'maxWaitTime' => 0,
        'maxIdleTime' => 60,
    ],
];
```

###### 配置队列`config/rabbit.php`
```angular2html
return [
    //队列名称
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
```

###### 使用方法
```angular2html

消费者
/** @var Channel $channel */
$channel = BeanFactory::getBean(Channel::class);
$channel->setQueue('test');
/** @var ConsumerInterface $consumer */
$consumer = $channel->consumer();
$consumer->run();

生产者
/** @var Channel $channel */
$channel = BeanFactory::getBean(Channel::class);
$channel->setQueue('test');
/** @var ProducerInterface $producer */
$producer = $channel->producer();
$producer->setMessage('Hello World1');
$producer->setMessage('Hello World2');
$producer->push();
```
