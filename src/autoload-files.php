<?php
/**
 * (c) kaluzki
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

defined('INSTALLATION_ROOT_PATH') || call_user_func(function(array $candidates) {
    foreach ($candidates as $candidate) {
        if (file_exists($candidate)) {
            /** @noinspection PhpIncludeInspection */
            require_once $candidate;
            return;
        }
    }
    die('oxid source/bootstrap.php could not be found.');
}, [
    // lookup priority:

    // as a module in an installed shop
    dirname(dirname(dirname(dirname(__DIR__)))) . '/source/bootstrap.php',

    // as a module in not yet installed shop
    dirname(dirname(dirname(dirname(__DIR__)))) . '/vendor/oxid-esales/oxideshop-ce/source/bootstrap.php',

    // as a project in an installed shop
    dirname(__DIR__) . '/source/bootstrap.php',

    // as a project in a not yet installed shop
    dirname(__DIR__) . '/vendor/oxid-esales/oxideshop-ce/source/bootstrap.php',
]);
