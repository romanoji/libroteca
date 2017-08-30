<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Domain\Model\BookLoan;

use RJozwiak\Libroteca\Infrastructure\Persistence\Lumen;
use Ramsey\Uuid\Uuid;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyID;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoan;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoanID;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoanRepository;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\BookLoanNotFoundException;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderID;
use RJozwiak\Libroteca\Infrastructure\Serialization\Basic\Deserializer;

class LumenBookLoanRepository implements BookLoanRepository
{
    /** @var Deserializer */
    private $deserializer;

    /**
     * @param Deserializer $deserializer
     */
    public function __construct(Deserializer $deserializer)
    {
        $this->deserializer = $deserializer;
    }

    /**
     * @return BookLoanID
     */
    public function nextID(): BookLoanID
    {
        return new BookLoanID(Uuid::uuid4()->toString());
    }

    /**
     * @param BookLoan $bookLoan
     */
    public function save(BookLoan $bookLoan)
    {
        $attributes = ['id' => $bookLoan->id()->id()];
        $data = [
            'book_copy_id' => $bookLoan->bookCopyID()->id(),
            'reader_id' => $bookLoan->readerID()->id(),
            'due_date' => $bookLoan->dueDate(),
            'has_ended' => $bookLoan->hasEnded(),
            'end_date' => $bookLoan->endDate() ?: null,
            'is_prolonged' => $bookLoan->isProlonged(),
            'remarks' => $bookLoan->remarks()
        ];

        Lumen\Model\BookLoan::updateOrCreate($attributes, $data);
    }

    /**
     * @param BookLoanID $id
     * @return BookLoan
     * @throws BookLoanNotFoundException
     */
    public function get(BookLoanID $id): BookLoan
    {
        $data = Lumen\Model\BookLoan::find($id->id());

        if ($data === null) {
            throw new BookLoanNotFoundException();
        }

        return $this->createBookLoan(
            $data['id'],
            $data['book_copy_id'],
            $data['reader_id'],
            $data['due_date'],
            $data['has_ended'],
            $data['end_date'],
            $data['is_prolonged'],
            $data['remarks']
        );
    }

    /**
     * @param BookCopyID $bookCopyID
     * @return null|BookLoan
     */
    public function findOngoingByBookCopyID(BookCopyID $bookCopyID): ?BookLoan
    {
        $data = Lumen\Model\BookLoan::where([
            'book_copy_id' => $bookCopyID->id(),
            'has_ended' => false
        ])->first();

        if ($data === null) {
            return null;
        }

        return $this->createBookLoan(
            $data['id'],
            $data['book_copy_id'],
            $data['reader_id'],
            $data['due_date'],
            $data['has_ended'],
            $data['end_date'],
            $data['is_prolonged'],
            $data['remarks']
        );
    }

    /**
     * @param ReaderID $readerID
     * @return BookLoan[]
     */
    public function findOngoingByReaderID(ReaderID $readerID): array
    {
        $data = Lumen\Model\BookLoan::where([
            'reader_id' => $readerID->id(),
            'has_ended' => false
        ])->get();

        $bookLoans = [];
        foreach ($data as $row) {
            $bookLoans = $this->createBookLoan(
                $row['id'],
                $row['book_copy_id'],
                $row['reader_id'],
                $row['due_date'],
                $row['has_ended'],
                $row['end_date'],
                $row['is_prolonged'],
                $row['remarks']
            );
        }

        return $bookLoans;
    }

    /**
     * @param int|string $id
     * @param int|string $bookCopyID
     * @param int|string $readerID
     * @param \DateTimeInterface $dueDate
     * @param bool $hasEnded
     * @param null|\DateTimeInterface $endDate
     * @param bool $isProlonged
     * @param string $remarks
     * @return BookLoan
     */
    private function createBookLoan(
        $id,
        $bookCopyID,
        $readerID,
        \DateTimeInterface $dueDate,
        bool $hasEnded,
        ?\DateTimeInterface $endDate,
        bool $isProlonged,
        string $remarks
    ): BookLoan {
        $properties = [
            'id' => new BookLoanID($id),
            'bookCopyID' => new BookCopyID($bookCopyID),
            'readerID' => new ReaderID($readerID),
            'dueDate' => new \DateTimeImmutable('@'.$dueDate->getTimestamp()),
            'ended' => $hasEnded,
            'endDate' => $endDate ? new \DateTimeImmutable('@'.$endDate->getTimestamp()) : null,
            'prolonged' => $isProlonged,
            'remarks' => $remarks
        ];

        return $this->deserializer->deserialize($properties, BookLoan::class);
    }
}
