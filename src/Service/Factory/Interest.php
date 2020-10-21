<?php declare(strict_types=1);

namespace Deg\Linvest\Service\Factory;

use Deg\Linvest\Model;
use Deg\Linvest\Service;

class Interest
{
    public function create(): Service\Interest
    {
        return new Service\Interest(
            new Model\Factory\Payment()
        );
    }
}
