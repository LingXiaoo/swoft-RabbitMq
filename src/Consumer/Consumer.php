<?php

namespace Lingxiao\Swoft\RabbitMq\Consumer;

use Lingxiao\Swoft\RabbitMq\Channel\BaseChannel;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Log\Helper\Log;
use Lingxiao\Swoft\RabbitMq\Result;

/**
 * Class MqProducer
 * @package Lingxiao\Swoft\RabbitMq\Producer
 * @Bean()
 */
class Consumer extends BaseConsumer implements ConsumerInterface
{
    public function run(){
        $autoAck = $this->rabbit->getAutoAck();
        $this->AmqChannel->basic_consume($this->rabbit->getQueueName(), '', false, $autoAck, false, false, function($message) use ($autoAck){

            $data = json_decode($message->getBody(),true);
            /** @var AMQPChannel $channel */
            $channel = $message->delivery_info['channel'];
            $deliveryTag = $message->delivery_info['delivery_tag'];

            try {

                //触发事件
                $result = $this->ConsumerHandle->handle($data, $message);
                //触发事件

            } catch (\Throwable $exception){
                Log::error('Rabbitmq Consumer error',[$exception->getMessage(),$exception->getFile(),$exception->getLine()]);
                $result = Result::DROP;
            }

            if ($result === Result::ACK) {
                Log::info('Rabbitmq Consumer ACK Message '.$deliveryTag);
                return $channel->basic_ack($deliveryTag);
            }
            if ($result === Result::NACK) {
                Log::info('Rabbitmq Consumer NACK Message '.$deliveryTag);
                return $channel->basic_nack($deliveryTag);
            }
            if ($result === Result::REQUEUE) {
                Log::info('Rabbitmq Consumer Requeue Message '.$deliveryTag);
                return $channel->basic_reject($deliveryTag, true);
            }
            Log::info('Rabbitmq Consumer Drop Message '.$deliveryTag);
            return $channel->basic_reject($deliveryTag, false);
        });
        while($this->AmqChannel->is_consuming()){
            $this->AmqChannel->wait();
        }
    }
}