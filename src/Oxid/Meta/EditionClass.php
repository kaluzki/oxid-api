<?php
/**
 * (c) kaluzki
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace kaluzki\Oxid\Meta;
use fn;

use function fn\map;
use fn\Map\Sort;
use kaluzki\DI\ArrayAccessDecorator;
use kaluzki\Oxid\Container;
use OxidEsales\Eshop\Application\Component\Widget\WidgetController;
use OxidEsales\Eshop\Application\Controller\AccountController;
use OxidEsales\Eshop\Application\Controller\Admin\AdminController;
use OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController;
use OxidEsales\Eshop\Application\Controller\Admin\AdminListController;
use OxidEsales\Eshop\Application\Controller\Admin\DynamicExportBaseController;
use OxidEsales\Eshop\Application\Controller\Admin\ListComponentAjax;
use OxidEsales\Eshop\Application\Controller\Admin\ObjectSeo;
use OxidEsales\Eshop\Application\Controller\Admin\ShopConfiguration;
use OxidEsales\Eshop\Application\Controller\ArticleDetailsController;
use OxidEsales\Eshop\Application\Controller\ArticleListController;
use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Core\Base;
use OxidEsales\Eshop\Core\Controller\BaseController;
use OxidEsales\Eshop\Core\Model\BaseModel;
use OxidEsales\Eshop\Core\Model\ListModel;
use OxidEsales\Eshop\Core\Model\MultiLanguageModel;
use OxidEsales\Eshop\Core\SeoEncoder;
use ReflectionClass;

/**
 * @property-read string $class
 * @property-read string $package
 * @property-read string[] $parents
 */
class EditionClass
{
    /**
     * @var string
     */
    const NS = 'OxidEsales\Eshop\\';

    /**
     * @var string[]
     */
    const PACKAGES = [
        Base::class => '/',
        SeoEncoder::class => '/Seo',
        BaseModel::class => '/Model',
        MultiLanguageModel::class => '/Model/I18n',
        ListModel::class => '/Model/List',
        BaseController::class => '/Controller',
        FrontendController::class => '/Front',
        WidgetController::class => '/Front/Widget',
        AccountController::class => '/Front/Account',
        ArticleListController::class => '/Front/Article/List',
        ArticleDetailsController::class => '/Front/Article/Details',
        AdminController::class => '/Admin',
        ListComponentAjax::class => '/Admin/Component',
        AdminListController::class => '/Admin/List',
        AdminDetailsController::class => '/Admin/Details',
        DynamicExportBaseController::class => '/Admin/Details/Export',
        ShopConfiguration::class => '/Admin/Details/Config',
        ObjectSeo::class => '/Admin/Details/Seo',
    ];

    use fn\Meta\Properties\ReadOnlyTrait;

    /**
     * @var array
     */
    protected $properties;

    /**
     * @var array
     */
    private static $packages;

    /**
     * @param iterable|callable [$children]
     */
    public function __construct($class)
    {
        $this->properties = new ArrayAccessDecorator(Container::createContainer([
            'class'   => $class,
            'package' => function() {
                return self::package($this->class);
            },
            'parents' => function() {
                return self::parents($this->class);
            }
        ]));
    }

    /**
     * @param string $class
     * @return string
     */
    private static function package($class)
    {
        if (self::$packages === null) {
            self::$packages = map(self::PACKAGES)->sort(function($left, $right) {
                return count(self::parents($left)) - count(self::parents($right));
            }, Sort::KEYS | Sort::REVERSE)->map;
        }
        foreach (self::$packages as $baseClass => $package) {
            if (is_a($class, $baseClass, true)) {
                return $package;
            }
        }
        return '/UNKNOWN';
    }

    /**
     * @param string $class
     * @return array
     */
    private static function parents($class)
    {
        $ref = new ReflectionClass($class);
        $parents = [];
        while($parent = $ref->getParentClass()) {
            $parents[] = $parent->getName();
            $ref = $parent;
        }
        return fn\map($parents, function($class) {
            return strpos($class, self::NS) === 0 ? $class : null;
        })->map;
    }
}
