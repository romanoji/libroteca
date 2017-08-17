<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Domain\Model\BookLoan;

use Illuminate\Support\Facades\DB;
use RJozwiak\Libroteca\Lumen;
use Ramsey\Uuid\Uuid;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyID;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoan;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoanID;
use RJozwiak\Libroteca\Domain\Model\BookLoan\BookLoanRepository;
use RJozwiak\Libroteca\Domain\Model\BookLoan\Exception\BookLoanNotFoundException;
use RJozwiak\Libroteca\Domain\Model\Reader\ReaderID;
use RJozwiak\Libroteca\Infrastructure\Serialization\Basic\Deserializer;

class LumenQBBookLoanRepository implements BookLoanRepository
{
    private const TABLE = 'book_loans';

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
            'end_date' => $bookLoan->endDate(),
            'is_prolonged' => $bookLoan->isProlonged(),
            'remarks' => $bookLoan->remarks()
        ];

        DB::table(Lumen\Models\BookLoan::TABLE)->updateOrInsert($attributes, $data);
    }

    /**
     * @param BookLoanID $id
     * @return BookLoan
     * @throws BookLoanNotFoundException
     */
    public function get(BookLoanID $id): BookLoan
    {
        $data = DB::table(Lumen\Models\BookLoan::TABLE)::find($id->id());

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
        $data = DB::table(Lumen\Models\BookLoan::TABLE)::where([
            'book_copy_id' => $bookCopyID->id(),
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
     * @param ReaderID $readerID
     * @return BookLoan[]
     */
    public function findOngoingByReaderID(ReaderID $readerID): array
    {
        $data = DB::table(Lumen\Models\BookLoan::TABLE)::where([
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
     * @param \DateTimeImmutable $dueDate
     * @param bool $hasEnded
     * @param \DateTimeImmutable $endDate
     * @param bool $isProlonged
     * @param string $remarks
     * @return BookLoan
     */
    private function createBookLoan(
        $id,
        $bookCopyID,
        $readerID,
        \DateTimeImmutable $dueDate,
        bool $hasEnded,
        \DateTimeImmutable $endDate,
        bool $isProlonged,
        string $remarks
    ) : BookLoan {
        $properties = [
            'id' => new BookLoanID($id),
            'bookCopyID' => new BookCopyID($bookCopyID),
            'readerID' => new ReaderID($readerID),
            'dueDate' => $dueDate,
            'ended' => $hasEnded,
            'endDate' => $endDate,
            'prolonged' => $isProlonged,
            'remarks' => $remarks
        ];

        return $this->deserializer->deserialize($properties, BookLoan::class);
    }
}
