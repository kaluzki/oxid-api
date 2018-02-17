<?php
/**
 * (c) kaluzki
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return call_user_func(function(array $candidates) {
    foreach ($candidates as $candidate) {
        if (file_exists($candidate)) {
            /** @noinspection PhpIncludeInspection */
            require_once $candidate;
            return function(...$definitions) {
                return \kaluzki\Oxid\Container::createContainer(...$definitions);
            };
        }
    }
    die('vendor/autoload.php could not be found. Did you run `composer install`?');
}, [
    // lookup priority:

    // as a composer project
    dirname(__DIR__) . '/vendor/autoload.php',

    // as a composer module
    dirname(dirname(dirname(__DIR__))) . '/autoload.php'
]);

