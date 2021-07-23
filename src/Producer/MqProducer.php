<?php

namespace Lingxiao\Swoft\RabbitMq\Producer;

use Lingxiao\Swoft\RabbitMq\Connection\Connection;
use Lingxiao\Swoft\RabbitMq\Exception\RabbitException;
use Lingxiao\Swoft\RabbitMq\Pool;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use Swoft\Bean\BeanFactory;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;

/**
 * Class MqProducer
 * @package Lingxiao\Swoft\RabbitMq\Producer
 * @Bean()
 */
class MqProducer
{
    public $message = [];
    /**
     * @Inject()
     * @var Pool
     */
    protected $poll;

    /**
     * @var string
     */
    protected $routeKey = '';

    /**
     * @var string
     */
    protected $queue = '';

    /**
     * @var AMQPChannel|Pool
     */
    public $channel;

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

    public function getQueueLists(){
        $conf = $this->poll->getQueueLists();
        if (empty($conf)){
            throw new RabbitException('QueueLists is Not configured!');
        }
        return array_column($conf,null,'name');
    }

    public function initChannel(){
        if (empty($this->getQueue())){
            throw new RabbitException('Queue is Not configured!');
        }

        $queueLists = $this->getQueueLists();
        $exchange = $queueLists[$this->getQueue()]['exChange'];
        $queue = $queueLists[$this->getQueue()]['queue'];

        $this->channel = $this->poll->channel();
        $this->channel->exchange_declare(
            $exchange['exchangeName'],
            $exchange['direct'],
            $exchange['passive'] ?? false,
            $exchange['durable'] ?? false,
            $exchange['auto_delete'] ?? false
        );
        $this->channel->queue_declare(
            $queue['queueName'],
            $queue['passive'] ?? false,
            $queue['durable'] ?? false,
            $queue['exclusive'] ?? false,
            $queue['auto_delete'] ?? false
        );
        $this->channel->queue_bind($queue['queueName'], $exchange['exchangeName'],$this->getRouteKey());
    }

    public function push(){
        if (empty($this->message)){
            throw new RabbitException('Message is empty!');
        }
        $this->initChannel();

        foreach ($this->message as $msg){
            //触发事件
            var_dump($msg->getBody());
            $this->channel->basic_publish($msg,$this->getQueue(),$this->getRouteKey());
            //触发事件
        }
        //清空当前对象关闭通道
        $this->channel->close();
    }

    /**
     * @return string
     */
    public function getQueue(): string
    {
        return $this->queue;
    }

    /**
     * @param string $queue
     */
    public function setQueue(string $queue): void
    {
        $this->queue = $queue;
    }


    /**
     * @return string
     */
    public function getRouteKey(): string
    {
        return $this->routeKey;
    }

    /**
     * @param string $routeKey
     */
    public function setRouteKey(string $routeKey): void
    {
        $this->routeKey = $routeKey;
    }
}