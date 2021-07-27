<?php

namespace Lingxiao\Swoft\RabbitMq\Producer;


/**
 * Class ProducerInterface
 * @package Lingxiao\Swoft\RabbitMq\Producer
 */
Interface ProducerInterface
{

    /**
     * @param mixed $data
     */
    public function setMessage($data,array $properties = []);

    public function push();
}