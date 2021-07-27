<?php

namespace Lingxiao\Swoft\RabbitMq\Consumer;

use Lingxiao\Swoft\RabbitMq\Channel\BaseChannel;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class MqProducer
 * @package Lingxiao\Swoft\RabbitMq\Producer
 * @Bean()
 */
class MqConsumer extends BaseChannel implements ConsumerInterface
{
    public function run(){
        $autoAck = $this->rabbit->getAutoAck();
        $this->AmqChannel->basic_consume($this->rabbit->getQueueName(), '', false, $autoAck, false, false, function($msg) use ($autoAck){
            $param = $msg->body;
            var_dump($param);
//            $this->doProcess($param);
            if(!$autoAck){
                //手动ack应答
                $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
            }
        });
        //监听消息
        while(count($this->AmqChannel->callbacks)){
            $this->AmqChannel->wait();
        }
    }
}