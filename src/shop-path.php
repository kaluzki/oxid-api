<?php
/**
 * (c) kaluzki
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return call_user_func(function() {
    $facts = new \OxidEsales\Facts\Facts;
    if (is_dir($dir = $facts->getEnterpriseEditionRootPath()) === true) {
        return $dir . DIRECTORY_SEPARATOR;
    }
    if (is_dir($dir = $facts->getProfessionalEditionRootPath()) === true) {
        return $dir . DIRECTORY_SEPARATOR;
    }
    if (is_dir($dir = $facts->getCommunityEditionSourcePath()) === true) {
        return $dir . DIRECTORY_SEPARATOR;
    }
    die('edition source path could not be found. Did you run `composer require oxid-esales/oxideshop-(ce|pe|ee)`?');
});
