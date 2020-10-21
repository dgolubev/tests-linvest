<?php declare(strict_types=1);

namespace Deg\Linvest\Model\Factory;

use Deg\Linvest\Model;

class Payment
{
    public function create(
        Model\Tranche $tranche,
        Model\Investor $investor,
        float $amount,
        \DateTime $paid,
        int $type
    ): Model\Payment {
        return new Model\Payment($tranche, $investor, $amount, $paid, $type);
    }
}
