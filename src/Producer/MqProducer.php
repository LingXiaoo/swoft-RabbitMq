<?php

namespace Lingxiao\Swoft\RabbitMq\Producer;

use Lingxiao\Swoft\RabbitMq\Exception\RabbitException;
use PhpAmqpLib\Message\AMQPMessage;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Log\Helper\Log;

/**
 * Class MqProducer
 * @package Lingxiao\Swoft\RabbitMq\Producer
 * @Bean()
 */
class MqProducer extends BaseProducer implements ProducerInterface
{
    /**
     * @param mixed $data
     */
    public function setMessage($data,array $properties = [])
    {
        if (is_array($data)){
            $msg = json_encode($data,true);
        } elseif ($data instanceof \Closure){
            $msg = $data();
        } else {
            $msg = $data;
        }
        $this->message[] = new AMQPMessage($msg,$properties);
    }

    public function push(){
        if (empty($this->message)){
            throw new RabbitException('Message is empty!');
        }
        try {
            foreach ($this->message as $msg){
                //触发事件
                Log::info('Rabbitmq Producer publish Message',[$msg]);
                $this->AmqChannel->basic_publish($msg,$this->rabbit->getExchangeName(),$this->rabbit->getRouteKey());
                //触发事件
            }
            //清空当前对象关闭通道
            $this->clearMessage();
            $this->rabbit->close();
        }catch (\Throwable $exception){
            Log::error('Rabbitmq Producer error',[$exception->getMessage(),$exception->getFile(),$exception->getLine()]);
            //清空当前对象关闭通道
            $this->clearMessage();
            $this->rabbit->close();
        } finally {
            $this->clearMessage();
            $this->rabbit->close();
        }
    }
}