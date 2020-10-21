<?php declare(strict_types=1);

namespace Deg\Linvest\UTests\Service;

use Deg\Linvest\Model;
use Deg\Linvest\Service\Investment;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * @covers \Deg\Linvest\Service\Investment
 */
class InvestmentTest extends MockeryTestCase
{
    private const TRANCHE_ID = '9999';
    private const AMOUNT = 8888.88;
    private const INVESTED_AT = '01/01/2020';

    /** @var Model\Factory\Payment | m\MockInterface */
    private $mockPaymentModelFactory;

    /** @var Investment */
    private Investment $sut;

    /** @var Model\Loan | m\MockInterface */
    private $mockLoan;
    /** @var Model\Tranche | m\MockInterface */
    private $mockTranche;
    /** @var Model\Investor | m\MockInterface */
    private $mockInvestor;
    private \DateTime $investedAt;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockLoan = m::mock(Model\Loan::class);
        $this->mockTranche = m::mock(Model\Tranche::class);
        $this->mockInvestor = m::mock(Model\Investor::class);

        $this->investedAt = new \DateTime(self::INVESTED_AT);

        $this->mockPaymentModelFactory = m::mock(Model\Factory\Payment::class);

        $this->sut = new Investment($this->mockPaymentModelFactory);
    }

    public function testInvestToLoanOk()
    {
        $this->mockInvestor->expects('getWalletAmount')->andReturn(self::AMOUNT);

        $this->mockLoan->expects('isOpen')->with()->andReturnTrue();
        $this->mockLoan->expects('getTrancheById')->with(self::TRANCHE_ID)->andReturn($this->mockTranche);

        $this->mockTranche->expects('canInvest')->andReturnTrue();

        $mockPayment = m::mock(Model\Payment::class);
        $this->mockPaymentModelFactory
            ->expects('create')
            ->with(
                $this->mockTranche,
                $this->mockInvestor,
                self::AMOUNT,
                $this->investedAt,
                Model\Payment::TYPE_INVESTMENT
            )
            ->andReturn($mockPayment);

        $this->mockTranche->expects('addPayment')->with($mockPayment);
        $this->mockInvestor->expects('addPayment')->with($mockPayment);

        $this->sut->investtoLoan(
            $this->mockLoan,
            self::TRANCHE_ID,
            $this->mockInvestor,
            self::AMOUNT,
            $this->investedAt,
        );
    }

    public function testInvestToLoanFailNotEnoughInWallet()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(Investment::ERR_INVESTOR_NO_MONEY);

        $this->mockInvestor->expects('getWalletAmount')->andReturn(self::AMOUNT - 1);

        $this->sut->investtoLoan(
            $this->mockLoan,
            self::TRANCHE_ID,
            $this->mockInvestor,
            self::AMOUNT,
            $this->investedAt,
        );
    }

    public function testInvestToLoanFailLoanIsClosed()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(Investment::ERR_LOAD_NOT_AVAILABLE);

        $this->mockInvestor->expects('getWalletAmount')->andReturn(self::AMOUNT);

        $this->mockLoan->expects('isOpen')->with()->andReturnFalse();

        $this->sut->investtoLoan(
            $this->mockLoan,
            self::TRANCHE_ID,
            $this->mockInvestor,
            self::AMOUNT,
            $this->investedAt,
        );
    }

    public function testInvestToLoanFailTrancheNotFound()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(Investment::ERR_TRANCHE_NOT_FOUND);

        $this->mockInvestor->expects('getWalletAmount')->andReturn(self::AMOUNT);

        $this->mockLoan->expects('isOpen')->with()->andReturnTrue();
        $this->mockLoan->expects('getTrancheById')->with(self::TRANCHE_ID)->andReturnNull();

        $this->sut->investtoLoan(
            $this->mockLoan,
            self::TRANCHE_ID,
            $this->mockInvestor,
            self::AMOUNT,
            $this->investedAt,
        );
    }

    public function testInvestToLoanFailTrancheNotAvailable()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(Investment::ERR_TRANCHE_CANNOT_INVEST);

        $this->mockInvestor->expects('getWalletAmount')->andReturn(self::AMOUNT);

        $this->mockLoan->expects('isOpen')->with()->andReturnTrue();
        $this->mockLoan->expects('getTrancheById')->with(self::TRANCHE_ID)->andReturn($this->mockTranche);

        $this->mockTranche->expects('canInvest')->andReturnFalse();

        $this->sut->investtoLoan(
            $this->mockLoan,
            self::TRANCHE_ID,
            $this->mockInvestor,
            self::AMOUNT,
            $this->investedAt,
        );
    }
}
