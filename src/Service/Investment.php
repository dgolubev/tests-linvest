<?php declare(strict_types=1);

namespace Deg\Linvest\Service;

use Deg\Linvest\Model;

class Investment
{
    public const ERR_LOAD_NOT_AVAILABLE = 'Loan not available';
    public const ERR_TRANCHE_NOT_FOUND = 'Tranche not found';
    public const ERR_TRANCHE_CANNOT_INVEST = 'Amount not accepted';
    public const ERR_INVESTOR_NO_MONEY = 'Investor does not have enough money';

    /** @var Model\Factory\Payment */
    private $paymentFactory;

    /**
     * Constructor.
     *
     * @param Model\Factory\Payment $paymentFactory Payment Model factory
     */
    public function __construct(
        Model\Factory\Payment $paymentFactory
    ) {
        $this->paymentFactory = $paymentFactory;
    }

    /**
     * Make investment to load
     *
     * @param Model\Loan     $loan       Load to invest
     * @param string         $trancheId  Tranche identifier
     * @param Model\Investor $investor   Investor
     * @param float          $amount     Investment amount
     * @param \DateTime      $investedAt Investment date time
     */
    public function investToLoan(
        Model\Loan $loan,
        string $trancheId,
        Model\Investor $investor,
        float $amount,
        \DateTime $investedAt
    ): void {
        if ($investor->getWalletAmount() < $amount) {
            throw new \RuntimeException(self::ERR_INVESTOR_NO_MONEY);
        }

        if (! $loan->isOpen()) {
            throw new \RuntimeException(self::ERR_LOAD_NOT_AVAILABLE);
        }

        $tranche = $loan->getTrancheById($trancheId);
        if (null === $tranche) {
            throw new \RuntimeException(self::ERR_TRANCHE_NOT_FOUND);
        }

        if (! $tranche->canInvest($amount)) {
            throw new \RuntimeException(self::ERR_TRANCHE_CANNOT_INVEST);
        }

        $payment = $this->paymentFactory->create(
            $tranche, $investor, $amount, $investedAt, Model\Payment::TYPE_INVESTMENT
        );

        $tranche->addPayment($payment);
        $investor->addPayment($payment);
    }
}
