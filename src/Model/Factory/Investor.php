<?php declare(strict_types=1);

namespace Deg\Linvest\Model\Factory;

use Deg\Linvest\Model;

class Investor
{
    public function create(string $name, float $amount): Model\Investor
    {
        return new Model\Investor($name, $amount);
    }
}
