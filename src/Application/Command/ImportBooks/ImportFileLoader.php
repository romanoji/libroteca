<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Command\ImportBooks;

interface ImportFileLoader
{
    /**
     * @param \SplFileInfo $file
     * @return BookData[]
     * @throws \InvalidArgumentException
     */
    public function loadBooksData(\SplFileInfo $file): array;
}
