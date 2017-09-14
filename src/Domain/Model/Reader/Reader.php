<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\Reader;

use RJozwiak\Libroteca\Domain\Model\AggregateRoot;
use RJozwiak\Libroteca\Domain\Model\Email;
use RJozwiak\Libroteca\Domain\Model\Phone;

class Reader extends AggregateRoot
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

    /** @var \DateTimeImmutable */
    private $createdAt;

    /**
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

        // TODO: created at field

        // TODO: ReaderRegistered event
    }

    /**
     * @return ReaderID
     */
    public function id(): ReaderID
    {
        return $this->id;
    }

    /**
     * @return Name
     */
    public function name(): Name
    {
        return $this->name;
    }

    /**
     * @return Surname
     */
    public function surname(): Surname
    {
        return $this->surname;
    }

    /**
     * @return Email
     */
    public function email(): Email
    {
        return $this->email;
    }

    /**
     * @return Phone
     */
    public function phone(): Phone
    {
        return $this->phone;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function createdAt(): \DateTimeImmutable
    {
        return new \DateTimeImmutable(); // TODO: stub
    }
}
