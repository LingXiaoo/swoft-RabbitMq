<?php declare(strict_types=1);

namespace Lingxiao\Swoft\RabbitMq\test\testing;

use Swoft\SwoftComponent;

/**
 * Class AutoLoader
 */
class AutoLoader extends SwoftComponent
{
    /**
     * Get namespace and dir
     *
     * @return array
     * [
     *     namespace => dir path
     * ]
     */
    public function getPrefixDirs(): array
    {
        return [
            __NAMESPACE__ => __DIR__,
        ];
    }

    /**
     * Metadata information for the component.
     *
     * @return array
     * @see ComponentInterface::getMetadata()
     */
    public function metadata(): array
    {
        return [];
    }

    /**
     * @return string[][]
     */
    public function beans(): array
    {
        return [
            "rabbitmq" =>[
                "class" => \Lingxiao\Swoft\RabbitMq\RabbitMq::class,
                "brokers" => "127.0.0.1"
            ],
        ];
    }
}
