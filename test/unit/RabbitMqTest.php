<?php
/**
 * Class KafkaTopicTest
 * author:costalong
 * Email:longqiuhong@163.com
 */

namespace Lingxiao\Swoft\RabbitMq\Test\Unit;


use Lingxiao\Swoft\RabbitMq\RabbitMq;
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
        $RabbitMq = BeanFactory::getBean('rabbit.pool');
        $channel = $RabbitMq->channel();
        var_dump($channel);
    }

    /**
     * @throws Exception
     */
    public function testProducer()
    {

    }
}
