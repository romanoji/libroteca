<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Lumen\Models;

use Illuminate\Database\Eloquent\Model;
use MartinGeorgiev\Utils\DataStructure;
use RJozwiak\Libroteca\Domain\Model\Book\Author;

class Book extends Model
{
    public const TABLE = 'books';

    /** @var string */
    protected $table = self::TABLE;

    /** @var bool */
    public $timestamps = false;

    /**
     * @param string $authors
     * @return array
     */
    protected function getAuthorsAttribute(string $authors)
    {
        // TODO: "authors" column type to json?
        return DataStructure::transformPostgresTextArrayToPHPArray($authors);
    }
}
