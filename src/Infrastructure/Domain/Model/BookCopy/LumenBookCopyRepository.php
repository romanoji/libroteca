<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Domain\Model\BookCopy;

use Ramsey\Uuid\Uuid;
use RJozwiak\Libroteca\Domain\Model\Book\BookID;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopy;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyID;
use RJozwiak\Libroteca\Domain\Model\BookCopy\BookCopyRepository;
use RJozwiak\Libroteca\Domain\Model\BookCopy\Exception\BookCopyNotFoundException;
use RJozwiak\Libroteca\Lumen;

class LumenBookCopyRepository implements BookCopyRepository
{
    /**
     * @return BookCopyID
     */
    public function nextID(): BookCopyID
    {
        return new BookCopyID(Uuid::uuid4()->toString());
    }

    /**
     * @param BookCopy $bookCopy
     */
    public function save(BookCopy $bookCopy)
    {
        $data = [
            'book_id' => $bookCopy->bookID()->id(),
            'remarks' => $bookCopy->remarks()
        ];

        Lumen\Models\BookCopy::where('id', $bookCopy->id()->id())
            ->update($data, ['upsert' => true]);
    }

    /**
     * @param BookCopyID $id
     * @return BookCopy
     * @throws BookCopyNotFoundException
     */
    public function get(BookCopyID $id): BookCopy
    {
        $data = Lumen\Models\BookCopy::find($id->id());

        if ($data === null) {
            throw new BookCopyNotFoundException();
        }

        return $this->createBookCopy($data['id'], $data['book_id'], $data['remarks']);
    }

    /**
     * @param BookID $bookID
     * @return BookCopy[]
     */
    public function findByBookID(BookID $bookID): array
    {
        $data = Lumen\Models\BookCopy::where('book_id', $bookID->id())->get();

        $bookCopies = [];
        foreach ($data as $row) {
            $bookCopies[] = $this->createBookCopy($row['id'], $row['book_id'], $row['remarks']);
        }

        return $bookCopies;
    }

    /**
     * @param int|string $id
     * @param int|string $bookID
     * @param string $remarks
     * @return BookCopy
     */
    private function createBookCopy($id, $bookID, string $remarks): BookCopy
    {
        return new BookCopy(
            new BookCopyID($id),
            new BookID($bookID),
            $remarks
        );
    }
}
