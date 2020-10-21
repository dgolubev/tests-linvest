<?php declare(strict_types=1);

namespace Deg\Linvest\Service\Factory;

use Deg\Linvest\Model;
use Deg\Linvest\Service;

class Investment
{
    public function create(): Service\Investment
    {
        return new Service\Investment(
            new Model\Factory\Payment()
        );
    }
}
