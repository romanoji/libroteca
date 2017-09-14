<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model;

class Phone
{
    private const E123_FORMAT = '/^\+?(?:[0-9] ?){6,14}[0-9]$/';

    /** @var string */
    private $phone;

    /**
     * @param $phone
     * @throws \InvalidArgumentException
     */
    public function __construct(string $phone)
    {
        $this->setPhone($phone);
    }

    /**
     * @param string $phone
     * @throws \InvalidArgumentException
     */
    private function setPhone(string $phone)
    {
        $this->assertValidFormat($phone);

        $this->phone = $phone;
    }

    /**
     * @param string $phone
     * @throws \InvalidArgumentException
     */
    private function assertValidFormat(string $phone)
    {
        if (!preg_match(self::E123_FORMAT, $phone)) {
            throw new \InvalidArgumentException('Invalid phone format.');
        }
    }

    /**
     * @return string
     */
    public function phone(): string
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->phone;
    }

    /**
     * @param Phone $phone
     * @return bool
     */
    public function equals(Phone $phone): bool
    {
        return $this->phone() === $phone->phone();
    }
}
