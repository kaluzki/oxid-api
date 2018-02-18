<?php
/**
 * (c) kaluzki
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return call_user_func(function(array $candidates) {
    foreach ($candidates as $candidate) {
        if (file_exists($candidate . 'autoload.php')) {
            return $candidate;
        }
    }
    die('vendor/autoload.php could not be found. Did you run `composer install`?');
}, [
    // lookup priority:

    // as standalone composer project
    dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR,

    // as required composer package
    dirname(dirname(dirname(dirname(__DIR__)))) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR,
]);
