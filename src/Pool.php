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
     * @return ConnectionInterface
     * @throws RabbitException
     */
    public function createConnection(): ConnectionInterface
    {
        return $this->rabbitMq->createConnection($this);
    }

    /**
     * @return Connection|ConnectionInterface
     */
    public function connection()
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

        return $connection;
    }


//    /**
//     * call magic method
//     *
//     * @param string $name
//     * @param array  $arguments
//     *
//     * @return Connection
//     * @throws RabbitException
//     */
//    public function __call(string $name, array $arguments)
//    {
//        try {
//            /* @var ConnectionManager $conManager */
//            $conManager = BeanFactory::getBean(ConnectionManager::class);
//
//            $connection = $this->getConnection();
//
//            $connection->setRelease(true);
//            $conManager->setConnection($connection);
//        } catch (Throwable $e) {
//            throw new RabbitException(
//                sprintf('Pool error is %s file=%s line=%d', $e->getMessage(), $e->getFile(), $e->getLine())
//            );
//        }
//
//        // Not instanceof Connection
//        if (!$connection instanceof Connection) {
//            throw new RabbitException(
//                sprintf('%s is not instanceof %s', get_class($connection), Connection::class)
//            );
//        }
//
//        return $connection->{$name}(...$arguments);
//    }

    /**
     * @return RabbitMq
     */
    public function getRabbitMq(): RabbitMq
    {
        return $this->rabbitMq;
    }
}
