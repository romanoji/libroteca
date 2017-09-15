<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Command;

use RJozwiak\Libroteca\Application\Command;

class ImportBooks implements Command
{
    public const FIELDS = ['isbn', 'title', 'authors', 'amount'];

    /** @var \SplFileInfo */
    private $file;

    /**
     * @param \SplFileInfo $file
     */
    public function __construct(\SplFileInfo $file)
    {
        $this->file = $file;
    }

    /**
     * @return \SplFileInfo
     */
    public function file(): \SplFileInfo
    {
        return $this->file;
    }
}
