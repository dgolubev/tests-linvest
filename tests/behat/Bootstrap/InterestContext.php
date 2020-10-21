<?php declare(strict_types=1);

namespace Deg\Linvest\BTests\Bootstrap;

use Behat\Behat\Context\Context;
use Deg\Linvest\Model;
use Deg\Linvest\Service;

class InterestContext implements Context
{
    /**
     * InterestContext constructor.
     */
    public function __construct()
    {
        $this->interestSrv = (new Service\Factory\Interest())->create();
    }

    /**
     * @Given run interest processing for investor :investor on :calcAt
     */
    public function interestReceived(string $investor, string $calcAt): void
    {
        $this->interestSrv->processForInvestor(
            InvestorContext::getInvestor($investor),
            \DateTime::createFromFormat(Model\Interest::DATE_FORMAT, $calcAt)
        );
    }
}
