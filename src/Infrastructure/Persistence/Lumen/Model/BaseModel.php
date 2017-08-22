<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Persistence\Lumen\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class BaseModel extends Model
{
    // TODO: find a way to use _id as primary key
    /** @var array */
    protected $hidden = ['_id'];

    /** @var string */
    protected $primaryKey = 'id';

    /** @var array */
    protected $guarded = [];
}
