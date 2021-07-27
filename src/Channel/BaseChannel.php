<?php

namespace Lingxiao\Swoft\RabbitMq\Channel;

use PhpAmqpLib\Channel\AMQPChannel;

/**
 * Class BaseChannel
 * @package Lingxiao\Swoft\RabbitMq\Channel
 */
class BaseChannel
{
    /**
     * @var array
     */
    protected $message = [];
    /**
     * @var Channel
     */
    protected $rabbit;

    /**
     * @var AMQPChannel
     */
    protected $AmqChannel;

    /**
     * @param AMQPChannel $AmqChannel
     */
    public function setAmqChannel(AMQPChannel $AmqChannel): void
    {
        $this->AmqChannel = $AmqChannel;
    }

    /**
     * @param mixed $rabbit
     */
    public function setRabbit($rabbit): void
    {
        $this->rabbit = $rabbit;
    }

}