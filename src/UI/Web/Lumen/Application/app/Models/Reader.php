<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Lumen\Models;

use Illuminate\Database\Eloquent\Model;

class Reader extends Model
{
    public const TABLE = 'readers';

    /** @var string */
    protected $table = self::TABLE;

    /** @var bool */
    public $timestamps = false;
}
