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
    private $host = '127.0.0.1';
    private $port = '5672';
    private $user = '';
    private $password = '';
    private $vhost = '/';

    public function createConnection(Pool $pool){
        //调用连接类
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

}
