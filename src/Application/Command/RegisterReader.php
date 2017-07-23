<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Command;

use RJozwiak\Libroteca\Application\Command;

class RegisterReader implements Command
{
    /** @var int|string */
    public $readerID;

    /** @var string */
    public $name;

    /** @var string */
    public $surname;

    /** @var string */
    public $email;

    /** @var string */
    public $phone;

    /**
     * RegisterReader constructor.
     * @param int|string $readerID
     * @param string $name
     * @param string $surname
     * @param string $email
     * @param string $phone
     */
    public function __construct(
        $readerID,
        string $name,
        string $surname,
        string $email,
        string $phone
    ) {
        $this->readerID = $readerID;
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->phone = $phone;
    }
}
