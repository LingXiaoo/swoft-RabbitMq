<?php

namespace Lingxiao\Swoft\RabbitMq\Consumer;

use PhpAmqpLib\Message\AMQPMessage;

Interface ConsumerHandleInterface
{
    /**
     * @param $data
     * @param AMQPMessage $message
     * @return string
     */
    public function handle($data, AMQPMessage $message) :string;
}