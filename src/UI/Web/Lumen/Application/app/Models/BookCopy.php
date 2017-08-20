<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Lumen\Models;

class BookCopy extends BaseModel
{
    public const COLLECTION = 'book_copies';

    /** @var string */
    protected $collection = self::COLLECTION;

    /** @var bool */
    public $timestamps = false;
}
