<?php declare(strict_types=1);

namespace Deg\Linvest\Model\Factory;

use Deg\Linvest\Model;

class Loan
{
    public function create(\Datetime $startAt, \Datetime $entAt): Model\Loan
    {
        return new Model\Loan($startAt, $entAt);
    }
}
