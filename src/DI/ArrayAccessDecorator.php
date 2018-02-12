<?php
/**
 * (c) kaluzki
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace kaluzki\DI;

use ArrayAccess;
use Psr\Container\ContainerInterface;
use RuntimeException;

/**
 */
class ArrayAccessDecorator implements ArrayAccess
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @inheritdoc
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($offset)
    {
        return $this->container->has($offset);
    }

    /**
     * @inheritdoc
     */
    public function offsetGet($offset)
    {
        return $this->container->get($offset);
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        if ($this->container instanceof \DI\Container) {
            return $this->container->set($offset, $value);
        }
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        throw new RuntimeException('not a \DI\Container');
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        $this->offsetSet($offset, null);
    }
}
