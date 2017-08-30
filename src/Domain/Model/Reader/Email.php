<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\Reader;

class Email
{
    /** @var string */
    private $email;

    /**
     * @param string $email
     * @throws \InvalidArgumentException
     */
    public function __construct(string $email)
    {
        $this->setEmail($email);
    }

    /**
     * @param string $email
     * @throws \InvalidArgumentException
     */
    private function setEmail(string $email)
    {
        $this->assertValidFormat($email);

        $this->email = $email;
    }

    /**
     * @param string $email
     * @throws \InvalidArgumentException
     */
    private function assertValidFormat(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email format.');
        }
    }

    /**
     * @return string
     */
    public function email() : string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->email;
    }

    /**
     * @param Email $email
     * @return bool
     */
    public function equals(Email $email) : bool
    {
        return $this->email() === $email->email();
    }
}
