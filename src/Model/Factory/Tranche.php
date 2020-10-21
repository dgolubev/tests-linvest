<?php declare(strict_types=1);

namespace Deg\Linvest\Model\Factory;

use Deg\Linvest\Model;

class Tranche
{
    public function create(float $interestRate, float $amountMax): Model\Tranche
    {
        return new Model\Tranche($interestRate, $amountMax);
    }
}
