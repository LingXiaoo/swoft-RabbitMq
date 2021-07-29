<?php

namespace Lingxiao\Swoft\RabbitMq\Consumer;

use Lingxiao\Swoft\RabbitMq\Channel\Channel;
use PhpAmqpLib\Channel\AMQPChannel;

/**
 * Class BaseConsumer
 * @package Lingxiao\Swoft\RabbitMq\Channel
 */
class BaseConsumer
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
     * @var ConsumerHandleInterface
     */
    protected $ConsumerHandle;

    /**
     * @param mixed $ConsumerHandle
     */
    public function setConsumerHandle($ConsumerHandle): void
    {
        $this->ConsumerHandle = $ConsumerHandle;
    }

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