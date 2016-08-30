<?php

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
    ) {
        $this->setID($id);
        $this->setName($name);
        $this->setSurname($surname);
        $this->setEmail($email);
        $this->setPhone($phone);
    }

    /**
     * @param ReaderID $id
     */
    private function setID(ReaderID $id)
    {
        $this->id = $id;
    }

    /**
     * @param Name $name
     */
    private function setName(Name $name)
    {
        $this->name = $name;
    }

    /**
     * @param Surname $surname
     */
    private function setSurname(Surname $surname)
    {
        $this->surname = $surname;
    }

    /**
     * @param Email $email
     */
    private function setEmail(Email $email)
    {
        $this->email = $email;
    }

    /**
     * @param Phone $phone
     */
    private function setPhone(Phone $phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return ReaderID
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function fullname()
    {
        return $this->name.' '.$this->surname;
    }

    /**
     * @return Email
     */
    public function email()
    {
        return $this->email;
    }

    /**
     * @return Phone
     */
    public function phone()
    {
        return $this->phone;
    }
}
