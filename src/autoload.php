<?php
/**
 * (c) kaluzki
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return call_user_func(function() {
    /** @noinspection PhpIncludeInspection */
    require_once (include 'vendor-path.php') . 'autoload.php';
    return function(...$definitions) {
        return \kaluzki\Oxid\Container::createContainer(...$definitions);
    };
});

