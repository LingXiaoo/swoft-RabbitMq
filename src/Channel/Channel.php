<?php

namespace Lingxiao\Swoft\RabbitMq\Channel;

use Lingxiao\Swoft\RabbitMq\Connection\Connection;
use Lingxiao\Swoft\RabbitMq\Consumer\MqConsumer;
use Lingxiao\Swoft\RabbitMq\Exception\RabbitException;
use Lingxiao\Swoft\RabbitMq\Pool;
use Lingxiao\Swoft\RabbitMq\Producer\MqProducer;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Swoft\Bean\BeanFactory;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;

/**
 * Class MqProducer
 * @package Lingxiao\Swoft\RabbitMq\Channel
 * @Bean()
 */
class Channel
{
    /**
     * 链接名称
     * @var string
     */
    protected $queue = '';
    /**
     * 链接池
     * @Inject()
     * @var Pool
     */
    protected $poll;

    /**
     * 队列配置
     * @var string
     */
    protected $queueConf = [];

    /**
     * 队列名称
     * @var string
     */
    protected $queueName = '';

    /**
     * 交换机名称
     * @var string
     */
    protected $exchangeName = '';

    /**
     * 交换机名称
     * @var string
     */
    protected $routeKey = '';

    /**
     * 是否手动确认
     * @var string
     */
    protected $autoAck = false;

    /**
     * 链接
     * @var Connection|AMQPStreamConnection
     */
    public $connection;

    /**
     * 信道
     * @var AMQPChannel
     */
    public $channel;

    public function setQueueConf(){
        $rabbitMq = $this->poll->getRabbitMq();
        $conf = $rabbitMq->getQueueLists();
        if (empty($conf)){
            throw new RabbitException('QueueLists is Not configured!');
        }
        $conf = array_column($conf,null,'name');
        $queueConf = $conf[$this->getQueue()] ?? [];
        if (empty($queueConf)){
            throw new RabbitException('QueueConf is Not configured!');
        }
        if (empty($queueConf['exchangeName'])){
            throw new RabbitException('exchange is Not configured!');
        }
        if (empty($queueConf['queueName'])){
            throw new RabbitException('Queue is Not configured!');
        }
        $this->queueConf = $queueConf;
        $this->setExchangeName($queueConf['exchangeName']);
        $this->setQueueName($queueConf['queueName']);
        $this->setRouteKey($queueConf['queueName'] ?? '');
        $this->setAutoAck($queueConf['autoAck'] ?? true);

    }

    public function initChannel(){
        if (empty($this->getQueue())){
            throw new RabbitException('Queue is Not configured!');
        }
        $this->setQueueConf();
        $conf = $this->getQueueConf();
        if (empty($conf['vhost'])){
            throw new RabbitException('vhost is Not configured!');
        }
        //设置vhost再建立链接
        $this->poll->getRabbitMq()->setVhost($conf['vhost']);
        $this->connection = $this->poll->connection();
        $this->channel = $this->connection->channel();

        $exchange = $conf['exChange'] ?? [];
        $this->channel->exchange_declare(
            $this->getExchangeName(),
            $exchange['direct'] ?? 'direct',
            $exchange['passive'] ?? false,
            $exchange['durable'] ?? false,
            $exchange['auto_delete'] ?? false
        );

        $queue = $conf['queue'] ?? [];
        $this->channel->queue_declare(
            $this->getQueueName(),
            $queue['passive'] ?? false,
            $queue['durable'] ?? false,
            $queue['exclusive'] ?? false,
            $queue['auto_delete'] ?? false
        );

        $this->channel->queue_bind($this->getQueueName(), $this->getExchangeName(),$this->getRouteKey());
    }


    public function producer(){
        $this->initChannel();
        $conf = $this->getQueueConf();
        $producerClass = $conf['producerClass'] ?? MqProducer::class;
        $producer = BeanFactory::getBean($producerClass);
        $producer->setAmqChannel($this->channel);
        $producer->setRabbit($this);
        return $producer;
    }

    public function consumer(){
        $this->initChannel();
        $conf = $this->getQueueConf();
        $consumerClass = $conf['consumerClass'] ?? MqConsumer::class;
        $consumer = BeanFactory::getBean($consumerClass);
        $consumer->setAmqChannel($this->channel);
        $consumer->setRabbit($this);
        return $consumer;
    }

    public function close(){
        $this->channel->close();
        $this->connection->close();
        $this->connection->release();
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
    public function getQueueConf()
    {
        return $this->queueConf;
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

    /**
     * @return string
     */
    public function getExchangeName(): string
    {
        return $this->exchangeName;
    }

    /**
     * @param string $exchangeName
     */
    public function setExchangeName(string $exchangeName): void
    {
        $this->exchangeName = $exchangeName;
    }

    /**
     * @return string
     */
    public function getQueueName(): string
    {
        return $this->queueName;
    }

    /**
     * @param string $queueName
     */
    public function setQueueName(string $queueName): void
    {
        $this->queueName = $queueName;
    }

    /**
     * @return string
     */
    public function getAutoAck()
    {
        return $this->autoAck;
    }

    /**
     * @param string $autoAck
     */
    public function setAutoAck($autoAck): void
    {
        $this->autoAck = $autoAck;
    }

}