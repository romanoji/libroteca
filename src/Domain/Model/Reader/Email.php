<?php

namespace RJozwiak\Libroteca\Domain\Model\Reader;

class Email
{
    /** @var string */
    private $email;

    /**
     * Email constructor.
     * @param string $email
     * @throws \InvalidArgumentException
     */
    public function __construct($email)
    {
        $this->setEmail($email);
    }

    /**
     * @param string $email
     * @throws \InvalidArgumentException
     */
    private function setEmail($email)
    {
        $this->assertValidFormat($email);

        $this->email = $email;
    }

    /**
     * @param string $email
     * @throws \InvalidArgumentException
     */
    private function assertValidFormat($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email format.');
        }
    }

    /**
     * @return string
     */
    public function email()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->email;
    }

    /**
     * @param Email $email
     * @return bool
     */
    public function equals(Email $email)
    {
        return $this->email() === $email->email();
    }
}
