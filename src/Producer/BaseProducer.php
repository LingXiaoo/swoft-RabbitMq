<?php

namespace Lingxiao\Swoft\RabbitMq\Producer;

use Lingxiao\Swoft\RabbitMq\Channel\Channel;
use PhpAmqpLib\Channel\AMQPChannel;

/**
 * Class BaseProducer
 * @package Lingxiao\Swoft\RabbitMq\Channel
 */
class BaseProducer
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

    public function clearMessage(){
        $this->message = [];
    }

    /**
     * @param mixed $rabbit
     */
    public function setRabbit($rabbit): void
    {
        $this->rabbit = $rabbit;
    }

}