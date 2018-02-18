<?php
/**
 * (c) kaluzki
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

\fn\traverse([
    'sShopDir' => OX_BASE_PATH,
    'sCompileDir' => OX_BASE_PATH . 'tmp/',
    'blProductive' => false,
    'aLanguageURLs' => [],
], function($value, $name) {
    $this->$name = $value;
    // configuration parameters in $this->_aConfigParams have the highest priority
    $this->_aConfigParams[$name] = $value;
});
