<?php
/**
 * (c) kaluzki
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace kaluzki\Oxid\Core;

use OxidEsales\Eshop\Core\Config;

/**
 * Omit loading config entries from the database
 */
class TempConfig extends Config
{
    /**
     * @inheritdoc
     */
    protected function _loadVarsFromDb($shopID, $onlyVars = null, $module = '')
    {
        return true;
    }
}
