<?php declare(strict_types=1);

namespace Lingxiao\Swoft\RabbitMq\Connection;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use Lingxiao\Swoft\RabbitMq\Pool;
use Lingxiao\Swoft\RabbitMq\RabbitMq;
use Lingxiao\Swoft\RabbitMq\Contract\ConnectionInterface;
use Swoft\Bean\BeanFactory;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Connection\Pool\AbstractConnection;
use Lingxiao\Swoft\RabbitMq\Exception\RabbitException;


/**
 * Class Connection
 * @package Lingxiao\Swoft\RabbitMq\Connection
 * @Bean()
 */
class Connection extends AbstractConnection implements ConnectionInterface
{
    /**
     * @var AMQPStreamConnection
     */
    protected $client;

    /**
     * @var RabbitMq
     */
    protected $rabbitMq;

    /**
     * @var Pool
     */
    protected $pool;

    /**
     * @param Pool    $pool
     * @param RabbitMq $rabbitMq
     */
    public function initialize(Pool $pool, RabbitMq $rabbitMq): void
    {
        $this->pool     = $pool;
        $this->rabbitMq  = $rabbitMq;
        $this->lastTime = time();

        $this->id = $this->pool->getConnectionId();
    }

    /**
     * @throws RabbitException
     */
    public function create(): void
    {
        $this->createClient();
    }

    /**
     * Pass other method calls down to the underlying client.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     * @throws RabbitException
     */
    public function __call(string $method, array $parameters)
    {
        return $this->command($method, $parameters);
    }

    /**
     *
     * @param string $method
     * @param array  $parameters
     * @param bool   $reconnect
     *
     * @return mixed
     * @throws RabbitException
     */
    public function command(string $method, array $parameters = [], bool $reconnect = false)
    {
        try {

            if (false === method_exists($this->client, $method)) {
                throw new RabbitException(sprintf('Redis method(%s) is not supported!', $method));
            }
            $result = $this->client->{$method}(...$parameters);

        } catch (\Throwable $e) {
            if (!$reconnect && $this->reconnect()) {
                return $this->command($method, $parameters, true);
            }

            throw new RabbitException('Rabbit command reconnect error=' . $e->getMessage(), $e->getCode(), $e);
        }

        return $result;
    }

    public function reconnect(): bool
    {
        $this->create();
    }

    /**
     * @throws RabbitException
     */
    public function createClient(): void
    {
        $this->client = $this->connection = new AMQPStreamConnection(
            $this->rabbitMq->getHost(),
            $this->rabbitMq->getPort(),
            $this->rabbitMq->getUser(),
            $this->rabbitMq->getPassword(),
            $this->rabbitMq->getVhost()
        );
    }

    /**
     * Close connection
     */
    public function close(): void
    {
        $this->client->close();
    }

    public function closeAndRelease(){
        $this->close();
        $this->release();
    }

    /**
     * @param bool $force
     *
     */
    public function release(bool $force = false): void
    {
        /* @var ConnectionManager $conManager */
        $conManager = BeanFactory::getBean(ConnectionManager::class);
        $conManager->releaseConnection($this->id);

        parent::release($force);
    }

}
