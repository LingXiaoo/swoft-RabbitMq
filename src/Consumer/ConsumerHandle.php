<?php

namespace Lingxiao\Swoft\RabbitMq\Consumer;

use Lingxiao\Swoft\RabbitMq\Result;
use PhpAmqpLib\Message\AMQPMessage;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Log\Helper\Log;

/**
 * Class ConsumerHandle
 * @package Lingxiao\Swoft\RabbitMq\Consumer
 * @Bean()
 */
class ConsumerHandle implements ConsumerHandleInterface
{

    /**
     * @param $data
     * @param AMQPMessage $message
     * @return string
     */
    public function handle($data,AMQPMessage $message) :string
    {
        Log::info($data);
        return Result::ACK;
    }
}