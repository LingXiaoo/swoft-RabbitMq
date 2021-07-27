<?php

namespace Lingxiao\Swoft\RabbitMq\Consumer;

use Lingxiao\Swoft\RabbitMq\Channel\BaseChannel;
use PhpAmqpLib\Message\AMQPMessage;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Log\Helper\Log;

/**
 * Class MqProducer
 * @package Lingxiao\Swoft\RabbitMq\Producer
 * @Bean()
 */
class MqConsumer extends BaseChannel implements ConsumerInterface
{
    public function run(){
        $autoAck = $this->rabbit->getAutoAck();
        $this->AmqChannel->basic_consume($this->rabbit->getQueueName(), '', false, $autoAck, false, false, function($message) use ($autoAck){
            $re = $this->handle($message);
            if(!$autoAck){
                if ($re){
                    $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
                }
            }
        });
        while(count($this->AmqChannel->callbacks)){
            $this->AmqChannel->wait();
        }
    }

    /**
     * @param AMQPMessage $message
     */
    public function handle(AMQPMessage $message) : bool
    {
        Log::info($message->getBody());
        return true;
    }
}