<?php declare(strict_types=1);

namespace Deg\Linvest\Model;

class Investor
{
    /** @var string */
    private $name;
    /** @var float */
    private $walletAmount;

    /** @var Payment[] */
    private $payments = [];

    /**
     * Investor constructor.
     *
     * @param string $name Investor name
     * @param float  $amount
     */
    public function __construct(string $name, float $amount)
    {
        $this->name = $name;
        $this->walletAmount = $amount;
    }

    /**
     * Get investor name
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get intestor wallet amount
     * @return float
     */
    public function getWalletAmount(): float
    {
        return $this->walletAmount;
    }

    /**
     * Investor payments
     * @return Payment[]
     */
    public function getPayments(): array
    {
        return $this->payments;
    }

    /**
     * Add payment
     *
     * @param Payment $payment Payment
     *
     * @return Investor
     */
    public function addPayment(Payment $payment): Investor
    {
        $this->walletAmount -= $payment->getAmount();

        $this->payments[] = $payment;

        return $this;
    }
}
