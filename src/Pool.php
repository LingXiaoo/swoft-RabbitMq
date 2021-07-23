<?php declare(strict_types=1);

namespace Lingxiao\Swoft\RabbitMq;

use Swoft\Bean\BeanFactory;
use Swoft\Connection\Pool\AbstractPool;
use Swoft\Connection\Pool\Contract\ConnectionInterface;
use Lingxiao\Swoft\RabbitMq\Connection\Connection;
use Lingxiao\Swoft\RabbitMq\Connection\ConnectionManager;
use Lingxiao\Swoft\RabbitMq\Exception\RabbitException;
use Throwable;

/**
 */
class Pool extends AbstractPool
{
    /**
     * Default pool
     */
    public const DEFAULT_POOL = 'rabbit.pool';

    /**
     * @var RabbitMq
     */
    protected $rabbitMq;

    /**
     * @var string
     */
    protected $vhost = '/';

    /**
     * @var array
     */
    protected $queueLists = [];


    /**
     * @return ConnectionInterface
     * @throws RabbitException
     */
    public function createConnection(): ConnectionInterface
    {
        $this->rabbitMq->setVhost($this->getVhost());
        $this->rabbitMq->setVhost($this->getVhost());

        return $this->rabbitMq->createConnection($this);
    }

    /**
     * call magic method
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return Connection
     * @throws RabbitException
     */
    public function __call(string $name, array $arguments)
    {
        try {
            /* @var ConnectionManager $conManager */
            $conManager = BeanFactory::getBean(ConnectionManager::class);

            $connection = $this->getConnection();

            $connection->setRelease(true);
            $conManager->setConnection($connection);
        } catch (Throwable $e) {
            throw new RabbitException(
                sprintf('Pool error is %s file=%s line=%d', $e->getMessage(), $e->getFile(), $e->getLine())
            );
        }

        // Not instanceof Connection
        if (!$connection instanceof Connection) {
            throw new RabbitException(
                sprintf('%s is not instanceof %s', get_class($connection), Connection::class)
            );
        }

        return $connection->{$name}(...$arguments);
    }

    /**
     * @return RabbitMq
     */
    public function getRabbitMq(): RabbitMq
    {
        return $this->rabbitMq;
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
