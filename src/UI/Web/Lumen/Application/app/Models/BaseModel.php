<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Lumen\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class BaseModel extends Model
{
    /** @var array */
    protected $hidden = ['_id'];

    /** @var string */
    protected $primaryKey = 'id';

//    /**
//     * @return array
//     */
//    public function toArray()
//    {
//        return ['id' => $this['id']] + parent::toArray();
//    }
}
