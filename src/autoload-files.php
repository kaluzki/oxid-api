<?php
/**
 * (c) kaluzki
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use OxidEsales\Eshop\Core;

defined('INSTALLATION_ROOT_PATH') || call_user_func(function($vendorPath) {
    if (is_readable($bootStrapFile = dirname($vendorPath) . '/source/bootstrap.php')) {
        /** @noinspection PhpIncludeInspection */
        require_once $bootStrapFile;
        return;
    }

    // try to bootstrap the oxid shop from vendor directory

    define('VENDOR_PATH', $vendorPath);
    define('OX_BASE_PATH', dirname($vendorPath) . '/resources/bootstrap/');
    define('OX_LOG_FILE', OX_BASE_PATH . 'EXCEPTION_LOG.txt');
    $shopPath = include 'shop-path.php';

    /**
     * @param string $message
     */
    function writeToLog($message)
    {
        $time = microtime(true);
        $micro = sprintf("%06d", ($time - floor($time)) * 1000000);
        $date = new \DateTime(date('Y-m-d H:i:s.' . $micro, $time));
        $timestamp = $date->format('d M H:i:s.u Y');

        $message = "[$timestamp] " . $message . PHP_EOL;

        file_put_contents(OX_LOG_FILE, $message, FILE_APPEND);
    }

    Core\Registry::set(Core\Config::class, new \kaluzki\Oxid\Core\TempConfig);
    Core\Registry::set(Core\ConfigFile::class, new Core\ConfigFile(OX_BASE_PATH . 'config.inc.php'));
    set_exception_handler([new Core\Exception\ExceptionHandler, 'handleUncaughtException']);

    require_once $shopPath . 'oxfunctions.php';
    require_once $shopPath . 'overridablefunctions.php';

}, include 'vendor-path.php');
