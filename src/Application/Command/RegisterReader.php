<?php

namespace RJozwiak\Libroteca\Application\Command;

use RJozwiak\Libroteca\Application\Command;

class RegisterReader implements Command
{
    /** @var string */
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
     * @param string $readerID
     * @param string $name
     * @param string $surname
     * @param string $email
     * @param string $phone
     */
    public function __construct(
        string $readerID,
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
