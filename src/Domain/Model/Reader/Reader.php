<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\Reader;

class Reader
{
    /** @var ReaderID */
    private $id;

    /** @var Name */
    private $name;

    /** @var Surname */
    private $surname;

    /** @var Email */
    private $email;

    /** @var Phone */
    private $phone;

    /**
     * Reader constructor.
     * @param ReaderID $id
     * @param Name $name
     * @param Surname $surname
     * @param Email $email
     * @param Phone $phone
     */
    public function __construct(
        ReaderID $id,
        Name $name,
        Surname $surname,
        Email $email,
        Phone $phone
        // TODO: Address (Street, City, PostCode)
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->phone = $phone;
        $this->ongoingLoans = [];

        // TODO: ReaderRegistered event
    }

    /**
     * @return ReaderID
     */
    public function id() : ReaderID
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function fullname() : string
    {
        return $this->name.' '.$this->surname;
    }

    /**
     * @return Email
     */
    public function email() : Email
    {
        return $this->email;
    }

    /**
     * @return Phone
     */
    public function phone() : Phone
    {
        return $this->phone;
    }
}
