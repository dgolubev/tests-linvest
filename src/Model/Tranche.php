<?php declare(strict_types=1);

namespace Deg\Linvest\Model;

class Tranche
{
    /** @var float */
    private $interestRate;
    /** @var float */
    private $amount;

    /** @var Payment[] */
    private $payments = [];

    /**
     * Tranche constructor.
     *
     * @param float $interestRate Interest rate
     * @param float $amount       Tranche amount
     */
    public function __construct(float $interestRate, float $amount)
    {
        $this->interestRate = $interestRate;
        $this->amount = $amount;
    }

    /**
     * Get interest rate
     * @return float
     */
    public function getInterestRate(): float
    {
        return $this->interestRate;
    }

    /**
     * Get Maximum allowed amount
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * Get paid amount
     * @return float
     */
    public function getPaidAmount(): float
    {
        return \array_reduce(
            $this->payments,
            function ($acc, Payment $payment) {
                return $acc + $payment->getAmount();
            }
        ) ?: 0;
    }

    /**
     * Add payment
     *
     * @param Payment $payment Payment
     *
     * @return Tranche
     */
    public function addPayment(Payment $payment): Tranche
    {
        $this->payments[] = $payment;

        return $this;
    }

    /**
     * Get Tranche payments
     * @return Payment[]
     */
    public function getPayments(): array
    {
        return $this->payments;
    }

    /**
     * Check can amount be invested
     *
     * @param float $investAmount Investing amount
     *
     * @return bool
     */
    public function canInvest(float $investAmount): bool
    {
        return $investAmount <= $this->amount - $this->getPaidAmount();
    }
}
