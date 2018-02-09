<?php
/**
 * (c) kaluzki
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if(!call_user_func(function(array $candidates) {
    foreach ($candidates as $candidate) {
        if (file_exists($candidate)) {
            /** @noinspection PhpIncludeInspection */
            require_once $candidate;
            return true;
        }
    }
    return false;
}, defined('INSTALLATION_ROOT_PATH') ? [] : [
    dirname(__DIR__) . '/source/bootstrap.php',
    dirname(__DIR__) . '/vendor/oxid-esales/oxideshop-ce/source/bootstrap.php',
]
)) {
    die('oxid source/bootstrap.php could not be found.');
}
