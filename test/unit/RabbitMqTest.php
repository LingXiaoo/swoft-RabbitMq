<?php
/**
 * Class KafkaTopicTest
 * author:costalong
 * Email:longqiuhong@163.com
 */

namespace Lingxiao\Swoft\RabbitMq\Test\Unit;


use Lingxiao\Swoft\RabbitMq\Producer\MqProducer;
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

    }

    /**
     * @throws Exception
     */
    public function testProducer()
    {
        $producer = BeanFactory::getBean(MqProducer::class);
        $producer->setQueue('queue_crm_order');
        $producer->setMessage('Hello World1');
        $producer->setMessage('Hello World1');
//
        $producer->push();
        var_dump('success！');
    }
}
