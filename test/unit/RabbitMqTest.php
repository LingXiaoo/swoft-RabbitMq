<?php
/**
 * Class KafkaTopicTest
 * author:costalong
 * Email:longqiuhong@163.com
 */

namespace Lingxiao\Swoft\RabbitMq\Test\Unit;

use Lingxiao\Swoft\RabbitMq\Channel\Channel;
use Lingxiao\Swoft\RabbitMq\Consumer\ConsumerInterface;
use Lingxiao\Swoft\RabbitMq\Producer\ProducerInterface;
use Exception;
use PHPUnit\Framework\TestCase;
use Swoft\Bean\BeanFactory;

class RabbitMqTest extends TestCase
{

    /**
     * @throws Exception
     */
    public function testConsumer()
    {
        /** @var Channel $channel */
        $channel = BeanFactory::getBean(Channel::class);
        $channel->setQueue('test');
        /** @var ConsumerInterface $consumer */
        $consumer = $channel->consumer();
        $consumer->run();
        var_dump('success！');
    }

    /**
     * @throws Exception
     */
    public function testProducer()
    {
        /** @var Channel $channel */
        $channel = BeanFactory::getBean(Channel::class);
        $channel->setQueue('test');
        /** @var ProducerInterface $producer */
        $producer = $channel->producer();
        $producer->setMessage('Hello World1');
        $producer->setMessage('Hello World2');
        $producer->push();
        var_dump('success！');

    }
}
