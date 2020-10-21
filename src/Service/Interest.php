<?php declare(strict_types=1);

namespace Deg\Linvest\Service;

use DateInterval;
use DateTime;
use Deg\Linvest\Model;

class Interest
{
    /** @var Model\Factory\Payment */
    private $paymentFactory;

    /**
     * Constructor
     *
     * @param Model\Factory\Payment $paymentFactory Payment factory
     */
    public function __construct(
        Model\Factory\Payment $paymentFactory
    )
    {
        $this->paymentFactory = $paymentFactory;
    }

    /**
     * Calculate and put interest money to investor wallet
     *
     * @param Model\Investor $investor Investor
     * @param DateTime       $calcAt   Calculation date
     */
    public function processForInvestor(
        Model\Investor $investor,
        DateTime $calcAt
    ): void
    {
        //  get last date of prev month
        $calcAt = DateTime::createFromFormat(
            'd/m/Y',
            $calcAt->sub(new DateInterval('P1M'))->format('t/m/Y')
        );

        foreach ($investor->getPayments() as $payment) {
            $interest = $this->calculateForPayment($payment, $calcAt);

            $investor->addPayment(
                $this->paymentFactory->create(
                    $payment->getTranche(),
                    $investor,
                    round($interest * -1, 2),
                    $calcAt,
                    Model\Payment::TYPE_INTEREST
                )
            );
        }
    }

    /**
     * Get interest for each investment(payment)
     *
     * @param Model\Payment $payment Payment
     * @param DateTime      $calcAt  Calculation date
     *
     * @return float
     */
    protected function calculateForPayment(Model\Payment $payment, DateTime $calcAt): float
    {
        //  get count of days in prev month
        $daysInMonth = (int) $calcAt->format('t');

        //  define count of days between payment date and last day of month
        $daysInvest = $calcAt->diff($payment->getPaidAt())->days + 1;

        //  investment payment date was not in this month
        if ($daysInvest > $daysInMonth) {
            $daysInvest = $daysInMonth;
        }

        $dailyInterestRate = $payment->getTranche()->getInterestRate() / $daysInMonth;
        return $payment->getAmount() / 100 * $dailyInterestRate * $daysInvest;
    }
}
