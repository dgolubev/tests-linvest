<?php declare(strict_types=1);

namespace Deg\Linvest\Model;

class Loan
{
    public const DATE_FORMAT = 'd/m/Y';

    /** @var \DateTime */
    private $dateStart;
    /** @var \DateTime */
    private $dateEnd;

    /** @var bool */
    private $isOpen = true;
    /** @var Tranche[] */
    private $tranches = [];

    /**
     * Loan constructor.
     *
     * @param \DateTime $dateStart
     * @param \DateTime $dateEnd
     */
    public function __construct(\DateTime $dateStart, \DateTime $dateEnd)
    {
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
    }

    /**
     * Get date start
     * @return \DateTime
     */
    public function getDateStart(): \DateTime
    {
        return $this->dateStart;
    }

    /**
     * Get loan end date
     * @return \DateTime
     */
    public function getDateEnd(): \DateTime
    {
        return $this->dateEnd;
    }

    /**
     * Is load open
     * @return bool
     */
    public function isOpen(): bool
    {
        return true === $this->isOpen;
    }

    /**
     * Close load
     * @return $this
     */
    public function closeLoan(): Loan
    {
        $this->isOpen = false;

        return $this;
    }

    /**
     * Add tranche
     *
     * @param string  $trancheId Tranche identifier
     * @param Tranche $tranche   Tranche
     *
     * @return Loan
     */
    public function addTranche(string $trancheId, Tranche $tranche): Loan
    {
        $this->tranches[$trancheId] = $tranche;

        return $this;
    }

    /**
     * Get Loan traches collection
     * @return array
     */
    public function getTranches(): array
    {
        return $this->tranches;
    }

    /**
     * Get Tranche by id
     *
     * @param string $trancheId Tranche identifier
     *
     * @return Tranche|null
     */
    public function getTrancheById(string $trancheId): ?Tranche
    {
        return $this->tranches[$trancheId] ?: null;
    }
}
