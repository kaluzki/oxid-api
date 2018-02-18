<?php
/**
 * (c) kaluzki
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

defined('INSTALLATION_ROOT_PATH') || call_user_func(function($vendorPath, array $candidates) {
    foreach ($candidates as $candidate => $isInstalled) {
        if (file_exists($vendorPath . $candidate)) {
            /** @noinspection PhpIncludeInspection */
            require_once $vendorPath . $candidate;
            return;
        }
    }
    die('oxid source/bootstrap.php could not be found.');
}, include 'vendor-path.php', [
    '../source/bootstrap.php' => true,
    'oxid-esales/oxideshop-ce/source/bootstrap.php' => false, // @todo requires overwritten bootstrap
]);
