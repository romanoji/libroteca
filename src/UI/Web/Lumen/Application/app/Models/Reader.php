<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Lumen\Models;

class Reader extends BaseModel
{
    public const COLLECTION = 'readers';

    /** @var string */
    protected $collection = self::COLLECTION;

    /** @var bool */
    public $timestamps = false;
}
