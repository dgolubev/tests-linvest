<?php declare(strict_types=1);

namespace Deg\Linvest\BTests\Bootstrap;

use Behat\Behat\Context\Context;

/**
 * Defines application features from the specific Bootstrap.
 */
class FeatureContext implements Context
{
    /**
     * Initializes Bootstrap.
     *
     * Every scenario gets its own Bootstrap instance.
     * You can also pass arbitrary arguments to the
     * Bootstrap constructor through behat.yml.
     */
    public function __construct()
    {
    }
}
