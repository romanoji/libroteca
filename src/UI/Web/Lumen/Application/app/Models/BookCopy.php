<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Lumen\Models;

use Illuminate\Database\Eloquent\Model;

class BookCopy extends Model
{
    public const TABLE = 'book_copies';

    /** @var string */
    protected $table = self::TABLE;

    /** @var bool */
    public $timestamps = false;
}
