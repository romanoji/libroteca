<?php

namespace RJozwiak\Libroteca\Domain\Model\Reader;

class Phone
{
    private const E123_FORMAT = '/^\+?(?:[0-9] ?){6,14}[0-9]$/';

    /** @var string */
    private $phone;

    /**
     * Phone constructor.
     * @param $phone
     * @throws \InvalidArgumentException
     */
    public function __construct($phone)
    {
        $this->setPhone($phone);
    }

    /**
     * @param string $phone
     * @throws \InvalidArgumentException
     */
    private function setPhone($phone)
    {
        $this->assertValidFormat($phone);

        $this->phone = $phone;
    }

    /**
     * @param string $phone
     * @throws \InvalidArgumentException
     */
    private function assertValidFormat($phone)
    {
        if (!preg_match(self::E123_FORMAT, $phone)) {
            throw new \InvalidArgumentException('Invalid phone format.');
        }
    }

    /**
     * @return string
     */
    public function phone()
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->phone;
    }

    /**
     * @param Phone $phone
     * @return bool
     */
    public function equals(Phone $phone)
    {
        return $this->phone() === $phone->phone();
    }
}
