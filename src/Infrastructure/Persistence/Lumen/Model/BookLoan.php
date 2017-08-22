<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\Persistence\Lumen\Model;

class BookLoan extends BaseModel
{
    public const COLLECTION = 'book_loans';

    /** @var string */
    protected $collection = self::COLLECTION;

    /** @var bool */
    public $timestamps = false;

    /** @var array */
    protected $casts = [
        'due_date' => 'date',
        'end_date' => 'datetime'
    ];

    /**
     * @return array
     */
    public function toArray()
    {
        $data = parent::toArray();
        $data['due_date'] = $this->attributes['due_date']->toDateTime()->format('Y-m-d');

        return $data;
    }
}
