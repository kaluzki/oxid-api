<?php
/**
 * (c) Demjan Kaluzki <kaluzkidemjan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once 'autoload.php';

use Slim\Http\Request;
use Slim\Http\Response;

$app = new Slim\App();

$app->get('/', function(Request $request, Response $response) {
    return $response->withJson(['it' => 'works!']);
});

return function() use($app) {
    $app->run();
};