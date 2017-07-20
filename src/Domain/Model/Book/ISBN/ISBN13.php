<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Domain\Model\Book\ISBN;

class ISBN13 extends ISBN
{
    private const FORMAT = '/^\d{13}$/';

    /**
     * @return string
     */
    protected function format() : string
    {
        return self::FORMAT;
    }

    /**
     * @param string $isbn
     * @return string
     */
    protected function checksumDigit(?string $isbn) : string
    {
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += (($i % 2 == 0) ? 1 : 3) * $isbn[$i];
        }
        $controlNumber = 10 - ($sum % 10);

        return $controlNumber == 10 ? '0' : (string) $controlNumber;
    }
}
