<?php
/**
 * (c) kaluzki
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace kaluzki\Oxid;

use kaluzki\DI\ContainerAwareTrait;

/**
 */
class Container extends \DI\Container
{
    use ContainerAwareTrait;

    /**
     * @inheritdoc
     */
    protected static function getContainerDefinitions()
    {
        return dirname(dirname(__DIR__)) . '/config/oxid.php';
    }
}
