<?php
namespace Lingxiao\Swoft\RabbitMq;

use  Lingxiao\Swoft\RabbitMq\Connection\Connection;
use Swoft\Bean\BeanFactory;

/**
 * Class RabbitMq
 *
 */
class RabbitMq
{
    /**
     * @var string
     */
    protected $host = '127.0.0.1';

    /**
     * @var int
     */
    protected $port = 5672;

    /**
     * @var string
     */
    protected $user = '';

    /**
     * @var string
     */
    protected $password = '';

    /**
     * @var array
     */
    protected $queueLists = [];

    public function createConnection(Pool $pool){
        //调用连接类
        /** @var Connection $connection */
        $connection = BeanFactory::getBean(Connection::class);
        $connection->initialize($pool,$this);
        $connection->create();
        return $connection;
    }

    /**
     * @return string
     */
    public function getVhost(): string
    {
        return $this->vhost;
    }

    /**
     * @param string $vhost
     */
    public function setVhost(string $vhost): void
    {
        $this->vhost = $vhost;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPort(): string
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return array
     */
    public function getQueueLists(): array
    {
        return $this->queueLists;
    }

    /**
     * @param array $queueLists
     */
    public function setQueueLists(array $queueLists): void
    {
        $this->queueLists = $queueLists;
    }

}
