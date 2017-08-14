<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Application\Command;

use RJozwiak\Libroteca\Application\Command\ImportBooks;
use RJozwiak\Libroteca\Application\Command\ImportBooks\BookData;
use RJozwiak\Libroteca\Application\Command\ImportBooks\ImportFileLoader;

class CsvFileLoader implements ImportFileLoader
{
    /**
     * @param \SplFileInfo $file
     * @return BookData[]
     * @throws \InvalidArgumentException
     */
    public function loadBooksData(\SplFileInfo $file): array
    {
        $this->assertValidFileFormat($file);

        $csvFile = $file->openFile();
        $csvFile->setFlags(
            \SplFileObject::DROP_NEW_LINE |
            \SplFileObject::READ_AHEAD |
            \SplFileObject::SKIP_EMPTY |
            \SplFileObject::READ_CSV
        );

        $data = [];
        foreach ($csvFile as $index => $row) {
            if ($index === 0) {
                continue;
            }

            [$isbn, $title, $authors, $amount] = $row;
            $data[] = new BookData(
                $isbn,
                $title,
                array_map('trim', explode(',', $authors)),
                (int) $amount
            );
        }

        return $data;
    }

    /**
     * @param \SplFileInfo $file
     * @throws \InvalidArgumentException
     */
    private function assertValidFileFormat(\SplFileInfo $file)
    {
        $csvHeaders = $file->openFile()->fgetcsv();

        if (!$csvHeaders) {
            throw new \InvalidArgumentException("File has invalid csv format.");
        }

        if ($csvHeaders !== ImportBooks::FIELDS) {
            throw new \InvalidArgumentException(
                sprintf("File has to have %d headers: %s"),
                count(ImportBooks::FIELDS),
                implode(', ', ImportBooks::FIELDS)
            );
        }
    }
}
