<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Lingxiao\Swoft\RabbitMq\RabbitMq;

return [
    'config' => [
        'path' => __DIR__ . '/config',
    ],
    'rabbit'               => [
        'class'         => \Lingxiao\Swoft\RabbitMq\RabbitMq::class,
        'host'          => '127.0.0.1',
        'port'          => 5672,
        'user'          => '',
        'password'      => '',
        "queueLists"  => config("rabbit.queueLists"),
    ],
    'rabbit.pool'          => [
        'class'       => \Lingxiao\Swoft\RabbitMq\Pool::class,
        'rabbitMq'    => \bean('rabbit'),
        'minActive'   => 10,
        'maxActive'   => 20,
        'maxWait'     => 0,
        'maxWaitTime' => 0,
        'maxIdleTime' => 60,
    ],
];
