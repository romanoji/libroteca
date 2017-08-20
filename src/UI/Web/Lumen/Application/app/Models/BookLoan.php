<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Lumen\Models;

class BookLoan extends BaseModel
{
    public const COLLECTION = 'book_loans';

    /** @var string */
    protected $collection = self::COLLECTION;

    /** @var bool */
    public $timestamps = false;
}
