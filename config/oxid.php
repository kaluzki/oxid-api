<?php
/**
 * (c) kaluzki
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use function DI\object, DI\get;
use OxidEsales\Facts\Facts;
use OxidEsales\UnifiedNameSpaceGenerator\UnifiedNameSpaceClassMapProvider;

return [
    Facts::class => object(),
    UnifiedNameSpaceClassMapProvider::class => object()->constructor(get(Facts::class)),
];
