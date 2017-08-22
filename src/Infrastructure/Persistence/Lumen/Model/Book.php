<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Persistence\Lumen\Model;

class Book extends BaseModel
{
    public const COLLECTION = 'books';

    /** @var string */
    protected $collection = self::COLLECTION;

    /** @var bool */
    public $timestamps = false;
}
