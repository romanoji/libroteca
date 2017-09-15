<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Application\Command;

use RJozwiak\Libroteca\Application\Command;

class RegisterReader implements Command
{
    /** @var int|string */
    private $readerID;

    /** @var string */
    private $name;

    /** @var string */
    private $surname;

    /** @var string */
    private $email;

    /** @var string */
    private $phone;

    /**
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

    /**
     * @return int|string
     */
    public function readerID()
    {
        return $this->readerID;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function surname(): string
    {
        return $this->surname;
    }

    /**
     * @return string
     */
    public function email(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function phone(): string
    {
        return $this->phone;
    }
}
