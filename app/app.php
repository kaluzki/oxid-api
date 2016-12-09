<?php
/**
 * (c) Demjan Kaluzki <kaluzkidemjan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once 'autoload.php';

return function() {
    $app = new Slim\App(include 'config.php');

    foreach (include 'middleware.php' as $callable) {
        $app->add($callable);
    }

    foreach (include 'routes.php' as $pattern => $callable) {
        $app->get($pattern, $callable);
    }
    $app->run();
};