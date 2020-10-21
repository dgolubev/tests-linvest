<?php declare(strict_types=1);

namespace Deg\Linvest\BTests\Bootstrap;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Deg\Linvest\Model;
use Deg\Linvest\Model\Loan;
use Deg\Linvest\Service;
use PHPUnit\Framework\Assert;

class LoanContext implements Context
{
    /** @var Model\Loan[] */
    private static $loans = [];

    /** @var Model\Factory\Loan */
    private $loanFactory;
    /** @var Model\Factory\Tranche */
    private $trancheFactory;

    /** @var Service\Investment */
    private $investmentSrv;

    /** @var \Exception|\Throwable */
    private $investmentError;

    /**
     * Initializes Bootstrap.
     *
     * Every scenario gets its own Bootstrap instance.
     * You can also pass arbitrary arguments to the
     * Bootstrap constructor through behat.yml.
     */
    public function __construct()
    {
        $this->loanFactory = new Model\Factory\Loan();
        $this->trancheFactory = new Model\Factory\Tranche();

        $this->investmentSrv = (new Service\Factory\Investment())->create();
    }

    /**
     * @BeforeScenario
     */
    public function beforeScenario()
    {
        self::$loans = [];
    }

    /**
     * @Given there is a loan :name from :startAt till :endAt
     */
    public function createLoan(string $name, string $startAt, string $endAt): void
    {
        self::$loans[$name] = $this->loanFactory
            ->create(
                \DateTime::createFromFormat(Loan::DATE_FORMAT, $startAt),
                \DateTime::createFromFormat(Loan::DATE_FORMAT, $endAt),
            );
    }

    /**
     * @Given to loan :loanName assigned tranches:
     */
    public function assignTranches(string $loanName, TableNode $traches): void
    {
        foreach ($traches->getHash() as $tranche) {
            self::$loans[$loanName]->addTranche(
                $tranche['id'],
                $this->trancheFactory->create(
                    (float) $tranche['rate'],
                    (float) $tranche['amount']
                )
            );
        }
    }

    /**
     * @When investor :investorName at :paidAt invest :amount to :trancheId tranche of :loanName loan
     */
    public function makeInvestmentToLoan(
        string $investorName,
        string $paidAt,
        float $amount,
        string $trancheId,
        string $loanName
    ): void
    {
        try {
            $this->investmentSrv->investToLoan(
                self::getLoan($loanName),
                $trancheId,
                InvestorContext::getInvestor($investorName),
                $amount,
                \DateTime::createFromFormat(Model\Payment::DATE_FORMAT, $paidAt)
            );
        } catch (\Throwable $e) {
            $this->investmentError = $e;
        }
    }

    /**
     * Get Loan by name
     *
     * @param string $name Loan name
     *
     * @return Model\Loan
     */
    public static function getLoan(string $name)
    {
        return self::$loans[$name];
    }

    /**
     * @Then paid amount of :trancheName tranche of :loanName loan should be :amount
     */
    public function trancheOfLoanHaveAmount(
        string $trancheName,
        string $loanName,
        float $amount
    )
    {
        $tranche = self::getLoan($loanName)->getTrancheById($trancheName);

        Assert::assertEquals($amount, $tranche->getPaidAmount());
    }

    /**
     * @Then payment should be accepted with error :error
     */
    public function investmentNotAccepted(string $error)
    {
        Assert::assertEquals($error, $this->investmentError->getMessage());
    }
}
