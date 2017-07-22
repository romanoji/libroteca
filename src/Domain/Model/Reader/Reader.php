<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\Reader;

use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopy;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyID;
use RJozwiak\Libroteca\Domain\Model\Reader\BookLoan\BookLoanFactory;
use RJozwiak\Libroteca\Domain\Model\Reader\BookLoan\BookLoanID;
use RJozwiak\Libroteca\Domain\Model\Reader\BookLoan\Exception\BookLoanAlreadyEndedException;
use RJozwiak\Libroteca\Domain\Model\Reader\BookLoan\Exception\EndingOverdueLoanWithoutRemarksException;
use RJozwiak\Libroteca\Domain\Model\Reader\Exception\MaxNumberOfBookLoansExceededException;
use RJozwiak\Libroteca\Domain\Model\Reader\BookLoan\BookLoan;
use RJozwiak\Libroteca\Domain\Model\Reader\Exception\ReaderCannotReturnNotBorrowedBook;

class Reader
{
    private const MAX_ONGOING_LOANS = 5;

    /** @var ReaderID */
    private $id;

    /** @var Name */
    private $name;

    /** @var Surname */
    private $surname;

    /** @var Email */
    private $email;

    /** @var Phone */
    private $phone;

    /** @var BookLoan[] */ // TODO: add ArrayCollection?
    private $ongoingBookLoans;

    /**
     * Reader constructor.
     * @param ReaderID $id
     * @param Name $name
     * @param Surname $surname
     * @param Email $email
     * @param Phone $phone
     */
    public function __construct(
        ReaderID $id,
        Name $name,
        Surname $surname,
        Email $email,
        Phone $phone
        // TODO: Address (Street, City, PostCode)
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->phone = $phone;
        $this->ongoingLoans = [];

        // TODO: ReaderRegistered event
    }

    /**
     * @return ReaderID
     */
    public function id() : ReaderID
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function fullname() : string
    {
        return $this->name.' '.$this->surname;
    }

    /**
     * @return Email
     */
    public function email() : Email
    {
        return $this->email;
    }

    /**
     * @return Phone
     */
    public function phone() : Phone
    {
        return $this->phone;
    }

    /**
     * @param BookLoanID $bookLoanID
     * @return null|BookLoan
     */
    public function bookLoan(BookLoanID $bookLoanID) : ?BookLoan
    {
        /** @var BookLoan $ongoingLoan */
        foreach ($this->ongoingLoans as $ongoingLoan) {
            if ($ongoingLoan->id()->equals($bookLoanID)) {
                return $ongoingLoan;
            }
        }

        return null; // TODO: exception?
    }

    /**
     * @param BookLoanFactory $bookLoanFactory
     * @param BookCopy $bookCopy
     * @param \DateTimeImmutable $dueDate
     * @throws MaxNumberOfBookLoansExceededException
     */
    public function borrowBookCopy(
        BookLoanFactory $bookLoanFactory,
        BookCopy $bookCopy,
        \DateTimeImmutable $dueDate
    ) : void {
        if (count($this->ongoingLoans) >= self::MAX_ONGOING_LOANS) {
            throw new MaxNumberOfBookLoansExceededException();
        }

        $loan = $bookLoanFactory->create($bookCopy->id(), $this->id(), $dueDate);

        $this->ongoingLoans[] = $loan; // TODO: filter out duplicates (if we get any?)
    }

    /**
     * @param BookCopyID $bookCopyID
     * @param \DateTimeImmutable $endDate
     * @param string|null $remarks
     * @throws BookLoanAlreadyEndedException
     * @throws EndingOverdueLoanWithoutRemarksException
     * @throws ReaderCannotReturnNotBorrowedBook
     */
    public function returnBookCopy(
        BookCopyID $bookCopyID,
        \DateTimeImmutable $endDate,
        string $remarks = null
    ) : void {
        /** @var BookLoan $ongoingLoan */
        foreach ($this->ongoingLoans as $index => $ongoingLoan) {
            if ($ongoingLoan->bookCopyID()->equals($bookCopyID)) {
                $ongoingLoan->endLoan($endDate, $remarks);
                unset($this->ongoingLoans[$index]);
                return; // TODO: return loan?
            }
        }

        throw new ReaderCannotReturnNotBorrowedBook();
    }
}
