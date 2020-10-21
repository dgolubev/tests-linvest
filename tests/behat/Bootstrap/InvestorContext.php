<?php declare(strict_types=1);

namespace Deg\Linvest\BTests\Bootstrap;

use Behat\Behat\Context\Context;
use Deg\Linvest\Model;
use PHPUnit\Framework\Assert;

class InvestorContext implements Context
{
    /** @var Model\Investor[] */
    private static $investors = [];

    /** @var Model\Factory\Investor */
    private $investorFactory;

    /**
     * Initializes Bootstrap.
     *
     * Every scenario gets its own Bootstrap instance.
     * You can also pass arbitrary arguments to the
     * Bootstrap constructor through behat.yml.
     */
    public function __construct()
    {
        $this->investorFactory = new Model\Factory\Investor();
    }

    /**
     * @BeforeScenario
     */
    public function beforeScenario()
    {
        self::$investors = [];
    }

    /**
     * @Given there is investor :name with :moneyAmount in wallet
     */
    public function createInvestor(string $name, float $moneyAmount): void
    {
        self::$investors[$name] = $this->investorFactory->create($name, $moneyAmount);
    }

    /**
     * @Then in investor :investorName wallet should be :amount
     */
    public function walletAmount(string $investorName, float $amount)
    {
        Assert::assertEquals($amount, self::getInvestor($investorName)->getWalletAmount());
    }

    /**
     * Get Investor
     *
     * @param string $investorName
     *
     * @return Model\Investor|null
     */
    public static function getInvestor(string $investorName): ?Model\Investor
    {
        return self::$investors[$investorName] ?: null;
    }
}
