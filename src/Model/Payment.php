<?php declare(strict_types=1);

namespace Deg\Linvest\Model;

class Payment
{
    public const DATE_FORMAT = 'd/m/Y';

    public const TYPE_INVESTMENT = 1;
    public const TYPE_INTEREST = 2;

    /** @var Investor */
    private $investor;
    /** @var Tranche */
    private $tranche;
    /** @var float */
    private $amount;
    /** @var \DateTime */
    private $paidAt;
    /** @var int */
    private $type;

    /**
     * Payment constructor.
     *
     * @param Tranche   $tranche  Tranche
     * @param Investor  $investor Investor
     * @param float     $amount   Payment amount
     * @param \DateTime $paidAt   Payment date tme
     * @param int $type   Payment type
     */
    public function __construct(
        Tranche $tranche,
        Investor $investor,
        float $amount,
        \DateTime $paidAt,
        int $type
    ) {
        $this->tranche = $tranche;
        $this->investor = $investor;
        $this->amount = $amount;
        $this->paidAt = $paidAt;
        $this->type = $type;
    }

    /**
     * Get Investor
     * @return Investor
     */
    public function getInvestor(): Investor
    {
        return $this->investor;
    }

    /**
     * Get loan tranche related
     * @return Tranche
     */
    public function getTranche(): Tranche
    {
        return $this->tranche;
    }

    /**
     * Payment amount
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * Payment date time
     * @return \DateTime
     */
    public function getPaidAt(): \DateTime
    {
        return $this->paidAt;
    }
}
