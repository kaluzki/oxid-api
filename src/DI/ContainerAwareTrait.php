<?php
/**
 * (c) kaluzki
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace kaluzki\DI;

use DI\ContainerBuilder;

/**
 * @mixin \DI\Container
 */
trait ContainerAwareTrait
{
    /**
     * @param mixed ...$definitions
     * @return static|\DI\Container
     */
    public static function createContainer(...$definitions)
    {
        $builder = new ContainerBuilder(static::class);
        $builder->addDefinitions(static::getContainerDefinitions($builder));
        foreach ($definitions as $definition) {
            $builder->addDefinitions($definition instanceof \Closure ? $definition($builder) : $definition);
        }
        return $builder->build();
    }

    /**
     * @param ContainerBuilder|null $builder
     * @return mixed
     */
    protected static function getContainerDefinitions(ContainerBuilder $builder = null)
    {
        return [];
    }

    /**
     * @param mixed ...$args
     * @return mixed
     */
    public function __invoke(...$args)
    {
        return $this->call(...$args);
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        try {
            return $this->has($name);
        } catch(\InvalidArgumentException $ignore) {
            return false;
        }
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value)
    {
        throw new \RuntimeException(__METHOD__);
    }

    /**
     * @param string $name
     */
    public function __unset($name)
    {
        throw new \RuntimeException(__METHOD__);
    }
}
